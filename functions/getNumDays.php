<?php
if (!isset($_SESSION)) {
        session_start();
    }
try
{
    include_once("../classes/connection.inc.php");
    include_once("../classes/mysql_database.inc.php");

    $holidays = array();
    $holiday = "";
    $dir = dirname(__FILE__);
    $file = $dir . "/holidays.txt";
    $fp = fopen($file, 'r') or die("Unable to open file!");
    $holiday .= fread($fp, filesize($file));

    $holidays = explode(" ", $holiday);

	$emplNum = $_SESSION['emplNum'];
	$n = 0;
	$numDays = 0;
	$r = 0;

    $db = DBFactory::CreateDatabaseObject("MySqlDatabase");
    $db->Connect($host, $database, $username, $password);

	$startDate = $db->StripSlashes($_POST['sDate']);
	$endDate = $db->StripSlashes($_POST['eDate']);
	$leaveID = $db->StripSlashes($_POST['type']);

	$numDays = $db->getWorkingDays($startDate, $endDate, $holidays);
  
  $db->Begin();
  $usedDays = $db->Execute("
    SELECT
      IFNULL(SUM(usedDays), 0) as usedDays
    FROM
      employee_leaves
    WHERE
      employee_emplNum = '$emplNum'
    AND
      leaves_id = $leaveID
    AND
      year = year(curdate())
    ");

  $usedDays->MoveNext();
  $ud = $usedDays->usedDays;

  $availableDays = $db->Execute("
    select
      available
    from
      available_days
    where
      emplNum = '$emplNum'
    and
      leave_id = $leaveID
    and
      year = year(curdate())
  ");

  $availableDays->MoveNext();
  $ad = $availableDays->available;

  $n = $ad - $ud;
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
