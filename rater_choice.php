<?php
require 'utilities.php'; session_start();

$selection=$_POST['selection'];

$userid = $_SESSION['thisuser'];
$rubricid = $_SESSION['rubricid'];
$sessionid = $_SESSION['ratingsession'];


switch ($selection)
{
	case "rate": header('Location: student_info.php');
		break;
	case "admin": header('Location: admin.php');
		break;
	case "logout": header('Location: logout.php');
		break;
}

?>
