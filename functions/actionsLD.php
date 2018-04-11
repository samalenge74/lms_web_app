<?php
if (!isset($_SESSION)) {
        session_start();
    }
$emplNum = $_SESSION['emplNum'];

try
{
    include_once("../classes/connection.inc.php");
    include_once("../classes/mysql_database.inc.php");
	
    $db = DBFactory::CreateDatabaseObject("MySqlDatabase");
    $db->Connect($host, $database, $username, $password);
    
    $id = $db->StripSlashes($_POST['id']);
    $reasosn = $db->StripSlashes($_POST['reason']);
    
    $n = 0;
  	
    $db->Begin();
    $dt = $db->Execute("
    select 
        l.startDate, 
        l.endDate, 
        l.numDays, 
        l.hours, 
        l.leaves_id, 
        l.employee_emplNum as emplNum, 
        e.reportsTo,
        e.email 
    from 
        leaveappl as l, 
        leaveapplstatus as s, 
        employee as e 
    where 
        s.id = '$id'
    and 
        s.leaveAppl_id = l.id 
    and 
        l.employee_emplNum = e.emplNum");
    $dt->MoveNext();
    $sDate = $dt->startDate;
    $eDate = $dt->endDate;
    $emplEmail = $dt->email;
    $n = $dt->numDays;
    $h = $dt->hours;
    $lID = $dt->leaves_id;
    $e = $dt->emplNum;
    $by = $dt->reportsTo;
    $dt2 = $db->Execute(" 
        select 
            name 
        from 
            employee 
        where   
            emplNum = '$by'");
    $dt2->MoveNext();
    $nameReportsTo = $dt2->name;
    $subject = "Leave Request Declined";
    $message = "Good day Leave-M User.\r\n\r\n Your leave application from $sDate to $eDate has been declined by $nameReportsTo.\r\n \r\n Leave-M OnTheGo.";
    $db->Execute("
    UPDATE leaveapplstatus
    SET
        status = 'declined', 
        declined_by = '$emplNum' ,
        declined_date = NOW(),
        reason_for_decline = '$reasosn',
        request_cancel = 'no'
    WHERE
        id = '$id'");
    $db->Commit();
    $db->sendMail($emplEmail, $subject, $message);
    $n = 1;	

}
catch (Exception $ex)
{
    $db->Rollback();
    echo "Exception Caught: " . $ex->getMessage() .
         "<p>Trace: " . $ex->getTraceAsString() . "</p>";
}
echo $n;
?>