<?php
require_once 'utilities.php'; session_start();

$choice=$_POST['choice'];

switch ($choice)
{
	case "student": header('Location: report_by_student.php');
		break;
	case "rater": header('Location: report_by_rater.php');
		break;
	case "variable": header('Location: report_by_variable.php');
		break;
	case "adjudicated": header('Location: report_by_adjudication.php');
		break;

}
?>

