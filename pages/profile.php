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
  $manager = $_SESSION['reportsTo'];
  $form = "";

  $db = DBFactory::CreateDatabaseObject("MySqlDatabase");
  $db->Connect($host, $database, $username, $password);

  $db->Begin();
  $dt = $db->Execute("
      select
      	  e.name,
          e.email,
          e.jobTitle,
          e.extension,
          e.profile_pic
      from
          employee as e
      where
          e.emplNum = $emplNum");

  $db->Commit();
  $dt->MoveNext();

  $ds = $db->Execute("
    	select
      	e.name
      from
          employee as e
      where
          e.emplNum = $manager");

	$db->Commit();
  $ds->MoveNext();

	$form .= "<table id=\"one-column-emphasis\">";
  $form .= "<colgroup>";
  $form .= "<col class=\"oce-first\" />";
  $form .= "</colgroup>";
  $form .= "<tbody>";
	$form .= "<tr>";
	$form .= "<th><label id=\"Label3\">Name</label></th>";
	$form .= "<td><input id=\"fname\" name=\"fname\" type=\"text\" value=\"$dt->name\" readonly /></td>";
  $form .= "<td><label>Profile Picture</label></td>";
	$form .= "</tr>";
	$form .= "<tr>";
	$form .= "<th><label id=\"Label4\">Job Title</label></th>";
	$form .= "<td><input id=\"email\" name=\"email\" type=\"text\" value=\"$dt->jobTitle\" readonly /></td>";
  $form .= "<td rowspan=\"7\" class=\"\"><img class=\"profile_pic\" src=\"../images/profile_pics/$dt->profile_pic\"></td>";
	$form .= "</tr>";
	$form .= "<tr>";
	$form .= "<th><label id=\"Label5\">Email</label></th>";
	$form .= "<td><input id=\"eDate\" name=\"eDate\" type=\"text\" value=\"$dt->email\" readonly /></td>";
	$form .= "</tr>";
	$form .= "<tr>";
	$form .= "<th><label>Extension</labe></th>";
	$form .= "<td><input id=\"eDate\" name=\"eDate\" type=\"text\" value=\"$dt->extension\" readonly /></td>";
	$form .= "</tr>";
	$form .= "<tr>";
	$form .= "<th><label>reports To</labe></th>";
	$form .= "<td><input id=\"eDate\" name=\"eDate\" type=\"text\" value=\"$ds->name\" readonly /></td>";
	$form .= "</tr>";
	$form .= "<form id=\"rPass\" action=\"#\" method=\"post\" name=\"rPass\">";
  $form .= "<tr>";
	$form .= "<th></th>";
	$form .= "<td><h3>Change Password</h3></td>";
	$form .= "</tr>";
	$form .= "<tr>";
	$form .= "<th><label id=\"Label4\">New Password</label></th>";
	$form .= "<td><input id=\"pwd\" name=\"pwd\" type=\"password\" required/>";
	$form .= "<label id=\"errorPWD\" class=\"formError\"></label></td>";
	$form .= "</tr>";
	$form .= "<tr>";
	$form .= "<th><label id=\"Label5\">Retype Password</label></th>";
	$form .= "<td><input id=\"pwd1\" name=\"pwd1\" type=\"password\" required/>";
	$form .= "<label id=\"errorPWD1\" class=\"formError\"></label>";
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
