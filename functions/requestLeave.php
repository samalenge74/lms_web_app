<?php
if (!isset($_SESSION)) {
        session_start();
    }
try
{
    include_once("../classes/connection.inc.php");
    include_once("../classes/mysql_database.inc.php");
	
	$db = DBFactory::CreateDatabaseObject("MySqlDatabase");
        $db->Connect($host, $database, $username, $password);
	
	$leaveID = $db->StripSlashes($_POST['type']);
	$sDate = $db->StripSlashes($_POST['sDate']); 
	$eDate = $db->StripSlashes($_POST['eDate']); 
	$nDays = $db->StripSlashes($_POST['numDays']);
	$comments = $db->StripSlashes($_POST['comments']);
	$hours = $db->StripSlashes($_POST['hours']);
	$fhDay = $db->StripSlashes($_POST['fhDay']);
        $t = 1;
        $tod = "";
	
	if ($hours == "") 
	{
		$hours = 9.00;
	}
		
	if ($fhDay == "") 
	{
		$fhDay = 0;
	}
        
        if ($fhDay == 1){
            $tod = "full day";
        }
        
        if ($fhDay == 2){
            $tod = "AM";
        }
        
        if ($fhDay == 3){
            $tod = "PM";
        }
		
	if ($nDays == 1 && $hours == 4.50) {$nDays = 0.50;} 
	if ($nDays > 1)	{$hours = $hours * $nDays;}
	
	$medCert = $db->StripSlashes($_POST['medCert']);
	$emplNum = $_SESSION['emplNum'];
	$n = 0;
	$subject = "Leave Request";
	$message = "";
	$to ="";
	$approval = 1;
	//$leaveApplID = 0;

    $db->Begin();
    $db->Execute("
        INSERT INTO leaveappl(
            startDate,
            endDate,
            numDays,
            hours,
            am_pm,
            comments,
            dateApplied,
            leaves_id,
            employee_emplNum,
			hours_id)
        VALUES(
            '$sDate',
            '$eDate',
            '$nDays',
            '$hours',
            '$tod',
            '$comments',
             NOW(),
            '$leaveID',
            '$emplNum',
            '$fhDay')");
        $leaveApplID = $db->GetLastInsertID();
	$db->Execute("
	INSERT INTO leaveapplstatus(
		leaveAppl_id)
	VALUES('$leaveApplID')");
	
	$dt = $db->Execute("
	SELECT 
		e.name, 
		e.reportsTo 
	FROM 
		employee as e 
	WHERE
		e.emplNum = $emplNum");
	
	$dt->MoveNext();
	$name = $dt->name;
	$repotsTo = $dt->reportsTo;

        $dt1 = $db->Execute("
        SELECT 
                e.name, 
                e.email 
        FROM 
                employee as e 
        WHERE
            e.emplNum = $repotsTo");
        $dt1->MoveNext();
        $to = $dt1->email;
        $supervisor = $dt1->name;
        $message = "Good day $supervisor\r\n \r\n You have a leave request from $name.\r\n\r\n Log into http://lms.matityah.co.za/ in order to edit the request.\r\n \r\n Leave-M OnTheGo.";
        $db->sendMail($to, $subject, $message);

	$n = 1;
        $db->Commit();
}
catch (Exception $ex)
{
    $db->Rollback();
    echo "Exception Caught: " . $ex->getMessage() .
         "<p>Trace: " . $ex->getTraceAsString() . "</p>";
}
	echo $n;
?>