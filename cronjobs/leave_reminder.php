<?php
try
{
  include_once("../classes/connection.inc.php");
  include_once("../classes/mysql_database.inc.php");

  $db = DBFactory::CreateDatabaseObject("MySqlDatabase");
  $db->Connect($host, $database, $username, $password);
  
  $date = date("Y-m-d", strtotime('tomorrow'));

  $db->Begin();
  $leave = $db->Execute("
    select 
        l.startDate, 
        l.numDays, 
        l.am_pm, 
        l.employee_emplNum,
        e.name,
        e.reportsTo 
    from 
        leaveappl as l, 
        leaveapplstatus as ls, 
        employee as e 
    where 
        l.id = ls.leaveAppl_id 
    and 
        l.employee_emplNum = e.emplNum 
    and 
        l.startDate = '$date'  
    and 
        ls.status = 'approved'
    ");
  $db->Commit();
  $n = $leave->Count();
  
  if ($n != 0){
      
    for ($x =0; $x < $n; $x++){
        $leave->MoveNext();
        $dt=$db->Execute("
            select name, email from employee where emplNum = '$leave->reportsTo'
        ");
        $db->Commit();
        $dt->MoveNext();
        $to = $dt->email;
        $supervisor = $dt->name;
        $message = "Good day $supervisor\r\n \r\n This is to remind you that $leave->name will be on leave from tomorrow for $leave->numDays days.\r\n \r\n Leave-M OnTheGo.";
        $db->sendMail($to, $subject, $message);
    }
    
  }
  
}
catch (Exception $ex)
{
    $db->Rollback();
    echo "Exception Caught: " . $ex->getMessage() .
         "<p>Trace: " . $ex->getTraceAsString() . "</p>";
}
?>

