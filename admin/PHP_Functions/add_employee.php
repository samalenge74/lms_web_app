<?php
if (!isset($_SESSION)) {
        session_start();
    }

$admin = $_SESSION['emplNum'];
$y = date("Y");
$n = 0;
try
{
    include_once("../../classes/connection.inc.php");
    include_once("../../classes/mysql_database.inc.php");

	$db = DBFactory::CreateDatabaseObject("MySqlDatabase");
        $db->Connect($host, $database, $username, $password);

	$empl_num = $db->StripSlashes($_POST['empl_num']);
	$empl_name = $db->StripSlashes($_POST['empl_name']);
	$empl_email = $db->StripSlashes($_POST['empl_email']);
	$empl_job_title = $db->StripSlashes($_POST['empl_job_title']);
  $empl_ext = $db->StripSlashes($_POST['empl_ext']);
	$empl_username = $db->StripSlashes($_POST['username']);
	$empl_password = $db->StripSlashes($_POST['empl_password']);
  $empl_password_encrypted = md5($empl_password);
	$manager = $db->StripSlashes($_POST['manager']);
  $is_manager = $db->StripSlashes($_POST['is_manager']);
  $is_admin = $db->StripSlashes($_POST['is_admin']);

	$subject = "LMS Account Created";
	$message = "";
	$to ="";

    $db->Begin();
    $db->Execute("
        INSERT INTO employee(
            emplNum,
            name,
            email,
            jobTitle,
            extension,
            reportsTo,
            username,
            password,
            is_manager,
            is_admin,
            added_by,
            date_added)
        VALUES(
            '$empl_num',
            '$empl_name',
            '$empl_email',
            '$empl_job_title',
            '$empl_ext',
            '$manager',
            '$empl_username',
            '$empl_password_encrypted',
            '$is_manager',
            '$is_admin',
            '$admin',
            NOW())");
    $db->Commit();
    $to = $empl_email;
    $message .= "Good day $empl_name\r\n \r\n You have been added to the Leave-M system.\r\n\r\n Visit http://lms.matityah.co.za/ . \r\n Username: $empl_username .\r\n Password: $empl_password.\r\n \r\n Leave-M OnTheGo.";
    $db->sendMail($to, $subject, $message);

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
