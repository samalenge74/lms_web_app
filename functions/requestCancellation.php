<?php
if (!isset($_SESSION)) {
        session_start();
    }
try
{
    include_once("../classes/connection.inc.php");
    include_once("../classes/mysql_database.inc.php");
	
	$emplNum = $_SESSION['emplNum'];
	$n = 0;
	$subject = "Leave Cancellation Request";
	$message = "";
	$to ="";
        			
    $db = DBFactory::CreateDatabaseObject("MySqlDatabase");
    $db->Connect($host, $database, $username, $password);
    $cID = $db->StripSlashes($_POST['id']);
    $r = $db->StripSlashes($_POST['reason']);
	$db->Begin();
	$db->Execute("
	UPDATE 
		leaveapplstatus
	SET
		request_cancel = 'yes',
		request_cancel_date = NOW(),
                reasons_for_cancel = '$r'
	WHERE 
	    id = '$cID'");
	
	$dt = $db->Execute("
	SELECT 
		e.name, 
		e.email, 
		e.reportsTo 
	FROM 
		employee as e 
	WHERE
		e.emplNum = '$emplNum'");
        
	$dt->MoveNext();
	$name = $dt->name;

	$empl = $dt->reportsTo;
	
	$dt2 = $db->Execute("
	SELECT 
		e.name, 
		e.email 
	FROM 
		employee as e 
	WHERE
		e.emplNum = '$empl'");
		
	$db->Commit();
        $dt2->MoveNext();
	$supervisor = $dt2->name;
	$to = $dt2->email;
	$message .= "Good $supervisor.\r\n \r\n You have a leave cancellation request from $name. In order to edit the request, visit the LMS Web Portal.\r\n \r\n Leave-M OnTheGo.";
	$db->sendMail($to, $subject, $message);
	
	$n = 1;
}
catch (Exception $ex)
{
    $db->Rollback();
    echo "Exception Caught: " . $ex->getMessage() .
         "<p>Trace: " . $ex->getTraceAsString() . "</p>";
}

echo $n;

