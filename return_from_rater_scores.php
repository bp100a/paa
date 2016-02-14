<?php

require 'utilities.php'; session_start();

$option=$_POST['option1'];

switch ($option)
{
	case "rater": header('Location: report_by_rater.php');
		break;
	case "report": header('Location: select_report.php');
		break;
	case "admin": header('Location: admin.php');
		break;

}
?>