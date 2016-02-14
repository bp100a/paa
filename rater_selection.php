<?php
require 'utilities.php'; session_start();

$ratertask = $_POST['rater_task'];

$userid = $_SESSION['thisuser'];
$rubricid = $_SESSION['rubricid'];
$sessionid = $_SESSION['ratingsession'];
$studentid = $_SESSION['currentstudent'];

switch ($ratertask)
{

	case "edit":

	

		header('Location: build_rated_rubric.php');
		break;

	
	case "adjudicate":


		header('Location: build_adjudication_rubric.php');
		break;

	case "rate":


		header('Location: build_rubric.php');
		break;

	case "back";
		header('Location: student_info.php');
		break;
}

?>

