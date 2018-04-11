<?php
if (!isset($_SESSION)) {
        session_start();
    }
?>
<html>
<body>
<head>
<link href="../Styles/jquery-ui-1.10.4.custom.css" rel="stylesheet" type="text/css" media="all" />
<link href='../Styles/jquery.dataTables.min.css' rel='stylesheet' />


</head>
<body>
<?php
try
{
    include_once("../classes/connection.inc.php");
    include_once("../classes/mysql_database.inc.php");

	$emplNum = $_SESSION['emplNum'];
	$type = array();
	$table = "";

    $db = DBFactory::CreateDatabaseObject("MySqlDatabase");
    $db->Connect($host, $database, $username, $password);
	$t = $db->StripSlashes($_POST['type']);
	$s = $db->StripSlashes($_POST['sDate']);
	$e = $db->StripSlashes($_POST['eDate']);
  $table .= "<div class='table_c'>";
	$table .= "<table id=\"fullReport\" class=\"display\" width=\"100%\" cellspacing=\"0\">";
	$table .= "<thead>";
    $table .= "<tr>";
	$table .= "<th scope=\"col\" >Leave Type</th>";
    $table .= "<th scope=\"col\" >Start Date</th>";
    $table .= "<th scope=\"col\" >End date</th>";
    $table .= "<th scope=\"col\" ># Days</th>";
    $table .= "<th scope=\"col\" ># Hours</th>";
    $table .= "<th scope=\"col\" >AM/PM</th>";
    $table .= "</tr>";
	$table .= "</thead>";
	$table .= "<tfoot>";
    $table .= "<tr>";
	$table .= "<th scope=\"col\" >Leave Type</th>";
    $table .= "<th scope=\"col\" >Start Date</th>";
    $table .= "<th scope=\"col\" >End date</th>";
    $table .= "<th scope=\"col\" ># Days</th>";
    $table .= "<th scope=\"col\" ># Hours</th>";
    $table .= "<th scope=\"col\" >AM/PM</th>";
    $table .= "</tr>";
	$table .= "</tfoot>";
    $table .= "<tbody>";

	$db->Begin();
    $type = $db->ExecuteArray("
        SELECT
        	id
        FROM
            leaves");
    $db->Commit();
	if($t == 0)
		{
		$dt = $db->Execute("
			SELECT
				l.startDate,
				l.endDate,
				p.usedDays,
				p.hours,
				p.am_pm as tod,
				s.type
			FROM
				leaveappl as l,
				leaves as s,
				leaveapplstatus as n,
				employee_leaves as p
			WHERE
				l.id = n.leaveAppl_id
			AND
				n.id = p.leaveapplstatus_id
			AND
				l.leaves_id = s.id
			AND
				l.startDate >= '$s'
			AND
				l.startDate <= '$e'
			AND
				l.employee_emplNum = '$emplNum'");
			$n = $dt->Count();
			if ($n == 0){
				 // $table .="<tr>";
				 // $table .=  "<td colspan=\"7\"><p>No Leave records...</p></td>";
				 // $table .=  "</tr>";
			}
			else{
				for ($x = 0; $x < $n; $x++)
				{
					$dt->MoveNext();
					$table .= "<tr>";
					$table .=  "<td>" . $dt->type . "</td>";
					$table .=  "<td>" . $dt->startDate . "</td>";
					$table .=  "<td>" . $dt->endDate . "</td>";
					$table .=  "<td>" . $dt->usedDays . "</td>";
					$table .=  "<td>" . $dt->hours . "</td>";
                    $table .=  "<td>" . $dt->tod . "</td>";
					$table .=  "</tr>";
				}
			}
          }else{
			$dt = $db->Execute("
			SELECT
				l.startDate,
				l.endDate,
				p.usedDays,
				p.hours,
				s.type,
				n.status
			FROM
				leaveappl as l,
				leaves as s,
				leaveapplstatus as n,
				employee_leaves as p
			WHERE
				l.id = n.leaveAppl_id
			AND
				n.id = p.leaveapplstatus_id
			AND
				l.leaves_id = s.id
			AND
				l.startDate >= '$s'
			AND
				l.startDate <= '$e'
			AND
				l.employee_emplNum = '$emplNum'
			AND
				s.id = $t");
			$n = $dt->Count();
			if ($n == 0){
				 // $table .="<tr>";
				 // $table .=  "<td colspan=\"6\"><p>No Leave records...</p></td>";
				 // $table .=  "</tr>";
			}else{
				for ($x = 0; $x < $n; $x++)
				{
					$dt->MoveNext();
					$table .= "<tr>";
					$table .=  "<td>" . $i . "</td>";
					$table .=  "<td>" . $dt->startDate . "</td>";
					$table .=  "<td>" . $dt->endDate . "</td>";
					$table .=  "<td>" . $dt->usedDays . "</td>";
					$table .=  "<td>" . $dt->hours . "</td>";
                    $table .=  "<td>" . $dt->tod . "</td>";
					$table .=  "</tr>";
					$i++;
				}
			}

       	}
    $table .= "</tbody>";
	$table .=  "</table>";
  $table .=  "</div>";
}
catch (Exception $ex)
{
    $db->Rollback();
    echo "Exception Caught: " . $ex->getMessage() .
         "<p>Trace: " . $ex->getTraceAsString() . "</p>";
}

echo $table;

?>
<script type="text/javascript" src="../Scripts/jquery-2.0.3.min.js"></script>
<script type="text/javascript" src="../Scripts/jquery-ui-1.10.4.custom.js"></script>
<script src='../Scripts/fullcalendar.min.js'></script>
<script src='../Scripts/jquery.dataTables.min.js'></script>
<script src='../Scripts/dataTables.buttons.min.js'></script>
<script src='../bower_components/gasparesganga-jquery-loading-overlay/src/loadingoverlay.min.js'></script>
<script type="text/javascript" src="../Scripts/jquery-ui-1.10.4.custom.min.js"></script>
<script type="text/javascript" src="../Scripts/functions.js"></script>
</body>
</html>
