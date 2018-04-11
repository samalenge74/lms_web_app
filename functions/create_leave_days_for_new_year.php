<?php
if (!isset($_SESSION)) {
        session_start();
    }
try
{
  include_once("../classes/connection.inc.php");
  include_once("../classes/mysql_database.inc.php");

  $db = DBFactory::CreateDatabaseObject("MySqlDatabase");
  $db->Connect($host, $database, $username, $password);
 
  $db->Begin();
  $empl = $db->ExecuteArr("
      SELECT
        emplNum
      FROM
          employee");

  $db->Commit();
  foreach ($empl as $e) {
    $emplNum = $e["emplNum"];
    
    $leave = $db->ExecuteArr("
        SELECT
          id, type
        FROM
            leaves");
    $db->Commit();

    foreach($leave as $l){
      $type = $l["type"];
      $id = $l["id"];
      
      switch ($type) {
        case 'Sick Leave':
          $dt = $db->Execute("
            insert into available_days(available, leave_id, emplNum, year)
            VALUES(12, $id, '$emplNum', year(curdate()))
          ");
          break;

          case 'Annual Leave':
            $dt = $db->Execute("
              insert into available_days(available, leave_id, emplNum, year)
              VALUES(15, $id, '$emplNum', year(curdate()))
            ");
            break;

          case 'Family Responsibility Leave':
            $dt = $db->Execute("
              insert into available_days(available, leave_id, emplNum, year)
              VALUES(3, $id, '$emplNum', year(curdate()))
            ");
            break;

        default:
          # code...
          break;
      }
    }

  }

  $db->Commit();
}
catch (Exception $ex)
{
    $db->Rollback();
    echo "Exception Caught: " . $ex->getMessage() .
         "<p>Trace: " . $ex->getTraceAsString() . "</p>";
}

?>
