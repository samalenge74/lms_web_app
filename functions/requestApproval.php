<?php
    try
{
    include_once("../classes/connection.inc.php");
    include_once("../classes/mysql_database.inc.php");
	
	$PHPprotectV56 = 1001;
			
    $PHPprotectV52 = DBFactory::CreateDatabaseObject("MySqlDatabase");
    $PHPprotectV52->Connect($PHPprotectV25, $PHPprotectV28, $PHPprotectV26, $PHPprotectV27);

	$PHPprotectV52->Begin();
	$PHPprotectV42 = $PHPprotectV52->Execute("
	SELECT 
		e.name, 
		e.email, 
		e.reportsTo 
	FROM 
		employee as e, 
	WHERE
		e.emplNum = $PHPprotectV56");
	$PHPprotectV52->Commit();
	$PHPprotectV56 = $PHPprotectV42->reportsTo;
	
	$PHPprotectV42 = $PHPprotectV52->Execute("
	SELECT 
		e.name, 
		e.email, 
	FROM 
		employee as e, 
	WHERE
		e.emplNum = $PHPprotectV56");
	$PHPprotectV52->Commit();
	$PHPprotectV36 = $PHPprotectV42->name;
	$PHPprotectV82 = $PHPprotectV42->email;

}
catch (Exception $PHPprotectV55)
{
    $PHPprotectV52->Rollback();
    echo "Exception Caught: " . $PHPprotectV55->getMessage() .
         "<p>Trace: " . $PHPprotectV55->getTraceAsString() . "</p>";
}




?>
