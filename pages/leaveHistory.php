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
?>
<?php include ('../includes/header.php'); ?>

<div class="content-tabs" >
  <div id='cssmenu'>
  <ul>
     <li><a href='../pages/leaveRequest.php'>Request Leave</a></li>
     <li><a href='../pages/summaryReport.php'>Leave Balance</a></li>
     <li class='active'><a href='../pages/leaveHistory.php'>Leave History</a></li>
     <li><a href='../pages/cancelLeave.php'>Cancel Leave</a></li>
     <li><a href='../pages/profile.php'>User Profile</a></li>
     <li class='<?php echo $mss; ?>'><a href='../pages/viewCalendar.php'>Calendar</a></li>
     <li class='<?php echo $mss; ?>'><a href='../pages/pendingDecision.php'>Pendings</a></li>
     <li><a href='../pages/ess_home.php'>Info</a></li>
  </ul>
  </div>
	<form id="formType" action="#" method="post" class="form-inline content_form">
	  	<div class="form-group">
		    <label for="dFrom">From:</label>
		    <input type="text" id="dFrom" class="form-control" required/>
	  	</div>
	  	<div class="form-group">
		    <label for="dTo">To:</label>
		    <input type="text" id="dTo" class="form-control" required/>
	  	</div>
	  	<div class="form-group">
	  	<?php
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
		?>
		    <select id="type" class="form-control">
				<option value="">Select a leave type</option>
				<option value="0">All Types of Leaves</option>

		<?php
				$n = $dt->Count();
				for ($x = 0; $x < $n; $x++)
				{
					$dt->MoveNext();
					echo "<option value=" . $dt->id . ">" . $dt->type . "</option>";
				}
		?>
			</select>
		<?php
			}
		catch (Exception $ex)
		{
		    $db->Rollback();
		    echo "Exception Caught: " . $ex->getMessage() .
		         "<p>Trace: " . $ex->getTraceAsString() . "</p>";
		}
		?>
	  	</div>
	</form>
<div id="histContent"></div>

</div>
<?php include ('../includes/footer.php'); ?>
