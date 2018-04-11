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

	$emplNum =  $_SESSION['emplNum'];

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
	$table = "<table id=\"cancelLeave\" class=\"display\" width=\"100%\" cellspacing=\"0\">";
	$table .= "<thead>";
    $table .= "<tr>";
    $table .= "<th>Start Date</th>";
    $table .= "<th>End date</th>";
    $table .= "<th># Days</th>";
    $table .= "<th>Leave Type</th>";
    $table .= "<th>Status</th>";
    $table .= "<th>Reasons</th>";
    $table .= "<th>Delete</th>";
    $table .= "</tr>";
	$table .= "</thead>";
	$table .= "<tfoot>";
    $table .= "<tr>";
    $table .= "<th>Start Date</th>";
    $table .= "<th>End date</th>";
    $table .= "<th># Days</th>";
    $table .= "<th>Leave Type</th>";
    $table .= "<th>Status</th>";
    $table .= "<th>Reasons</th>";
    $table .= "<th>Delete</th>";
    $table .= "</tr>";
	$table .= "</tfoot>";
	$table .= "<tbody>";
	$dt = $db->Execute("
		SELECT
			l.startDate,
			l.endDate,
			l.numDays,
			s.type,
			n.status,
			n.id
		FROM
			leaveappl as l,
			leaves as s,
			leaveapplstatus as n
		WHERE
			l.id = n.leaveAppl_id
		AND
			l.leaves_id = s.id
		AND
			l.employee_emplNum = '$emplNum'
		AND
			n.status != 'declined'
		AND
		    n.request_cancel = 'no'
		AND
			l.startDate > CURDATE() - INTERVAL 1 DAY
		ORDER By
		    l.startDate,
		    n.status DESC");
		$n = $dt->Count();
		if ($n == 0){
			 // $table .="<tr>";
			 // $table .=  "<td colspan=\"7\"><p>No Leave records...</p></td>";
			 // $table .=  "</tr>";
		}else{
			for ($x = 0; $x < $n; $x++)
			{
				$dt->MoveNext();

				$table .= "<tr>";
				$table .=  "<td>" . $dt->startDate . "</td>";
				$table .=  "<td>" . $dt->endDate . "</td>";
				$table .=  "<td>" . $dt->numDays . "</td>";
				$table .=  "<td>" . $dt->type . "</td>";
	            $table .=  "<td>" . $dt->status . "</td>";
	            $table .=  "<td><textarea class=\"reason\" cols=\"14\" name=\"TextArea1\" rows=\"2\"></textarea></td>";
	            $table .= "<td class=\"delete\"><button type=\"button\" class=\"btn btn-danger\" aria-label=\"Left Align\"><span class=\"glyphicon glyphicon-trash\" aria-hidden=\"true\"></span></button><input type='hidden' class='lID' value=\"$dt->id\" /></td>";
                $table .=  "</tr>";

			}
		}
    $table .= "</tbody>";
    $table .=  "</table>";
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
