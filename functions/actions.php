<?php
if (!isset($_SESSION)) {
        session_start();
    }
$emplNum = $_SESSION['emplNum'];
$lID = $_SESSION['id'];
$approval = $_SESSION['approval'];
$t = $_SESSION['requestType'];
try
{
    include_once("../classes/connection.inc.php");
    include_once("../classes/mysql_database.inc.php");
	
    $db = DBFactory::CreateDatabaseObject("MySqlDatabase");
    $db->Connect($host, $database, $username, $password);
    
    $status = $db->StripSlashes($_POST['status']);
    $applicant = $db->StripSlashes($_POST['applicant']);
    $sDate = $db->StripSlashes($_POST['sDate']);
    $eDate = $db->StripSlashes($_POST['eDate']);
    $comment = $db->StripSlashes($_POST['comments']);
    $n = 0;
  
	if($t == 1){		
		if ($status == "approve"){
			if ($approval == 1){
			    $db->Begin();
				$dt = $db->Execute("
				SELECT 
					e.name 
				FROM 
					employee as e 
				WHERE
					e.username = '$applicant'");
				$dt->MoveNext();
				$name = $dt->name;
				$approval = 2;
				$message = "Good day Leave-M User.\r\n \r\n You have a leave application request from $name.\r\n Follow this link http://lms.matityah.co.za/index.php?id={$lID}&requestType={$t}&approval={$approval} in order to edit the request.\r\n \r\n Leave-M OnTheGo.";
				$dt = $db->Execute("
				SELECT 
					e.name, 
					e.reportsTo 
				FROM 
					employee as e 
				WHERE
					e.emplNum = '$emplNum'");
				$dt->MoveNext();
				$emplNum = $dt->reportsTo;
				$dt = $db->Execute("
				SELECT 
					e.name, 
					e.email 
				FROM 
					employee as e 
				WHERE
					e.emplNum = '$emplNum'");
				
			    $db->Commit();
			    $dt->MoveNext();
				$to = $dt->email;
				$subject = "Leave Request";
				$db->sendMail($to, $subject, $message);
				$n = 1;
			}elseif($approval == 2){
			    $db->Begin();
				$dt = $db->Execute("
				SELECT 
					e.name, 
					e.email 
				FROM 
					employee as e 
				WHERE
					e.username = $applicant");
				$dt->MoveNext();
				$emplEmail = $dt->email;
				$subject = "Leave Request Approved";
				$message = "Good day Leave-M User.\r\n\r\n Your leave application from $sDate to $eDate has been approved.\r\n \r\n Leave-M OnTheGo.";
				$db->Execute("
			        UPDATE leaveapplstatus
			        SET
			            status = '$status', 
			            approved_by = '$emplNum' ,
			            approved_date = NOW()
			        WHERE
			       		leaveAppl_id = $lID");
			    $db->Execute("
				INSERT INTO employee_leaves 
				SELECT 
					s.numDays,
					s.hours, 
					s.leaves_id, 
					s.employee_emplNum, 
					n.id
				FROM 
					leaveappl as s, 
					leaveapplstatus as n 
				WHERE
					s.id = n.leaveAppl_id
				AND 
					s.id = '$lID'");
            $db->Commit();
			$db->sendMail($emplEmail, $subject, $message);
			$n = 1;	
			}
	   }
		else{
		    if ($status == "decline"){
    		    $db->Begin();
    			$db->Execute("
    	        UPDATE leaveapplstatus
    		        SET
    		            status = '$status', 
    		            declined_by = '$emplNum' ,
    		            declined_date = NOW(),
    		            reason_for_decline = '$comment'
    		       WHERE
    		       		leaveAppl_id = $lID");
    				
    				$db->Begin();
    				$dt = $db->Execute("
    				SELECT 
    					e.name, 
    					e.email 
    				FROM 
    					employee as e 
    				WHERE
    					e.username = '$applicant'");
    			    $db->Commit();
    				$dt->MoveNext();
    				$emplEmail = $dt->email;
    				$subject = "Leave Application Declined";
    				$message = "Good day Leave-M User.\r\n\r\n Your leave application from $sDate to $eDate has been declined.\r\n \r\n Leave-M OnTheGo.";
    				$db->sendMail($emplEmail, $subject, $message);	
    				$n = 1;
            }
		}
	}else{
		if ($t == 2){
			if ($status == "approve"){
				if ($approval == 1){
				    $db->Begin();
					$dt = $db->Execute("
					SELECT 
						e.name 
					FROM 
						employee as e 
					WHERE
						e.username = '$applicant'");
					$dt->MoveNext();
					$name = $dt->name;
					$approval = 2;
					$message = "Good day Leave-M User.\r\n \r\n You have a leave cancellation request from $name.\r\n Follow this link http://lms.matityah.co.za/index.php?id={$leaveApplID}&requestType={$t}&approval={$approval} in order to edit the request.\r\n \r\n Leave-M OnTheGo.";
					$dt = $db->Execute("
					SELECT 
						e.name, 
						e.reportsTo 
					FROM 
						employee as e 
					WHERE
						e.emplNum = '$emplNum'");
					$dt->MoveNext();
					$emplNum = $dt->reportsTo;
					$dt = $db->Execute("
					SELECT 
						e.name, 
						e.email 
					FROM 
						employee as e 
					WHERE
						e.emplNum = '$emplNum'");
					
				    $db->Commit();
				    $dt->MoveNext();
					$to = $dt->email;
					
					$db->sendMail($to, $subject, $message);
					$n = 1;
				}elseif($approval == 2){
				    $db->Begin();
					$dt = $db->Execute("
					SELECT 
						e.name, 
						e.email 
					FROM 
						employee as e 
					WHERE
						e.username = '$applicant'");
					$dt->MoveNext();
					$emplEmail = $dt->email;
					$subject = "Leave Cancellation Approved";
					$message = "Good day Leave-M User.\r\n\r\n Your leave cancellation request from $sDate to $eDate has been approved.\r\n \r\n Leave-M OnTheGo.";
					$db->Execute("
					   INSERT INTO
					       leavecancel
					   SELECT 
					       *
                       FROM 
                            leaveappl as l
                       WHERE
                            l.id = $lID");
					$db->Commit();
                    $lcancelID = $db->GetLastInsertID();
                    
                    $db->Execute("
                        INSERT INTO
                            leavecancelstatus
                        VALUES(
                        $emplNum,
                        NOW(),
                        $lcancelID)");
                        
					$db->Execute("
				        DELETE
				        FROM
				        	leaveappl
				        WHERE
				       		id = $lID");
				    $db->Commit();
				    $db->sendMail($emplEmail, $subject, $message);
					$n = 1;	
				}
		}
		else{
		     if ($status == "decline"){
    		    $db->Begin();
    			$db->Execute("
    	        UPDATE leaveapplstatus
    		        SET
    		            request_cancel = 'no'
    		       WHERE
    		       		leaveAppl_id = $lID");
    					
    				$dt = $db->Execute("
    				SELECT 
    					e.name, 
    					e.email 
    				FROM 
    					employee as e 
    				WHERE
    					e.username = '$applicant'");
                    $db->Commit();
    				$dt->MoveNext();
    				$emplEmail = $dt->email;
    				$subject = "Leave Application Declined";
    				$message = "Good day Leave-M User.\r\n\r\n Your leave application from $sDate to $eDate has been declined.\r\n \r\n Leave-M OnTheGo.";
    				$db->sendMail($emplEmail, $subject, $message);	
    				$n = 1;
               }
		}
		}
	}
	
}
catch (Exception $ex)
{
    $db->Rollback();
    echo "Exception Caught: " . $ex->getMessage() .
         "<p>Trace: " . $ex->getTraceAsString() . "</p>";
}
echo $n;
?>