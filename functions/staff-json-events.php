<?php
if (!isset($_SESSION)) {
        session_start();
    }
if (!isset($_GET['staff'])) {
        $staff = $_GET['staff'];
    }else{
        $staff = "";
    }

try
{
    include_once("../classes/connection.inc.php");
    include_once("../classes/mysql_database.inc.php");
    
    $db = DBFactory::CreateDatabaseObject("MySqlDatabase");
    $db->Connect($host, $database, $username, $password);
    
    $staff = $db->StripSlashes($_POST['staff']);
    $events = array();
    if ($staff == ""){
        $db->Begin();
        $events = $db->ExecuteArr("
        SELECT 
            l.id, 
            e.name as title, 
            l.startDate as start, 
            l.endDate as end  
        FROM 
            leaveappl as l, 
            leaveapplstatus as n, 
            employee as e 
        WHERE 
            l.id = n.leaveAppl_id 
        AND 
            l.employee_emplNum = e.emplNum 
        AND 
            n.status = 'Approved' 
        AND 
            e.reportsTo = '$emplNum'");
    $db->Commit();
    }else{ 
        $db->Begin();
        $events = $db->ExecuteArr("
            SELECT 
                l.id, 
                e.name as title, 
                l.startDate as start, 
                l.endDate as end  
            FROM 
                leaveappl as l, 
                leaveapplstatus as n, 
                employee as e 
            WHERE
                e.emplNum = $staff
            AND 
                l.id = n.leaveAppl_id 
            AND 
                l.employee_emplNum = e.emplNum 
            AND 
                n.status = 'Approved' 
            AND 
                e.reportsTo = $emplNum");
        $db->Commit();
   }
} 
catch (Exception $ex)
{
    $db->Rollback();
    echo "Exception Caught: " . $ex->getMessage() .
         "<p>Trace: " . $ex->getTraceAsString() . "</p>";
}
echo json_encode ($events);

?>