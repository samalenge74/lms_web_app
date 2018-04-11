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
    $dt = $db->Execute("
      update available_days set available = available + 1.75 where emplNum = '$e' and leave_id = 2 and year = year(curdate());
    ")
  }
}
catch (Exception $ex)
{
    $db->Rollback();
    echo "Exception Caught: " . $ex->getMessage() .
         "<p>Trace: " . $ex->getTraceAsString() . "</p>";
}
$r = $n - $numDays;
echo "$numDays, $r";

?>
