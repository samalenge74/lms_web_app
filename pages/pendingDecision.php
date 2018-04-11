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
   <li><a href='../pages/cancelLeave.php'>Cancel Leave</a></li>
   <li><a href='../pages/profile.php'>User Profile</a></li>
   <li class='$mss'><a href='../pages/viewCalendar.php'>Calendar</a></li>
   <li class='$mss active'><a href='../pages/pendingDecision.php'>Pendings</a></li>
   <li><a href='../pages/ess_home.php'>Info</a></li>
</ul>
</div>";
echo"<div class='table_c'>";
    $emplNum = $_SESSION['emplNum'];
    $disabled = "";
    $table = "";
    $table = "<table id=\"lra\" class=\"display\" width=\"100%\" cellspacing=\"0\">";
    $table .= "<thead>";
    $table .= "<tr>";
    $table .= "<th scope=\"col\" >Type</th>";
    $table .= "<th scope=\"col\" >Name</th>";
    $table .= "<th scope=\"col\" >Date applied</th>";
    $table .= "<th scope=\"col\" >Open</th>";
    $table .= "</tr>";
    $table .= "</thead>";
    $table .= "<tfoot>";
    $table .= "<tr>";
    $table .= "<th scope=\"col\" >Type</th>";
    $table .= "<th scope=\"col\" >Name</th>";
    $table .= "<th scope=\"col\" >Date applied</th>";
    $table .= "<th scope=\"col\" >Open</th>";
    $table .= "</tr>";
    $table .= "</tfoot>";
    $table .= "<tbody>";

    try
    {

        include_once("../classes/connection.inc.php");
        include_once("../classes/mysql_database.inc.php");

        $db = DBFactory::CreateDatabaseObject("MySqlDatabase");
        $db->Connect($host, $database, $username, $password);

        $db->Begin();
        $dt = $db->Execute("
            SELECT
                s.id,
                l.type,
                e.name,
                a.startDate,
                a.endDate,
                a.numDays,
                a.dateApplied,
                a.comments,
                a.med_cert,
                s.status,
                s.request_cancel
            FROM
                leaves as l,
                employee as e,
                leaveappl as a,
                leaveapplstatus as s
            WHERE
                s.status = 'pending'
            AND
                s.leaveAppl_id = a.id
            AND
                a.leaves_id = l.id
            AND
                a.employee_emplNum = e.emplNum
            AND
                e.reportsTo = '$emplNum'
            AND
                e.tmp_reportsTo = 0
            ORDER BY
                a.dateApplied ");

        $n = $dt->Count();
        if ($n >= 1){
            for ($x = 0; $x < $n; $x++)
            {
                $dt->MoveNext();
                if($dt->request_cancel == "yes"){
                    $disabled = "disabled";
                }else{
                    $disabled = "";
                }
                $table .= "<tr>";
                $table .=  "<td>" . $dt->type . "</td>";
                $table .=  "<td>" . $dt->name . "</td>";
                $table .=  "<td>" . $dt->dateApplied . "</td>";
                $table .= "<td><a href=\"leaveRequestAction.php?id=" . $dt->id . "\"><button type=\"button\" class=\"btn btn-info\" " . $disabled . " ><span class=\"glyphicon glyphicon-hand-right\"></span></button></a></td>";
                $table .=  "</tr>";

            }
        }  else {

            // $table .="<tr>";
            // $table .=  "<td colspan=\"4\"><p>No actions required...</p></td>";
            // $table .=  "</tr>";
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

?>
  <div class="accordion-option">
    <a href="javascript:void(0)" class="toggle-accordion active" accordion-id="#accordion"></a>
  </div>
  <div class="clearfix"></div>
  <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
    <div class="panel panel-default">
      <div class="panel-heading" role="tab" id="headingOne">
        <h4 class="panel-title">
        <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
          View Leave Requests
        </a>
      </h4>
      </div>
      <div id="collapseOne" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
        <div class="panel-body">
          <?php echo $table; ?>
        </div>
      </div>
    </div>
<?php
    $table = "";
    $table = "<table id=\"lra\" class=\"display\" width=\"100%\" cellspacing=\"0\">";
    $table .= "<thead>";
    $table .= "<tr>";
    $table .= "<th scope=\"col\" >Type</th>";
    $table .= "<th scope=\"col\" >Name</th>";
    $table .= "<th scope=\"col\" >Leave status</th>";
    $table .= "<th scope=\"col\" >Open</th>";
    $table .= "</tr>";
    $table .= "</thead>";
    $table .= "<tfoot>";
    $table .= "<tr>";
    $table .= "<th scope=\"col\" >Type</th>";
    $table .= "<th scope=\"col\" >Name</th>";
    $table .= "<th scope=\"col\" >Leave status</th>";
    $table .= "<th scope=\"col\" >Open</th>";
    $table .= "</tr>";
    $table .= "</tfoot>";
    $table .= "<tbody>";

    try
    {

        include_once("../classes/connection.inc.php");
        include_once("../classes/mysql_database.inc.php");

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

        $n = $dt->Count();
        if ($n >= 1){
            for ($x = 0; $x < $n; $x++)
            {
                $dt->MoveNext();
                $table .= "<tr>";
                $table .=  "<td>" . $dt->type . "</td>";
                $table .=  "<td>" . $dt->name . "</td>";
                $table .=  "<td>" . $dt->status . "</td>";
                $table .= "<td><a href=\"leaveCancellationAction.php?id=" . $dt->id . "\"><button type=\"button\" class=\"btn btn-default\" aria-label=\"Left Align\">
      <span class=\"glyphicon glyphicon-hand-right\" aria-hidden=\"true\"></span>
    </button></a></td>";
                $table .=  "</tr>";

            }
        }  else {
            // $table .="<tr>";
            // $table .=  "<td colspan=\"4\"><p>No actions required...</p></td>";
            // $table .=  "</tr>";
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
?>
    <div class="panel panel-default">
      <div class="panel-heading" role="tab" id="headingTwo">
        <h4 class="panel-title">
        <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo">
          View All Leave Cancellations
        </a>
      </h4>
      </div>
      <div id="collapseTwo" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingTwo">
        <div class="panel-body">
           <?php echo $table; ?>
        </div>
      </div>
    </div>
  </div>

<?php
    echo "</div>";
    echo "</div>";
    include ('../includes/footer.php');
