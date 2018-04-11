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
        l.am_pm as tod,
        l.leaves_id, 
        l.employee_emplNum as emplNum, 
        e.reportsTo,
        e.tmp_reportsTo,
        e.name,
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
    $tod = $dt->tod;
    $lID = $dt->leaves_id;
    $e = $dt->emplNum;
    $by = $dt->reportsTo;
    $by2 = $dt->tmp_reportsTo;
    $applicant = $dt->emplNum;
    $applicantName = $dt->name;
    $year = date("Y");
    
    if ($emplNum == $by )
    
    {$dt2 = $db->Execute(" 
            select 
                name 
            from 
                employee 
            where   
                emplNum = '$by'");}
    else{
        $dt2 = $db->Execute(" 
            select 
                name 
            from 
                employee 
            where   
                emplNum = '$by2'");
    }
    
    $dt2->MoveNext();
    $nameReportsTo = $dt2->name;
    $subject = "Leave Request Approved";
    $message = "Good day $applicantName.\r\n\r\n Your leave application from $sDate to $eDate has been approved by $nameReportsTo.\r\n \r\n Leave-M OnTheGo.";
    $db->Execute("
    UPDATE leaveapplstatus
    SET
        status = 'approved', 
        approved_by = '$emplNum',
        approved_date = NOW(),
        request_cancel = 'no'
        
    WHERE
        id = '$id'
    ");
    $db->Execute("
    UPDATE employee
    SET
        tmp_reportsTo = 0 
                
    WHERE
        emplNum = $applicant
    ");
    
    $db->Execute("
        INSERT INTO 
            employee_leaves 
        VALUES ('$n', '$h', '$tod', '$year' , '$lID', '$e', '$id')");
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