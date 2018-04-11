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
  $empl = $db->ExecuteArray("
      SELECT
        emplNum
      FROM
          employee");
  $db->Commit();
  foreach ($empl as $e) {

    $leave = $db->ExecuteArray("
        SELECT
          id, type
        FROM
            leaves");
    $db->Commit();

    foreach($leave as $l){
      switch ($l->type) {
        case 'Sick':
          $dt = $db->Execute("
            insert into available_days(available, leave_id, emplNum, year)
            VALUES(12, $l->id, '$e', year(curdate()))
          ");
          break;

          case 'Annual':
            $dt = $db->Execute("
              insert into available_days(available, leave_id, emplNum, year)
              VALUES(1.75, $l->id, '$e', year(curdate()))
            ");
            break;

          case 'Family Responsibility':
            $dt = $db->Execute("
              insert into available_days(available, leave_id, emplNum, year)
              VALUES(8, $l->id, '$e', year(curdate()))
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
