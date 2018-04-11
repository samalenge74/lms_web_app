<?php
  $name = '';
  if (!isset($_SESSION)) {
        session_start();

    }
    $name = $_SESSION['name'];
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">

        <!-- Always force latest IE rendering engine (even in intranet) & Chrome Frame
        Remove this if you use the .htaccess -->
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

        <title>LMS-Portal</title>
        <meta name="description" content="">
        <meta name="author" content="samalej">

        <meta name="viewport" content="width=device-width; initial-scale=1.0">

        <!-- Replace favicon.ico & apple-touch-icon.png in the root of your domain and delete these references -->
        <link rel="shortcut icon" href="/favicon.ico">
        <link rel="apple-touch-icon" href="/apple-touch-icon.png">

        <link href="../Styles/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href="../bower_components/sweetalert/dist/sweetalert.css" rel="stylesheet" type="text/css" media="all" />
        <link href="../Styles/tabs.css" rel="stylesheet" type="text/css" media="all" />

        <link type="text/css" href="../Styles/tableStyles.css" rel="stylesheet" />
        <link href='../Styles/fullcalendar.css' rel='stylesheet' />
        <link href='../Styles/fullcalendar.print.css' rel='stylesheet' media='print' />
        <link href='../Styles/jquery.dataTables.min.css' rel='stylesheet' />
        <link href="../Styles/jquery-ui-1.10.4.custom.min.css" rel="stylesheet" type="text/css" />
        <link href="../Styles/jquery.printpage.css" rel="stylesheet" type="text/css" media="all" />



    </head>

    <body>
        <div id="main">
            <div class="header-tabs">
                <div id="title">
                <img src="../images/lmsLogo.png" width="100px" height="50px" />
                </div>
                <div id="title_name">
                <?php echo $name; ?>
                </div>
                <div id="log">
                <input type="submit" id="logout" value="Log Out" />
                </div>
            </div>
            <?php
            /*if ($_SESSION['level'] == 1){
                include('../includes/mss.php');
            }else{
                include('../includes/ess.php');
            }*/
            ?>
