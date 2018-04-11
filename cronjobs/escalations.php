<?php

try
{
    include_once("../classes/connection.inc.php");
    include_once("../classes/mysql_database.inc.php");
    
    $db = DBFactory::CreateDatabaseObject("MySqlDatabase");
    $db->Connect($host, $database, $username, $password);
    $today = date("Y/m/d");
    $db->Begin();
    $db->Execute("
        SELECT 
            l.id,
        FROM
            leaveappl as l,
            leaveapplstatus as s
        WHERE
            l.dateApplied <= $today
        AND
            s.status = 'pending'
            id = $id");
    $db->Commit();
}
catch (Exception $ex)
{
    $db->Rollback();
    echo "Exception Caught: " . $ex->getMessage() .
         "<p>Trace: " . $ex->getTraceAsString() . "</p>";
}

?>