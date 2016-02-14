<?php
require_once 'utilities.php'; session_start();

$option=$_POST['option1'];

switch ($option)
{
	case "report": header('Location: select_report.php');
		break;
	case "ratings": header('Location: find_incomplete_ratings.php');
		break;
	case "admin": header('Location: admin.php');
		break;

}
?>
