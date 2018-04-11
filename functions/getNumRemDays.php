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

	$leaveID = $db->StripSlashes($_POST['type']);
	$emplNum = $_SESSION['emplNum'];

  $db->Begin();
  $usedDays = $db->Execute("
    select (t2.available - t1.usedDays) as usedDays 
    from (
        SELECT IFNULL(SUM(usedDays), 0) as usedDays 
        FROM employee_leaves WHERE employee_emplNum = '$emplNum' 
        AND leaves_id = $leaveID 
        AND year = year(curdate())) as t1, 
        (
        SELECT available 
        FROM available_days where emplNum = '$emplNum' 
        AND leave_id = $leaveID 
        AND year = year(curdate())) as t2
    ");

  $usedDays->MoveNext();
  $ud = $usedDays->usedDays;
}
catch (Exception $ex)
{
    $db->Rollback();
    echo "Exception Caught: " . $ex->getMessage() .
         "<p>Trace: " . $ex->getTraceAsString() . "</p>";
}
	echo $ud;
?>
