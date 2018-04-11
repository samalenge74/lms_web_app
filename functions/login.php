<?php
session_start();
try
{
    include_once("../classes/connection.inc.php");
    include_once("../classes/mysql_database.inc.php");
	
    $db = DBFactory::CreateDatabaseObject("MySqlDatabase");
    $db->Connect($host, $database, $username, $password);
	
    $u = $db->StripSlashes($_POST['username']);
    $p = md5($db->StripSlashes($_POST['password']));

    $n = 0;
	//$id = "";

    $db->Begin();
    $dt = $db->Execute("
         SELECT 
            *
         FROM 
            employee
         WHERE
            username = '$u'
         AND
            password = '$p'");
    $db->Commit();
    $n = $dt->Count();

    if ($n == 1) 
       {
            $dt->MoveNext();
            $_SESSION['emplNum'] = $dt->emplNum; 
            $_SESSION['name'] = $dt->name;
            $_SESSION['level'] = $dt->is_manager;
            $_SESSION['reportsTo'] = $dt->reportsTo;
            
            echo $n;
   }
    else {echo "";};
}
catch (Exception $ex)
{
    $db->Rollback();
    echo "Exception Caught: " . $ex->getMessage() .
         "<p>Trace: " . $ex->getTraceAsString() . "</p>";
}


?>