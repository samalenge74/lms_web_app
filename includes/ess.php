<?php
  if (!isset($_SESSION)) {
        session_start();
        
    }  
    $name = $_SESSION['name'];
?>
<ol id="toc">
    <li><a href="../pages/leaveRequest.php"><span><img src="../images/request.png" height="25px" width="25px" alt="help" title="Request Leave"></span></a></li>
    <li><a href="../pages/leaveHistory.php"><span><img src="../images/history.png" height="25px" width="25px" alt="help" title="Leave History"></span></a></li>
    <li><a href="../pages/summaryReport.php"><span><img src="../images/balance.png" height="25px" width="25px" alt="help" title="Leave Balance"></span></a></li>
    <li><a href="../pages/cancelLeave.php"><span><img src="../images/delete.png" height="25px" width="25px" alt="help" title="Leave Cancellation"></span></a></li>
    <li><a href="../pages/profile.php"><span><img src="../images/settings.png" height="30px" width="30px" alt="help" title="Profile"></span></a></li>
    <li><a href="../pages/ess_home.php"><span><img src="../images/helping.png" height="25px" width="25px" alt="help" title="Help"></span></a></li>
    
</ol>

