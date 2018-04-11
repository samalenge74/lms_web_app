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
	 <li class='active'><a href='../pages/cancelLeave.php'>Cancel Leave</a></li>
	 <li><a href='../pages/profile.php'>User Profile</a></li>
	 <li class='$mss'><a href='../pages/viewCalendar.php'>Calendar</a></li>
	 <li class='$mss'><a href='../pages/pendingDecision.php'>Pendings</a></li>
	 <li><a href='../pages/ess_home.php'>Info</a></li>
</ul>
</div>";
		echo"<div class='info_c'>";
	    echo "<h2>LMS.</h2>";
	    echo "<p>LMS or Leave Management System enables employees to apply or cancel leaves, display full leaves reports or a summarised version</p>";
	    echo "<p>In addition, it enables promoters to either approve or decline employees requests and to view their teams monthly leaves schedule.</p>";
	    echo "<h3>Things To Know.</h3>";
	    echo "<ol>";
	    echo "<li>When applying for leave:";
	    echo "<ul>";
	    echo "<li>The Full/Half Day, Start Time and End Time fields are applicable when the request is for 1 (one) day only.</li>";
	    echo "<li>The Start time and End time fields are auto-populated based on the selection in the Full/Half Day field.</li>";
	    echo"<li>When the request is for more than 1 (one) day, the Start Time and End Time fields get auto-populated with 00:00:00.</li>";
	    echo"<li>Click the submit button once and wait. The speed of the response from the server depends on the network connection quality. The better the connection, the faster the response from the server and the worst the connection, the slower the response from the server</li>";
	    echo "</ul>";
	    echo "</li>";
	    echo "<li>The Leave Balance is updated/changed only after the request submitted has been approved.</li>";
	    echo "</li>";
	    echo "<li>And also upon successfull cancellation, the Leave Balance is updated/changed.</li>";
	    echo "<li>To cancel leave, click anywhere on the leave's row under the Cancel Leave Page.</li>";
	    echo "</ol>";
	    echo "</div>";
	echo "</div>";
	include ('../includes/footer.php');
?>
