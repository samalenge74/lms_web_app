<?php
if (!isset($_SESSION)) {
        session_start();
    }
try
{
    include_once("../classes/connection.inc.php");
    include_once("../classes/mysql_database.inc.php");
	
	$id = $_POST['fhDay'];
				
    $db = DBFactory::CreateDatabaseObject("MySqlDatabase");
    $db->Connect($host, $database, $username, $password);

	$db->Begin();
    $hours = $db->Execute("
        SELECT 
        	*
        FROM
            hours
        WHERE
        	id = $id");
    $db->Commit();
} 
catch (Exception $ex)
{
    $db->Rollback();
    echo "Exception Caught: " . $ex->getMessage() .
         "<p>Trace: " . $ex->getTraceAsString() . "</p>";
}
$hours->MoveNext();
echo "$hours->fromTime, $hours->toTime, $hours->hours";

?>