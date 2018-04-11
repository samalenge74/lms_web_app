<?php
if (!isset($_SESSION)) {
        session_start();
    }
$mss = '';
if ($_SESSION['level'] == 1){
    $mss = '';
}else{
    $mss = 'mss_off';
}
include ('../includes/header.php');
echo "<div class=\"content-tabs\" >";
echo "<div id='cssmenu'>
<ul>
   <li><a href='../pages/leaveRequest.php'>Request Leave</a></li>
   <li><a href='../pages/summaryReport.php'>Leave Balance</a></li>
   <li><a href='../pages/leaveHistory.php'>Leave History</a></li>
   <li><a href='../pages/cancelLeave.php'>Cancel Leave</a></li>
   <li><a href='../pages/profile.php'>User Profile</a></li>
   <li class='$mss active'><a href='../pages/viewCalendar.php'>Calendar</a></li>
   <li class='$mss'><a href='../pages/pendingDecision.php'>Pendings</a></li>
   <li><a href='../pages/ess_home.php'>Info</a></li>
</ul>
</div>";
echo "<div class='table_c'>";
echo "<div id='loading' >loading...</div>";
echo "<div id='calendar'></div>";
echo "</div>";
echo "</div>";
include ('../includes/footer.php');
?>
