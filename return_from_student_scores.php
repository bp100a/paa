<?php
require 'utilities.php'; session_start();

$option=$_POST['option1'];

switch ($option)
{
	case "student": header('Location: report_by_student.php');
		break;
	case "report": header('Location: select_report.php');
		break;
	case "admin": header('Location: admin.php');
		break;

}
?>

