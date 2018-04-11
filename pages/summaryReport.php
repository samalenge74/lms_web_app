<?php
if (!isset($_SESSION)) {
        session_start();
    }
$mss = '';
if ($_SESSION['level'] == 1){
    $mss = '';
}else{
    $mss = 'mss_off';
}
include ('../includes/header.php');
echo "<div class=\"content-tabs\" >";
echo "<div id='cssmenu'>
<ul>
   <li><a href='../pages/leaveRequest.php'>Request Leave</a></li>
   <li><a href='../pages/summaryReport.php'>Leave Balance</a></li>
   <li><a href='../pages/leaveHistory.php'>Leave History</a></li>
   <li class='active'><a href='../pages/cancelLeave.php'>Cancel Leave</a></li>
   <li><a href='../pages/profile.php'>User Profile</a></li>
   <li class='$mss'><a href='../pages/viewCalendar.php'>Calendar</a></li>
   <li class='$mss'><a href='../pages/pendingDecision.php'>Pendings</a></li>
   <li><a href='../pages/ess_home.php'>Info</a></li>
</ul>
</div>";
echo"<div class='table_c'>";
	try
	{
	    include_once("../classes/connection.inc.php");
	    include_once("../classes/mysql_database.inc.php");

		$emplNum = $_SESSION['emplNum'];
		$type = array();
		$table = "";
		$db = DBFactory::CreateDatabaseObject("MySqlDatabase");
	    $db->Connect($host, $database, $username, $password);

		$db->Begin();
	    $type = $db->ExecuteArray("
	        SELECT
	        	id
	        FROM
	            leaves");
	    $db->Commit();
		$table = "<table id=\"lSummary\" class=\"display\" width=\"100%\" cellspacing=\"0\">";
		$table .= "<thead>";
		$table .= "<tr>";
        $table .= "<th>Leave Type</th>";
        $table .= "<th>Used Days</th>";
        $table .= "<th>Remaining Days</th>";
        $table .= "</tr>";
		$table .= "</thead>";
		$table .= "<tfoot>";
		$table .= "<tr>";
        $table .= "<th>Leave Type</th>";
        $table .= "<th>Used Days</th>";
        $table .= "<th>Remaining Days</th>";
        $table .= "</tr>";
		$table .= "</tfoot>";
        $table .= "<tbody>";

		foreach ($type as $v)
		{
			$dt = $db->Execute("
			select t1.type, t1.usedDays, (t2.available - t1.usedDays) as remainingDays
				from
				(select l.type, IFNULL(SUM(e.usedDays), 0) as usedDays from employee_leaves as e, leaves as l where e.employee_emplNum = '$emplNum' and e.leaves_id = $v and e.leaves_id = l.id and e.year = year(curdate())) as t1,

				(select available from available_days where emplNum = '$emplNum' and leave_id = $v and year = year(curdate())) as t2");

			$dt->MoveNext();
			$table .= "<tr>";
			$table .=  "<td>" . $dt->type . "</td>";
			$table .=  "<td>" . $dt->usedDays . "</td>";
			$table .=  "<td>" . $dt->remainingDays . "</td>";
			$table .=  "</tr>";
		}

		$table .= "</tbody>";
		$table .= "</table>";
	}
	catch (Exception $ex)
	{
	    $db->Rollback();
	    echo "Exception Caught: " . $ex->getMessage() .
	         "<p>Trace: " . $ex->getTraceAsString() . "</p>";
	}
	echo $table;
echo "</div>";
echo "</div>";
include ('../includes/footer.php');
?>
