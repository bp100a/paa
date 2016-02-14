<?php
require 'utilities.php'; session_start();

$sessionid = $_SESSION['ratingsession'];
$rubricid = $_SESSION['rubricid'];
$userid = $_SESSION['thisuser'];




$option=$_POST['option1'];

switch ($option)
{
	case "rater1": header('Location: rater1_sort.php');
		break;
	case "rater2": header('Location: rater2_sort.php');
		break;
	case "report": header('Location: select_report.php');
		break;
	case "admin": header('Location: admin.php');
		break;

}
?>

