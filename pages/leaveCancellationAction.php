<?php
if (!isset($_SESSION)) {
        session_start();
    }

$id = $_GET['id'];

include ('../includes/header.php');
echo "<div class=\"content-tabs\" >";
try
{
    include_once("../classes/connection.inc.php");
    include_once("../classes/mysql_database.inc.php");
	
    $emplNum = $_SESSION['emplNum'];
    $form = "";
		
    $db = DBFactory::CreateDatabaseObject("MySqlDatabase");
    $db->Connect($host, $database, $username, $password);

    $db->Begin();
    $dt = $db->Execute("
        select 
            l.id, 
            l.status,
            l.reasons_for_cancel as reason, 
            l.request_cancel_date as cancel_date, 
            a.startDate, 
            a.endDate, 
            a.numDays, 
            a.endDate, 
            e.name,
            t.type 
        from 
            leaveapplstatus as l, 
            leaveappl as a, 
            leaves as t, 
            employee as e 
        where
            l.id = $id
        and 
            a.leaves_id = t.id 
        and 
            l.leaveAppl_id = a.id 
        and 
            a.employee_emplNum = e.emplNum 
        and 
            e.reportsTo = '$emplNum' 
        and     
            l.request_cancel = 'yes' 
        order by 
            l.request_cancel_date");
        $db->Commit();
        $dt->MoveNext();
	$form .= "<form id=\"lCancAct\" action=\"#\" method=\"post\" name=\"lReqAct\">";
	$form .= "<table id=\"one-column-emphasis\">";
    $form .= "<colgroup>";
    $form .= "<col class=\"oce-first\" />";
    $form .= "</colgroup>";
    $form .= "<tbody>";
	$form .= "<tr>";
	$form .= "<th><label id=\"Label3\">Leave Type</label></th>";
	$form .= "<td><input id=\"sDate\" name=\"sDate\" type=\"text\" value=" . $dt->type . " readonly /></td>";
	$form .= "</tr>";
	$form .= "<tr>";
	$form .= "<th><label id=\"Label4\">Start Date</label></th>";
	$form .= "<td><input id=\"sDate\" name=\"sDate\" type=\"text\" value=" . $dt->startDate . " readonly /></td>";
	$form .= "</tr>";
	$form .= "<tr>";
	$form .= "<th><label id=\"Label5\">End Date</label></th>";
	$form .= "<td><input id=\"eDate\" name=\"eDate\" type=\"text\" value=" . $dt->endDate . " readonly /></td>";
	$form .= "</tr>";
	$form .= "<tr>";
	$form .= "<th><label>Num. Of Days</labe></th>";
	$form .= "<td><input id=\"eDate\" name=\"eDate\" type=\"text\" value=" . $dt->numDays . " readonly /></td>";
	$form .= "</tr>";
	$form .= "<tr>";
	$form .= "<tr>";
	$form .= "<th><label id=\"Label7\">Reasons for cancel</label></th>";
	$form .= "<td >";
	$form .= "<textarea id=\"comments\" cols=\"22\" name=\"TextArea1\" rows=\"2\" readonly>" . $dt->reason . "</textarea></td>";
	$form .= "</tr>";
    $form .= "<tr>";
	$form .= "<th><label id=\"Label7\">Reasons for decline</label></th>";
	$form .= "<td >";
	$form .= "<textarea id=\"rDecline\" cols=\"22\" name=\"TextArea1\" rows=\"2\"></textarea></td>";
	$form .= "</tr>";
	$form .= "<tr>";
	$form .= "<td><input id=\"lApplID\" name=\"lApplID\" type=\"hidden\" value=" . $id . "></td>";
	$form .= "<td colspan=\"2\"><input id=\"approve\" name=\"approve\" type=\"submit\" value=\"Approve\" />&nbsp;&nbsp;&nbsp;&nbsp;";
    $form .= "<input id=\"decline\" name=\"decline\" type=\"submit\" value=\"Decline\" /><label id=\"reply\"></label></td>";
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

