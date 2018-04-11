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
	
    $p = md5($db->StripSlashes($_POST['pwd']));

    $db->Begin();
    $dt = $db->Execute("
         UPDATE 
            employee
         SET 
            password = 'sp'
         WHERE
            emplNum = '$emplNum'");
    $db->Commit();
    echo "1";
}
catch (Exception $ex)
{
    $db->Rollback();
    echo "Exception Caught: " . $ex->getMessage() .
         "<p>Trace: " . $ex->getTraceAsString() . "</p>";
}

?>