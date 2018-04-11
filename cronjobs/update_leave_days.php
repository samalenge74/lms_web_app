<?php
try
{
  include_once("../classes/connection.inc.php");
  include_once("../classes/mysql_database.inc.php");

  $db = DBFactory::CreateDatabaseObject("MySqlDatabase");
  $db->Connect($host, $database, $username, $password);

  $db->Begin();
  $empl = $db->Execute("
      SELECT DISTINCT
        emplNum
      FROM
          available_days");
    $n = $empl->Count();
  
  for ($x = 0; $x < $n; $x++) {
    $empl->MoveNext();
    $dt = $db->Execute("
      update available_days set available = available + 1.25 where emplNum = '$empl->emplNum' and leave_id = 2 and year = year(curdate())
    ");
    $db->commit();
  }
}
catch (Exception $ex)
{
    $db->Rollback();
    echo "Exception Caught: " . $ex->getMessage() .
         "<p>Trace: " . $ex->getTraceAsString() . "</p>";
}


?>
