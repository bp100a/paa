<?php
require 'utilities.php'; session_start();

$selection=$_POST['selection'];



switch ($selection)
{
	case "begin": header('Location: begin_assessment.php');
		break;
	case "reports": header('Location: select_report.php');
		break;
	case "adjudicate": header('Location: assign_adjudicator.php');
		break;
	case "ratings": header('Location: find_incomplete_ratings.php');
		break;
	case "rate": header('Location: student_info.php');
		break;
	case "export": header('Location: create_scores_table.php');
		break;
	case "logout": header('Location: logout.php');
		break;
}
?>

