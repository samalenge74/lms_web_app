<?php
if (!isset($_SESSION)) {
        session_start();
    }

try
{
    include_once("../../classes/connection.inc.php");
    include_once("../../classes/mysql_database.inc.php");
    
    $db = DBFactory::CreateDatabaseObject("MySqlDatabase");
    $db->Connect($host, $database, $username, $password);
    
    $managers = array();
    $db->Begin();
    $managers = $db->ExecuteArr("
    SELECT 
        name,
        emplNum
    FROM 
        employee
    WHERE 
        is_manager = '1'");
    $db->Commit();    
} 
catch (Exception $ex)
{
    $db->Rollback();
    echo "Exception Caught: " . $ex->getMessage() .
         "<p>Trace: " . $ex->getTraceAsString() . "</p>";
}
echo json_encode (array('data' => $managers));

?>