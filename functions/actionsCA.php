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
    
    $dt3 = $db->Execute("
        select 
            a.id 
        from 
            leaveappl as a, 
            leaveapplstatus as l 
        where 
            l.id = '$id' 
        and 
            l.leaveAppl_id = a.id");
    $dt3->MoveNext();
    $leaveID = $dt3->id;
    
    $subject = "Leave Cancellation Approved";
    $message = "Good day Leave-M User.\r\n\r\n Your leave cancellation from $sDate to $eDate has been approved by $nameReportsTo.\r\n \r\n Leave-M OnTheGo.";
    $db->Execute("
        delete 
        from
            leaveappl
        where
            id = '$leaveID'            
    ");
    $db->Execute("
    INSERT
    INTO
        leavecancelstatus (approved_by, approved_date)
    VALUES ('$emplNum', NOW())
    ");
    
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