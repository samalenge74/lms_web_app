<?php
  if (!isset($_SESSION)) {
        session_start();
        
    }  
    $name = $_SESSION['name'];
?>
<ol id="toc">
    <li><a href="../pages/leaveRequest.php"><span><img src="../images/request.png" height="30px" width="30px" alt="help" title="Request Leave"></span></a></li>
    <li><a href="../pages/leaveHistory.php"><span><img src="../images/history.png" height="30px" width="30px" alt="help" title="Leave History"></span></a></li>
    <li><a href="../pages/summaryReport.php"><span><img src="../images/balance.png" height="30px" width="30px" alt="help" title="Leave Balance"></span></a></li>
    <li><a href="../pages/cancelLeave.php"><span><img src="../images/delete.png" height="30px" width="30px" alt="help" title="Cancel Leave"></span></a></li>
    <li><a href="../pages/pendingDecision.php"><span><img src="../images/action.png" height="30px" width="30px" alt="help" title="Actions"></span></a></li>
    <li><a href="../pages/viewCalendar.php"><span><img src="../images/Calendar.png" height="30px" width="30px" alt="help" title="Leave Calendar"></span></a></li>
    <li><a href="../pages/profile.php"><span><img src="../images/settings.png" height="30px" width="30px" alt="help" title="Profile"></span></a></li>
    <li><a href="../pages/mss_home.php"><span><img src="../images/helping.png" height="30px" width="30px" alt="help" title="Help"></span></a></li>
   
</ol>
