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
try
{
    include_once("../classes/connection.inc.php");
    include_once("../classes/mysql_database.inc.php");

	$emplNum = $_SESSION['emplNum'];
	$i = 1;
	$type = array();
	$form = "";

    $db = DBFactory::CreateDatabaseObject("MySqlDatabase");
    $db->Connect($host, $database, $username, $password);

	$db->Begin();
    $dt = $db->Execute("
        SELECT
        	id,
        	type
        FROM
            leaves");
    $db->Commit();

	$form .= "<form id=\"applyLeave\" action=\"#\" method=\"post\" name=\"applyLeave\">";
	$form .= "<table id=\"one-column-emphasis\">";
  $form .= "<colgroup>";
  $form .= "<col class=\"oce-first\" />";
  $form .= "</colgroup>";
  $form .= "<tbody>";
	$form .= "<tr>";
	$form .= "<th><label id=\"Label3\">Leave Type</label></th>";
	$form .= "<td><select id=\"type\" name=\"type\">";
	$form .= "<option value=\"\">Select One</option>";
	$n = $dt->Count();
	for ($x = 0; $x < $n; $x++)
	{
		$dt->MoveNext();
		$form .= "<option value=" . $dt->id . ">" . $dt->type . "</option>";
	}
	$form .= "</select>";
	$form .= "<label id=\"errorType\" class=\"formError\"></label></td>";
	$form .= "<td class=\"lDaysD\"><label>Availble Days per leave type:</label></td>";
	$form .= "</tr>";
	$form .= "<tr>";
	$form .= "<th><label id=\"Label4\">Start Date</label></th>";
	$form .= "<td><input id=\"sDate\" name=\"sDate\" type=\"text\" />";
	$form .= "<label id=\"errorSDate\" class=\"formError\"></label></td>";
	$form .= "<td rowspan=\"7\" class=\"lDaysDipl\"><input type=\"text\" id=\"lDays\" class=\"lDaysDipl\" readonly /></td>";
	$form .= "</tr>";
	$form .= "<tr>";
	$form .= "<th><label id=\"Label5\">End Date</label></th>";
	$form .= "<td><input id=\"eDate\" name=\"eDate\" type=\"text\" />";
	$form .= "<label id=\"errorEDate\" class=\"formError\"></label>";
	$form .= "<input type=\"hidden\" id=\"numDays\" value=\"\" /></td>";
	$form .= "</tr>";
	$form .= "<tr>";
	$form .= "<th><label>Full/Half Day</labe></th>";
	$form .= "<td><select id=\"fhDay\" name=\"fhDay\">";
	$form .= "<option value=\"0\">Select One</option>";
	$form .= "<option value=\"1\">Full Day</option>";
	$form .= "<option value=\"2\">Hafl Day - Morning</option>";
	$form .= "<option value=\"3\">Half Day - Afternoon</option>";
	$form .= "</select><input type=\"hidden\" id=\"hours\" value=\"\" /><label id=\"errorFHDay\" class=\"formError\"></label></td>";
	$form .= "</tr>";
	$form .= "<tr>";
	$form .= "<th><label id=\"Label5\">Start Time</label></th>";
	$form .= "<td><input id=\"sTime\" name=\"sTime\" type=\"text\" readonly/></td>";
	$form .= "</tr>";
	$form .= "<tr>";
	$form .= "<th><label id=\"Label5\">End Time</label></th>";
	$form .= "<td><input id=\"eTime\" name=\"eTime\" type=\"text\" readonly/></td>";
	$form .= "</tr>";
	$form .= "<tr>";
	$form .= "<th><label id=\"Label6\">Medical Certificate?</label></th>";
	$form .= "<td><select id=\"medCert\" name=\"medCert\">";
	$form .= "<option value=\"0\">Select One</option>";
	$form .= "<option value=\"1\">Medical Certificate Submitted</option>";
	$form .= "<option value=\"2\">No Medical Certificate</option>";
	$form .= "</select><label id=\"errorMedCert\" class=\"formError\"></label></td>";
	$form .= "</tr>";
	$form .= "<tr>";
	$form .= "<th><label id=\"Label7\">Comments</label></th>";
	$form .= "<td >";
	$form .= "<textarea id=\"comments\" cols=\"22\" name=\"TextArea1\" rows=\"2\"></textarea></td>";
	$form .= "</tr>";
	$form .= "<tr>";
	$form .= "<td></td>";
	$form .= "<td colspan=\"2\"><input id=\"submit\" name=\"Submit1\" type=\"submit\" value=\"submit\" />&nbsp;&nbsp;&nbsp;&nbsp;";
  $form .= "<button id=\"resetting\" class=\"btn-tabs\" name=\"resetting\">Reset</button></td>";
	$form .= "</tr>";
	$form .= "</tbody>";
	$form .= "</table>";
	$form .= "</form>";
	}
catch (Exception $ex)
{
    $db->Rollback();
    echo "Exception Caught: " . $ex->getMessage() .
         "<p>Trace: " . $ex->getTraceAsString() . "</p>";
}

echo $form;
echo "</div>";
include ('../includes/footer.php');
?>
