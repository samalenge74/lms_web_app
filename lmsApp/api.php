<?php
  require_once("Rest.inc.php");

  class API extends REST {

    public $data = "";

    const DB_SERVER = "localhost";
    const DB_USER = "wecaretr_root";
    const DB_PASSWORD = "kDQ4m4L3CFog";
    const DB = "wecaretr_lms";

    private $db = NULL;
    private $mysqli = NULL;
    public function __construct(){
      parent::__construct();        // Init parent contructor
      $this->dbConnect();         // Initiate Database connection
    }

    /*
     *  Connect to Database
    */
  private function dbConnect(){
    $this->mysqli = new mysqli(self::DB_SERVER, self::DB_USER, self::DB_PASSWORD, self::DB);
    // Check connection
    if (mysqli_connect_errno())
    {
      echo "Failed to connect to MySQL: " . mysqli_connect_error();
    }
  }

  /*
   * Dynmically call the method based on the query string
   */
  public function processApi(){
    $func = strtolower(trim(str_replace("/","",$_REQUEST['x'])));
    if((int)method_exists($this,$func) > 0)
      $this->$func();
    else
      $this->response('',404); // If the method not exist with in this class "Page not found".
  }

  private function login(){

    if($this->get_request_method() != "GET"){
      $this->response('',406);
    }
    $id = $this->_request['id'];
    $pw = $this->_request['pw'];
    $result = array();

    if(isset($id) && !empty($id) && isset($pw) && !empty($pw)){
      $query="SELECT DISTINCT emplNum, name, email, is_manager from employee where username = '$id' and password = md5('$pw')";

      $r = $this->mysqli->query($query) or die(mysqli_error($this->mysqli));
      if($r->num_rows == 1) {
          
          while($row = $r->fetch_assoc()){
                  $result[] = $row;
          }
          $this->response($this->json($result), 200); // send user details

      }else{
          $this->response($this->json($result), 404);
      }
    }
    $this->response($this->json($result),204);  // If no records "No Content" status
  }

  private function changePassword(){

    if($this->get_request_method() != "GET"){
      $this->response('',406);
    }
    $id = $this->_request['id'];
    $opw = $this->_request['opw'];
    $pw = $this->_request['pw'];

    if(isset($id) && !empty($id) && isset($pw) && !empty($pw)){
      $query="UPDATE employee SET password = md5('$pw') WHERE emplNum = '$id' AND password = md5('$opw')";
      $result = array();

      $r = $this->mysqli->query($query) or die(mysqli_error($this->mysqli));

      if($this->mysqli->affected_rows == 1) {

          $result = array('response'=>'done');
          $this->response($this->json($result), 200); //

      }else{
          $result = array('response'=>'not done');
          $this->response($this->json($result), 404);
      }
    }
    $this->response('',204);  // If no records "No Content" status
  }

  private function resetPassword(){

    if($this->get_request_method() != "GET"){
      $this->response('',406);
    }
    $id = $this->_request['id'];
    $name = $this->_request['name'];
    $pw = $this->generateStrongPassword();
    $user = $this->getUserDetails($id);
    $name = $user[0];
    $email = $user[1];
    $send_email = "";
    $subject = "Password Reset";

    $message = "Good day $name\r\n \r\n Here is your new password - $pw \r\n\r\n LMS Portal.";

    if(isset($id) && !empty($id) && isset($name) && !empty($name)){
      $query="UPDATE employee SET password = md5('$pw') WHERE emplNum = '$id'";
      $result = array();

      $r = $this->mysqli->query($query) or die(mysqli_error($this->mysqli));

      if($this->mysqli->affected_rows == 1) {

          $result = array('response'=>'done');
          $send_email = $this->sendMail($email, $subject, $message);
          $this->response($this->json($result), 200); //

      }else{
          $result = array('response'=>'not done');
          $this->response($this->json($result), 404);
      }
    }
    $this->response('',204);  // If no records "No Content" status
  }

  private function getReport(){
      if($this->get_request_method() != "GET"){
        $this->response('',406);
      }
      $id = $this->_request['id'];
      if(isset($id) && !empty($id)){

        $query = "SELECT id FROM leaves";

        $r = $this->mysqli->query($query) or die(mysqli_error($this->mysqli));
        if($r->num_rows > 0){
          $n = array();
          $result = array();
          $icon = array('icon'=>'add-circle','showDetails'=>false);

          while ($row = $r->fetch_assoc()){
            $n [] = $row['id'];
          }

          for ($i = 0; $i < count($n); $i++) {

            $q = "select t1.type, t1.usedDays, (t2.available - t1.usedDays) as remainingDays, t3.icon, t3.showDetails
                  from
                  (select l.type, IFNULL(SUM(e.usedDays), 0) as usedDays from employee_leaves as e, leaves as l where e.employee_emplNum = '$id' and e.leaves_id = $n[$i] and e.leaves_id = l.id and e.year = year(curdate())) as t1,

                  (select available from available_days where emplNum = '$id' and leave_id = $n[$i] and year = year(curdate())) as t2,
                  (select * from icons) as t3";

            $res = $this->mysqli->query($q) or die(mysqli_error($this->mysqli));
            if ($res->num_rows > 0){
              while ($rows = $res->fetch_assoc()) {
                $result[] = $rows;
              }
            }
          }
        $this->response($this->json($result), 200);

        }else{
          $this->response($this->json($result), 404);
        }
      }
      $this->response('',204);  // If no records "No Content" status
  }

   private function leaveCancel(){ // function to list leave that can be cancelled
      if($this->get_request_method() != "GET"){
        $this->response('',406);
      }
      $id = $this->_request['id'];
      if(isset($id) && !empty($id)){
          $result = array();

            $q = "SELECT
                  l.startDate,
                  l.endDate,
                  l.numDays,
                  s.type,
                  n.status,
                  n.id
                FROM
                  leaveappl as l,
                  leaves as s,
                  leaveapplstatus as n
                WHERE
                  l.id = n.leaveAppl_id
                AND
                  l.leaves_id = s.id
                AND
                  l.employee_emplNum = '$id'
                AND
                  n.status != 'declined'
                AND
                    n.request_cancel = 'no'
                AND
                  l.startDate > CURDATE() - INTERVAL 1 DAY
                ORDER By
                    l.startDate,
                    n.status DESC";

            $res = $this->mysqli->query($q) or die(mysqli_error($this->mysqli));
            if ($res->num_rows > 0){
              while ($rows = $res->fetch_assoc()) {
                $result[] = $rows;
              }
            

        $this->response($this->json($result), 200);

        }else{
          $this->response($this->json($result), 404);
        }
      }
      $this->response('',204);  // If no records "No Content" status
  }
  
  private function cancelLeave(){ // function to request leave cancellation

    if($this->get_request_method() != "GET"){
      $this->response('',406);
    }
    $id = $this->_request['id'];
    $emplNum = $this->_request['emplNum'];
    $r = $this->_request['reason'];
    
    $result = array();

    if(isset($id) && !empty($id) && isset($emplNum) && !empty($emplNum)){

      $employee = array();
      $manager = array();

      $employee = $this->getUserDetails($emplNum);
      $name = $employee[0];

      $manager = $this->getManagerEmailAddress($emplNum);

      $supervisor = $manager[0];
      $supEmail = $manager[1];
      $subject = "Leave Cancel Request";

      $message = "Good day $supervisor\r\n \r\n You have a leave cancel request from $name.\r\n\r\n Log into http://lms.matityah.co.za/ in order to attend to the request.\r\n \r\n LMS Portal.";


      $query="UPDATE
            		leaveapplstatus
            	SET
            		request_cancel = 'yes',
            		request_cancel_date = NOW(),
                reasons_for_cancel = '$r'
            	WHERE
            	    id = '$id'";

      $r = $this->mysqli->query($query) or die(mysqli_error($this->mysqli));
      if($this->mysqli->affected_rows == 1) {
          $result = array('response'=>'done');
          $send_email = $this->sendMail($supEmail, $subject, $message);
          $this->response($this->json($result), 200); //

      }else{
          $result = array('response'=>'not done');
          $this->response($this->json($result), 404);
      }
    }
    $this->response('',204);  // If no records "No Content" status
  }

  private function leaveRequest(){
      if($this->get_request_method() != "GET"){
        $this->response('',406);
      }
      $id = $this->_request['id'];
      $type = $this->_request['type'];
      $fromDate = $this->_request['fd'];
      $toDate = $this->_request['td'];
      $fhDay = $this->_request['fhDay'];
      $reason = $this->_request['reason'];
      $medCert = $this->_request['medCert'];
    
        
      if(isset($id) && !empty($id) && isset($type) && !empty($type) && isset($fromDate) && !empty($fromDate) && isset($toDate) && !empty($toDate)) {


        $manager = array();
        $result = array();
        $holidays = array();

        $tod = "";
        $holiday = "";

        $nDays = 0;
        $remDays = 0;

        $hours = 9.00;

        $inserted_id;

        $holidays = $this->getSAPublicHolidays();

        $name = $this->getUserDetails($id);

        $name = $name[0];

        $email = "";

        $manager = $this->getManagerEmailAddress($id);

        $supervisor = $manager[0];
        $supEmail = $manager[1];
        $subject = "Leave Application";

        $message = "Good day $supervisor\r\n \r\n You have a leave request from $name.\r\n\r\n Log into http://lms.matityah.co.za/ in order to attend to the request.\r\n \r\n LMS Portal.";


        if ($fhDay == "")
        {
          $fhDay = 0;
        }

        if ($fhDay == 1){
            $tod = "full day";
            $hours_id = 1;
        }

        if ($fhDay == 2){
            $tod = "AM";
            $hours = 4.50;
        }

        if ($fhDay == 3){
            $tod = "PM";
            $hours = 4.50;
        }

        $nDays = $this->getWorkingDays($fromDate,$toDate,$holidays);

        $remDays = $this->getRemaingDays($id, $type);

        if ($nDays > $remDays){
          $result = array('response'=>'Not enough');
          $this->response($this->json($result), 200); //
        }else{
            if($nDays == 0){
                $result = array('response'=>'holiday');
                $this->response($this->json($result), 200); //
            }else{
                  if ($nDays == 1 && $hours == 4.50) {$nDays = 0.50;}
                  if ($nDays > 1) {$hours = $hours * $nDays;}
        
                  $q = "INSERT INTO leaveappl(
                    startDate,
                    endDate,
                    numDays,
                    hours,
                    am_pm,
                    comments,
                    med_cert,
                    dateApplied,
                    leaves_id,
                    employee_emplNum,
                    hours_id)
                    VALUES(
                    '$fromDate',
                    '$toDate',
                    '$nDays',
                    '$hours',
                    '$tod',
                    '$reason',
                    '$medCert',
                     NOW(),
                    '$type',
                    '$id',
                    '$fhDay')";
        
                  $res = $this->mysqli->query($q) or die(mysqli_error($this->mysqli));
                  if ($this->mysqli->affected_rows == 1){
                      $inserted_id = $this->mysqli->insert_id;
                      $q = "INSERT INTO leaveapplstatus(leaveAppl_id) VALUES('$inserted_id')";
                      $r = $this->mysqli->query($q) or die(mysqli_error($this->mysqli));
                      if ($this->mysqli->affected_rows == 1){
                        $result = array('response'=>'done', 'days'=>$nDays);
                        $email = $this->sendMail($supEmail, $subject, $message);
                        $this->response($this->json($result), 200);
                      }else{
                        $result = array('response'=>'not done');
                    $this->response($this->json($result), 404);
                  }
                }
            }
          
        }// end of else
      $this->response('',204);  // If no records "No Content" status
    }
  }

  private function detailedReport(){
    if($this->get_request_method() != "GET"){
        $this->response('',406);
      }
      $id = $this->_request['id'];
      $type = $this->_request['type'];
      $fromDate = $this->_request['fd'];
      $toDate = $this->_request['td'];

      if(isset($id) && !empty($id)){
        if($type == 0){
          $q = "
          select *
          from
          (SELECT
            l.startDate,
            l.endDate,
            p.usedDays,
            p.hours,
            CASE p.am_pm WHEN '' THEN 'N/A' ELSE p.am_pm END as tod,
            s.type
          FROM
            leaveappl as l,
            leaves as s,
            leaveapplstatus as n,
            employee_leaves as p
          WHERE
            l.id = n.leaveAppl_id
          AND
            n.id = p.leaveapplstatus_id
          AND
            l.leaves_id = s.id
          AND
             l.startDate >= '$fromDate'
          AND
            l.startDate <= '$toDate'
          AND
            l.employee_emplNum = '$id') as t1,

            (select * from icons) as t2";

          $r = $this->mysqli->query($q) or die(mysqli_error($this->mysqli));
          $result = array();
          if($r->num_rows > 0) {

            while($row = $r->fetch_assoc()){
                    $result[] = $row;
            }
            $this->response($this->json($result), 200); // send user details
          }
          else{

            $this->response($this->json($result), 404);

          }
        }else{
          $q = "
          select *
          from
          (SELECT
            l.startDate,
            l.endDate,
            p.usedDays,
            p.hours,
            s.type,
            CASE p.am_pm WHEN '' THEN 'N/A' ELSE p.am_pm END as tod
          FROM
            leaveappl as l,
            leaves as s,
            leaveapplstatus as n,
            employee_leaves as p
          WHERE
            l.id = n.leaveAppl_id
          AND
            n.id = p.leaveapplstatus_id
          AND
            l.leaves_id = s.id
          AND
             l.startDate >= '$fromDate'
          AND
            l.startDate <= '$toDate'
          AND
            l.employee_emplNum = '$id'
          AND
            s.id = $type) as t1,

          (select * from icons) as t2";

          $r = $this->mysqli->query($q) or die(mysqli_error($this->mysqli));
          $result = array();
          if($r->num_rows > 0) {

            while($row = $r->fetch_assoc()){
                    $result[] = $row;
            }
            $this->response($this->json($result), 200); // send user details
          }

          else{
            $this->response($this->json($result), 404);
          }
        }


      }
      $this->response('',204);  // If no records "No Content" status
  }

  private function userDetails(){
    if($this->get_request_method() != "GET"){
        $this->response('',406);
      }
      $id = $this->_request['id'];


      if(isset($id) && !empty($id)){
          
        $manager = $this->getManagerEmplNum($id);
        
        $q = "select emplNum, name, jobTitle, email, extension, (SELECT name from employee where emplNum = '$manager') as lineManager from employee where employee.emplNum = '$id' ";

        $res = $this->mysqli->query($q) or die(mysqli_error($this->mysqli));
        if($res->num_rows > 0) {
          $result = array();
          while($row = $res->fetch_assoc()){
                  $result[] = $row;
          }
          $this->response($this->json($result), 200); // send user details
        }

        else{
          $this->response($this->json($result), 404);
        }

      }
      $this->response('',204);  // If no records "No Content" status
  }

  private function leaveTypes(){
    if($this->get_request_method() != "GET"){
        $this->response('',406);
      }

        $q = "SELECT * FROM leaves";

        $res = $this->mysqli->query($q) or die(mysqli_error($this->mysqli));
        if($res->num_rows > 0) {
          $result = array();
          while($row = $res->fetch_assoc()){
                  $result[] = $row;
          }
          $this->response($this->json($result), 200); // send user details
        }

        else{
          $this->response($this->json($result), 404);
        }
  }
  
  private function leaves(){
    if($this->get_request_method() != "GET"){
        $this->response('',406);
      }
        $id = $this->_request['id'];
        
        $q = "SELECT 
            concat(e.name,' - Days: ',l.numDays) as title, 
            concat(l.startDate,'T00:00:00') as startTime, 
            concat(l.endDate,'T23:59:00') as endTime,
            Case when l.am_pm = '' or l.am_pm = 1 then 'true' else 'false' end as allDay

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
            e.reportsTo = '$id'";

        $res = $this->mysqli->query($q) or die(mysqli_error($this->mysqli));
        if($res->num_rows > 0) {
          $result = array();
          while($row = $res->fetch_assoc()){
                  $result[] = $row;
          }
          $this->response($this->json($result), 200); // send user details
        }

        else{
          $this->response($this->json($result), 404);
        }
  }
  
  private function approvalsPending(){
    if($this->get_request_method() != "GET"){
        $this->response('',406);
      }
        $id = $this->_request['id'];
        $result = array();
        
        $q = "SELECT
                s.id,
                l.type, 
                e.name,
                e.emplNum,
                a.startDate, 
                a.endDate, 
                a.numDays, 
                a.dateApplied,
                a.comments, 
                a.med_cert, 
                s.status
            FROM
                leaves as l, 
                employee as e, 
                leaveappl as a, 
                leaveapplstatus as s 
            WHERE
                s.status = 'pending' 
            AND 
                s.leaveAppl_id = a.id 
            AND
                a.leaves_id = l.id 
            AND 
                a.employee_emplNum = e.emplNum 
            AND
                e.reportsTo = '$id'
            AND
                s.request_cancel = 'no'
            ORDER BY
                a.dateApplied";

        $res = $this->mysqli->query($q) or die(mysqli_error($this->mysqli));
        if($res->num_rows > 0) {
          
          while($row = $res->fetch_assoc()){
                  $result[] = $row;
          }
          $this->response($this->json($result), 200); // send user details
        }

        else{
          $this->response($this->json($result), 404);
        }
  }
  
  private function cancelPending(){
    if($this->get_request_method() != "GET"){
        $this->response('',406);
      }
        $id = $this->_request['id'];
        $result = array();
        
        $q = "select 
                l.id, 
                l.status,
                l.reasons_for_cancel as reason, 
                l.request_cancel_date as cancel_date, 
                a.startDate, 
                a.endDate, 
                a.numDays, 
                a.endDate, 
                e.name,
                e.emplNum,
                t.type 
            from 
                leaveapplstatus as l, 
                leaveappl as a, 
                leaves as t, 
                employee as e 
            where 
                a.leaves_id = t.id 
            and 
                l.leaveAppl_id = a.id 
            and 
                a.employee_emplNum = e.emplNum 
            and 
                e.reportsTo = '$id' 
            and     
                l.request_cancel = 'yes' 
            order by 
                l.request_cancel_date";

        $res = $this->mysqli->query($q) or die(mysqli_error($this->mysqli));
        if($res->num_rows > 0) {
          
          while($row = $res->fetch_assoc()){
                  $result[] = $row;
          }
          $this->response($this->json($result), 200); // send user details
        }

        else{
          $this->response($this->json($result), 404);
        }
  }
  
  private function approveLeave(){
    if($this->get_request_method() != "GET"){
        $this->response('',406);
      }
        $id = $this->_request['id'];
        $emplNum = $this->_request['emplNum'];
        $managNum = $this->_request['managNum'];
        $result = array();
        
        $employee = array();
        $manager = array();

        $employee = $this->getUserDetails($emplNum);
        $name = $employee[0];
        $email = $employee[1];

        $manager = $this->getManagerEmailAddress($emplNum);

        $supervisor = $manager[0];
        $subject = "Leave Approved";

        $message = "Good day $name\r\n \r\n Your leave request has been approved by $supervisor.\r\n \r\n LMS Portal.";

        
        $q = "UPDATE leaveapplstatus
                SET
                    status = 'approved', 
                    approved_by = '$managNum',
                    approved_date = NOW(),
                    request_cancel = 'no'
                    
                WHERE
                    id = '$id'";

        $res = $this->mysqli->query($q) or die(mysqli_error($this->mysqli));
        if($this->mysqli->affected_rows == 1) {
            $qr = "INSERT INTO
                        employee_leaves
                   SELECT 
                        l.numDays,
                        l.hours,
                        l.am_pm,
                        YEAR(CURDATE()),
                        l.leaves_id,
                        l.employee_emplNum,
                        s.id
                   FROM
                        leaveappl as l, 
                        leaveapplstatus as s
                   WHERE
                        s.id = '$id'
                   AND
                      s.leaveAppl_id = l.id";
            $rs = $this->mysqli->query($qr) or die(mysqli_error($this->mysqli));
            if($this->mysqli->affected_rows == 1) {
                $result = array('response'=>'done');
                $send_email = $this->sendMail($email, $subject, $message);
                $this->response($this->json($result), 200); //
            }else{
                $result = array('response'=>'not done');
                $this->response($this->json($result), 404);
            }
      }else{
          $result = array('response'=>'not done');
          $this->response($this->json($result), 404);
      }
  }
  
  private function approveCancel(){
    if($this->get_request_method() != "GET"){
        $this->response('',406);
      }
        $id = $this->_request['id'];
        $emplNum = $this->_request['emplNum'];
        $managNum = $this->_request['managNum'];
        $result = array();
        
        $employee = array();
        $manager = array();

        $employee = $this->getUserDetails($emplNum);
        $name = $employee[0];
        $email = $employee[1];

        $manager = $this->getManagerEmailAddress($emplNum);

        $supervisor = $manager[0];
        $subject = "Leave Cancel Approved";

        $message = "Good day $name\r\n \r\n Your leave cancel request has been approved by $supervisor.\r\n \r\n LMS Portal.";

        
        $q = "delete 
        from
            leaveappl
        where
            id = '$id'";

        $res = $this->mysqli->query($q) or die(mysqli_error($this->mysqli));
        if($this->mysqli->affected_rows == 1) {
          $result = array('response'=>'done');
          $send_email = $this->sendMail($email, $subject, $message);
          $this->response($this->json($result), 200); //

      }else{
          $result = array('response'=>'not done');
          $this->response($this->json($result), 404);
      }
  }
  
  private function declineLeave(){
    if($this->get_request_method() != "GET"){
        $this->response('',406);
      }
        $id = $this->_request['id'];
        $emplNum = $this->_request['emplNum'];
        $managNum = $this->_request['managNum'];
        $reasons = $this->_request['reason'];
        $result = array();
        
        $employee = array();
        $manager = array();

        $employee = $this->getUserDetails($emplNum);
        $name = $employee[0];
        $email = $employee[1];

        $manager = $this->getManagerEmailAddress($emplNum);

        $supervisor = $manager[0];
        $subject = "Leave Declined";

        $message = "Good day $name\r\n \r\n Your leave request has been declined by $supervisor.\r\n \r\n LMS Portal.";

        
        $q = "UPDATE leaveapplstatus
                SET
                    status = 'declined', 
                    declined_by = '$managNum' ,
                    declined_date = NOW(),
                    reason_for_decline = '$reason',
                    request_cancel = 'no'
                WHERE
                    id = '$id'";

        $res = $this->mysqli->query($q) or die(mysqli_error($this->mysqli));
        if($this->mysqli->affected_rows == 1) {
          $result = array('response'=>'done');
          $send_email = $this->sendMail($email, $subject, $message);
          $this->response($this->json($result), 200); //

      }else{
          $result = array('response'=>'not done');
          $this->response($this->json($result), 404);
      }
  }
  
  private function declineCancel(){
    if($this->get_request_method() != "GET"){
        $this->response('',406);
      }
        $id = $this->_request['id'];
        $emplNum = $this->_request['emplNum'];
        $managNum = $this->_request['managNum'];
        $reasons = $this->_request['reason'];
        $result = array();
        
        $employee = array();
        $manager = array();

        $employee = $this->getUserDetails($emplNum);
        $name = $employee[0];
        $email = $employee[1];

        $manager = $this->getManagerEmailAddress($emplNum);

        $supervisor = $manager[0];
        $subject = "Leave Declined";

        $message = "Good day $name\r\n \r\n Your leave request has been declined by $supervisor.\r\n \r\n LMS Portal.";

        
        $q = "UPDATE leaveapplstatus
                SET
                    status = 'declined', 
                    declined_by = '$managNum' ,
                    declined_date = NOW(),
                    reason_for_decline = '$reason',
                    request_cancel = 'no'
                WHERE
                    id = '$id'";

        $res = $this->mysqli->query($q) or die(mysqli_error($this->mysqli));
        if($this->mysqli->affected_rows == 1) {
          $result = array('response'=>'done');
          $send_email = $this->sendMail($email, $subject, $message);
          $this->response($this->json($result), 200); //

      }else{
          $result = array('response'=>'not done');
          $this->response($this->json($result), 404);
      }
  }

  private function userManagerDetails(){
    if($this->get_request_method() != "GET"){
        $this->response('',406);
      }

      $id = $this->_request['id'];


      if(isset($id) && !empty($id)){

        $q = "SELECT DISTINCT
              name
            FROM
              employee
            WHERE
              emplNum = (select reportsTo from employee where emplNum = '$id')";

        $res = $this->mysqli->query($q) or die(mysqli_error($this->mysqli));

        if($res->num_rows > 0) {
          $result = array();
          while($row = $res->fetch_assoc()){
                  $result[] = $row;
          }
          $this->response($this->json($result), 200); // send user details
        }

        else{
          $this->response($this->json($result), 404);
        }

      }
      $this->response('',204);  // If no records "No Content" status
  }
    /*
     *  Encode array into JSON
    */
  private function json($data){
    if(is_array($data)){
      return json_encode($data);
    }
  }

  private function generateStrongPassword($length = 9, $add_dashes = false, $available_sets = 'lud')
  {
    $sets = array();
    if(strpos($available_sets, 'l') !== false)
      $sets[] = 'abcdefghjkmnpqrstuvwxyz';
    if(strpos($available_sets, 'u') !== false)
      $sets[] = 'ABCDEFGHJKMNPQRSTUVWXYZ';
    if(strpos($available_sets, 'd') !== false)
      $sets[] = '23456789';
    $all = '';
    $password = '';
    foreach($sets as $set)
    {
      $password .= $set[array_rand(str_split($set))];
      $all .= $set;
    }
    $all = str_split($all);
    for($i = 0; $i < $length - count($sets); $i++)
      $password .= $all[array_rand($all)];
    $password = str_shuffle($password);
    if(!$add_dashes)
      return $password;
    $dash_len = floor(sqrt($length));
    $dash_str = '';
    while(strlen($password) > $dash_len)
    {
      $dash_str .= substr($password, 0, $dash_len) . '-';
      $password = substr($password, $dash_len);
    }
    $dash_str .= $password;
    return $dash_str;
  }

  private function getManagerEmailAddress($id){

    $details = array();

    $q = "select name, email from employee where emplNum = (select reportsTo from employee where emplNum = '$id')";
    $res = $this->mysqli->query($q) or die(mysqli_error($this->mysqli));
    $row = $res->fetch_assoc();
    $details[] = $row['name'];
    $details[] = $row['email'];
    return $details;
  }
  
  private function getManagerEmplNum($id){
    $manEmplNum = '';
    $q = "select emplNum from employee where emplNum = (select reportsTo from employee where emplNum = '$id')";
    $res = $this->mysqli->query($q) or die(mysqli_error($this->mysqli));
    $row = $res->fetch_assoc();
    $manEmplNum = $row['emplNum'];
    return $manEmplNum;
  }

  private function getUserDetails($id){

    $details = array();

    $q = "select name, email from employee where emplNum ='$id'";
    $res = $this->mysqli->query($q) or die(mysqli_error($this->mysqli));
    $row = $res->fetch_assoc();
    $details[] = $row['name'];
    $details[] = $row['email'];
    return $details;
  }

  private function getRemaingDays($id, $leave_id){

    $details = array();
    $ud = 0;
    $av = 0;
    $ad = 0;

    $q = "SELECT
      IFNULL(SUM(usedDays), 0) as usedDays
    FROM
      employee_leaves
    WHERE
      employee_emplNum = '$id'
    AND
      leaves_id = $leave_id
    AND
      year = year(curdate())
    ";
    $res = $this->mysqli->query($q) or die(mysqli_error($this->mysqli));
    $row = $res->fetch_assoc();
    $ud = $row['remainingDays'];

    $q1 = "
      select
        available
      from
        available_days
      where
        emplNum = '$id'
      and
        leave_id = $leave_id
      and
        year = year(curdate())
    ";
    $res1 = $this->mysqli->query($q1) or die(mysqli_error($this->mysqli));
    $row1 = $res1->fetch_assoc();
    $av = $row['available'];

    $ad = $av - $ud;

    $details[] = $ad;
    $details[] = $ud;
    return $details;
  }

  private function decrypt_string($str,$key,$string_is_base64_encoded=true){
    if ($str == '')
      return '';
      if ($string_is_base64_encoded)
          $str = base64_decode($str);
      $td = mcrypt_module_open('blowfish','','ecb','');
      $iv = mcrypt_create_iv(mcrypt_enc_get_iv_size($td), MCRYPT_RAND);
      mcrypt_generic_init($td, $key, $iv);
      $decrypted_data = mdecrypt_generic($td, $str);
      mcrypt_generic_deinit($td);
      mcrypt_module_close($td);

    return $decrypted_data;
  }

  private function getSAPublicHolidays(){
    $y = date("Y");
    $pHolidays = array();
    $json_string = file_get_contents("http://www.kayaposoft.com/enrico/json/v1.0/?action=getPublicHolidaysForYear&year=".$y."&country=zaf");
    //json string to array
    $parsed_arr = json_decode($json_string,true);

    for ($i = 0; $i < count($parsed_arr); $i++){

      $current = $parsed_arr[$i][date][year] . "-" . $parsed_arr[$i][date][month] . "-" . $parsed_arr[$i][date][day] ." ";

      $pHolidays[] = $current;

    }
    return $pHolidays;

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

}

  // Initiiate Library

  $api = new API;
  $api->processApi();
?>
