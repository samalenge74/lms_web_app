<?php

// Copyright (c) 2006 Mark Jundo P. Documento

require_once("database.inc.php");

class MySqlDatabase extends Database
{
    protected $connection;
    protected $host;
    protected $database;
    protected $user;
    protected $password;
		
    function __construct($host = "", $database = "", $user = "", $password = "")
    {
        if ($host != "" && $database != "" && $user != "")
            $this->Connect($host, $database, $user, $password);
    }

    function CloneObject()
    {
        return new MySqlDatabase();
    }

    function GetName()
    {
        return "MySqlDatabase";
    }

    function Connect($host, $database, $user, $password)
    {
        $this->connection = @($GLOBALS["___mysqli_ston"] = mysqli_connect($host,  $user,  $password)) or $this->ThrowException(((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
        $this->host = $host;
        $this->user = $user;
        $this->password = $password;

        @((bool)mysqli_query( $this->connection, "USE " . $database)) or $this->ThrowException(((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
        $this->database = $database;
    }

    function Close()
    {
        if (isset($this->connection))
        {
            @((is_null($___mysqli_res = mysqli_close($this->connection))) ? false : $___mysqli_res) or $this->ThrowException(((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
            unset($this->connection);
        }
    }

    function IsOpen()
    {
        return isset($this->connection);
    }

    function Execute($sql)
    {
        if (!isset($this->connection)) $this->ThrowException("Database connection is not valid");
        $result = @mysqli_query( $this->connection, $sql) or $this->ThrowException(((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));

        if (!$result) return;
        if (!@(($___mysqli_tmp = mysqli_num_fields($result)) ? $___mysqli_tmp : false))
            return;
        else
        {
            $data = array();
            while (($row = mysqli_fetch_array($result))) $data[] = $row;
            return new DataTable($data);
        }
    }
	
	function ExecuteArray($sql)
    {
        if (!isset($this->connection)) $this->ThrowException("Database connection is not valid");
        $result = @mysqli_query( $this->connection, $sql) or $this->ThrowException(((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));

        if (!$result) return;
        if (!@(($___mysqli_tmp = mysqli_num_fields($result)) ? $___mysqli_tmp : false))
            return;
        else
        {
            $data = array();
            while (($row = mysqli_fetch_array($result))) $data[] = $row['id'];
            return new ArrayObject($data);
        }
    }
    
    function ExecuteArr($sql)
    {
        if (!isset($this->connection)) $this->ThrowException("Database connection is not valid");
        $result = @mysqli_query( $this->connection, $sql) or $this->ThrowException(((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));

        if (!$result) return;
        if (!@(($___mysqli_tmp = mysqli_num_fields($result)) ? $___mysqli_tmp : false))
            return;
        else
        {
            $data = array();
            while (($row = mysqli_fetch_assoc($result))) $data[] = $row;
            return ($data);
        }
    }
	
	function ExecuteRaw($sql)
    {
        if (!isset($this->connection)) throw new Exception("Database connection is not valid");
        return @mysqli_query( $this->connection, $sql) or $this->ThrowException(((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)));
    }

    function Begin()
    {
        $this->Execute("SET AUTOCOMMIT=0");
        $this->Execute("START TRANSACTION");
    }
    
    function Commit()
    {
        $this->Execute("COMMIT");
        $this->Execute("SET AUTOCOMMIT=1");
    }
    
    function Rollback()
    {
        $this->Execute("ROLLBACK");
        $this->Execute("SET AUTOCOMMIT=1");
    }

    function GetLastInsertID()
    {
        // I tried mysql_insert_id() but it doesn't work.
        // Can anybody tell me why?
        $row = $this->Execute("SELECT LAST_INSERT_ID() AS id")->Row(0);
        return $row["id"];
    }
	
	function sendMail($to, $subject, $message)
	{
		$headers = 'From: DoNotReply@lms.co.za' . "\r\n" .
        'Reply-To: do_not_reply@lms.co.za' . "\r\n" .
        'X-Mailer: PHP/' . phpversion();
		
		mail($to, $subject, $message, $headers);
	}
	
	function getWorkingDays($startDate,$endDate,$holidays){
	    // do strtotime calculations just once
	    $endDate = strtotime($endDate);
	    $startDate = strtotime($startDate);
	
	
	    //The total number of days between the two dates. We compute the no. of seconds and divide it to 60*60*24
	    //We add one to inlude both dates in the interval.
	    $days = ($endDate - $startDate) / 86400 + 1;
	
	    $no_full_weeks = floor($days / 7);
	    $no_remaining_days = fmod($days, 7);
	
	    //It will return 1 if it's Monday,.. ,7 for Sunday
	    $the_first_day_of_week = date("N", $startDate);
	    $the_last_day_of_week = date("N", $endDate);
	
	    //---->The two can be equal in leap years when february has 29 days, the equal sign is added here
	    //In the first case the whole interval is within a week, in the second case the interval falls in two weeks.
	    if ($the_first_day_of_week <= $the_last_day_of_week) {
        if ($the_first_day_of_week <= 6 && 6 <= $the_last_day_of_week) $no_remaining_days--;
        if ($the_first_day_of_week <= 7 && 7 <= $the_last_day_of_week) $no_remaining_days--;
    }
    else {
        // (edit by Tokes to fix an edge case where the start day was a Sunday
        // and the end day was NOT a Saturday)

        // the day of the week for start is later than the day of the week for end
        if ($the_first_day_of_week == 7) {
            // if the start date is a Sunday, then we definitely subtract 1 day
            $no_remaining_days--;

            if ($the_last_day_of_week == 6) {
                // if the end date is a Saturday, then we subtract another day
                $no_remaining_days--;
            }
        }
        else {
            // the start date was a Saturday (or earlier), and the end date was (Mon..Fri)
            // so we skip an entire weekend and subtract 2 days
            $no_remaining_days -= 2;
        }
    }

    //The no. of business days is: (number of weeks between the two dates) * (5 working days) + the remainder
//---->february in none leap years gave a remainder of 0 but still calculated weekends between first and last day, this is one way to fix it
   $workingDays = $no_full_weeks * 5;
    if ($no_remaining_days > 0 )
    {
      $workingDays += $no_remaining_days;
    }

    //We subtract the holidays
    foreach($holidays as $holiday){
        $time_stamp=strtotime($holiday);
        //If the holiday doesn't fall in weekend
        if ($startDate <= $time_stamp && $time_stamp <= $endDate && date("N",$time_stamp) != 6 && date("N",$time_stamp) != 7)
            $workingDays--;
    }

    return $workingDays;
}
		
};

// Register in DBFactory
DBFactory::RegisterDatabase(new MySqlDatabase());

?>