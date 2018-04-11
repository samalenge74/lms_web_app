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
    
    $db->Begin();
    $events = array();
    $events = $db->ExecuteArr("
        SELECT 
            e.name as title, 
            concat(l.startDate,'T00:00:00') as start, 
            concat(l.endDate,'T23:59:00') as end  
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
    
} 
catch (Exception $ex)
{
    $db->Rollback();
    echo "Exception Caught: " . $ex->getMessage() .
         "<p>Trace: " . $ex->getTraceAsString() . "</p>";
}
echo json_encode ($events);

?>