<?php
require_once 'utilities.php'; session_start();

$sessionid = $_SESSION['ratingsession'];
$rubricid = $_SESSION['rubricid'];
$userid = $_SESSION['thisuser'];




?>

<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/htm14/loose.dtd">


<html>

<head>
<meta http-equiv="Content-Type" content="text/html; charset-iso-8859-1">
<link rel="stylesheet" type="text/css" href="mystyles.css" />
<title>Validate Student Info</title>
</head>

<body>
<table border="0" width="95%">
  <tr>
    <td colspan="2" width="95%" valign="bottom" align="left"
     type="text/css">
     <img src="njit_tag.jpg" width="233" height="60" border="0" />
     </td>
  </tr>
  <tr>
    <td width="95%" colspan="2"align="center">
        <h1>Validate Student Information</h1>
    </td>
  </tr>
</table>


<?php

/* Web-Based Portfolio Assessment
 * Module Validate Student Info
 * This module receives the student
 * information entered by the rater.
 * It validates the information to ensure
 * that the student info has been
 * properly entered, and if the student
 * does not already exist in the database,
 * it creates a record in the student table.
 */

require_once('student_fns.php');



$studentid = $_POST['student'];
$_SESSION['currentstudent'] = $studentid;

$getstudentinfo = "SELECT FirstName, LastName, NJITStudentID FROM student WHERE StudentID = '".$studentid."'";

$dogetname = mysqli_query($cxn,$getstudentname)
	or die(mysqli_error($cxn));
$setname = mysqli_fetch_row($dogetname);
$first = $setname[0];
$last = $setname[1];
$njitid = $setname[2];


/* Connect to the database.
 * Get the current ratingsession
 * by looking for the maximum ID value.
 */

$cxn = connect_to_db();


/* Now we see if the student already exists
 * in the database or if we have to create
 * a new record for the student.
 */

$getstudentid = "SELECT StudentID FROM student WHERE
        NJITstudentID = '".$njit_id."'";

$result1 = mysqli_query($cxn,$getstudentid)
	or die ("couldn't execute query");
$exists = mysqli_num_rows($result1);

/* If the student does not already exist, then we
 * create a record for them in the database.
 */


if ($exists < 1)
{

	$createstudent = "INSERT INTO student (NJITStudentID, Section, FirstName, LastName, CreateTime, 
	LastUpdateTime) VALUES
        ('$njit_id', '$section', '$first_name', '$last_name', CURDATE(), CURDATE())";
	$result4 = mysqli_query($cxn,$createstudent);
	
	if (!$result4)
	{
		echo "Student information could not be written to the database.<br />";
		echo "Please notify the session administrator.";
		echo "<h4>".mysqli_error($cxn)."</h4>";

	}
	else
	{
		$getnewstudentid = "SELECT StudentID FROM student WHERE
			NJITStudentID = '".$njit_id."'";

		$result5 = mysqli_query($cxn,$getnewstudentid);
		$temp5 = mysqli_fetch_row($result5);
		$studentid = $temp5[0];
	}
}
else

/* If the student does exist, we need to
 * retrieve their student id, but we also
 * need to check that the last name matches
 * the name the rater entered.
 * If not, we need to send up a warning.
 */


{
	$temp1 = mysqli_fetch_row($result1);
	$studentid = $temp1[0];

	$checkstudentname = "SELECT LastName FROM Student WHERE StudentID = '".$studentid."'";
	$docheckstudent = mysqli_query($cxn,$checkstudentname);
	$setstudentname = mysqli_fetch_row($docheckstudent);
	$lastname = $setstudentname[0];

	if ($lastname != $last_name)
	{
		echo "<table border=\"0\" width=\"95%\">";
		echo "<tr>";
		echo "<td colspan=\"2\" width=\"95%\" align=\"center\">";
		echo "The last name you entered for the student with an<br />
			NJIT student id of ".$njit_id." does not match<br />
			the last name in the database. Please check your<br />
			student information again.";
		echo "</td>";
		echo "</tr>";
		echo "<tr>";
		echo "<td colspan=\"2\" width=\"95%\" align=\"center\">";
		echo "<form method=\"POST\" action=\"student_info.php\">";
		echo "<input type=\"submit\" value=\"Re-enter Student Information\">";
		echo "</form>";
		echo "</td>";
		echo "</tr>";	
		echo "</table>";
		goto end;

	}

}

$_SESSION['currentstudent'] = $studentid;

/*Check to see if the student is in the
 * studenttoberated table for this rating
 * session. If not, we will insert the student
 * into the studenttoberated table.
 */

$checktoberated = "SELECT StudentID FROM studenttoberated WHERE StudentID = '".$studentid."'
		AND RatingSessionID = '".$sessionid."'";
$result9 = mysqli_query($cxn,$checktoberated)
		or die("Couldn't check if student is in to be rated table");
$intable = mysqli_num_rows($result9);

if ($intable < 1)
{

	$insertstudentid = "INSERT INTO studenttoberated VALUES
		('$studentid', '$sessionid', 0, 0)";
	$result = mysqli_query($cxn,$insertstudentid)
		or die("Could not insert Student info into studenttoberated");
}



$getrubric="SELECT RubricID FROM ratingsession
		WHERE RatingSessionID = '".$sessionid."'";

$result3 = mysqli_query($cxn,$getrubric)
	or die ("couldn't get rubricid");
$temp3 = mysqli_fetch_row($result3);
$rubric = $temp3[0];






/* Now we will check to see if the student
 * has already been rated or not.
 * If the student has been rated, we will check
 * to see if the user is one of the raters for
 * this student.  If so, they will be allowed
 * to edit their ratings.
 */


$getraters = "SELECT UserID FROM portfoliorating WHERE StudentID = '".$studentid."' AND
				RatingSessionID = '".$sessionid."'";
$result = mysqli_query($cxn, $getraters)
			or die ("Couldn't retrieve raters for this student");
$rater_cnt = mysqli_num_rows($result);


echo "<form method=\"POST\" action=\"rater_selection.php\">";

if (($userid == 0) || ($studentid == 0))
{
	echo "Please contact your system administrator - the system has lost its session variables";
	goto end;
}

if ($rater_cnt < 1)
{
	$createpfrating = "INSERT INTO portfoliorating (RatingSessionID, UserID, StudentID, IsAdjudicator)
				VALUES ('$sessionid', '$userid', '$studentid', 0)";
	$docreate = mysqli_query($cxn,$createpfrating)
			or die(mysqli_error($cxn));
	
	$getnewpfid = "SELECT PortfolioRatingID FROM portfoliorating WHERE RatingSessionID = '".$sessionid."'
			AND UserID = '".$userid."' AND StudentID = '".$studentid."'";
	$dogetpfid = mysqli_query($cxn,$getnewpfid);
	$setpfid = mysqli_fetch_row($dogetpfid);
	$portfolioratingid = $setpfid[0];

	$_SESSION['portfolioratingid'] = $portfolioratingid;

	echo "<table border=\"0\" width=\"95%\">";
	echo "<tr>";
	echo "<td colspan=\"2\" width=\"95%\" align=\"center\">";
	echo "The student's information has been validated.<br />
		You may now begin rating this student.";
	echo "</td>";
	echo "</tr>";
	echo "<tr>";
	echo "<td colspan=\"2\" width=\"95%\" align=\"center\">";
	echo "<input type=\"radio\" name=\"rater_task\" value=\"rate\">Begin rating this student";
	echo "</td>";
	echo "</tr>";
	echo "</table>";


}
else
{


	$alreadyrated = 0;


	for ($j = 0; $j < $rater_cnt; $j++)
	{
		$raters = mysqli_fetch_row($result);
		$rater = $raters[0];

		if ($rater != $userid)
		{
			goto loop;

		}
		else
		{
			$alreadyrated = 1;
		}
	loop:
	}
		


	if ($alreadyrated == 0)
	{


		if ($rater_cnt == 3)
		{

			echo "<table border=\"0\" width=\"95%\">";
			echo "<tr>";
			echo "<td colspan=\"2\" width=\"95%\" align=\"center\">";
			echo "This student has been fully rated.<br />
				Because you were not one of the raters<br />
				for this student, you cannot proceed.";
			echo "</td>";
			echo "</tr>";
			echo "<tr>";
			echo "<td colspan=\"2\" width=\"95%\" align=\"center\">";
			echo "<input type=\"radio\" name=\"rater_task\" value=\"back\">Go back and select another
				student to rate";
			echo "</td>";
			echo "</tr>";
			echo "</table>";

		}
		elseif ($rater_cnt == 2)
		{
			echo "<table border=\"0\" width=\"95%\">";
			echo "<tr>";
			echo "<td colspan=\"2\" width=\"95%\" align=\"center\">";
			echo "Have you been assigned to.<br />
				adjudicate this student?";
			echo "</td>";
			echo "</tr>";
			echo "<tr>";
			echo "<td colspan=\"2\" width=\"95%\" align=\"center\">";
			echo "<input type=\"radio\" name=\"rater_task\" value=\"adjudicate\">Yes, proceed to
				adjudication";
			echo "</td>";
			echo "</tr>";
			echo "<tr>";
			echo "<td colspan=\"2\" width=\"95%\" align=\"center\">";
			echo "<input type=\"radio\" name=\"rater_task\" value=\"back\">No, go back and select another
				student to rate";
			echo "</td>";
			echo "</tr>";
			echo "</table>";

	        }
		elseif ($rater_cnt == 1)
		{
			$createpfrating = "INSERT INTO portfoliorating (RatingSessionID, UserID, 
				StudentID, IsAdjudicator)
				VALUES ('$sessionid', '$userid', '$studentid', 0)";
			$docreate = mysqli_query($cxn,$createpfrating);

			$getnewpfid = "SELECT PortfolioRatingID FROM portfoliorating WHERE 
			RatingSessionID = '".$sessionid."'
			AND UserID = '".$userid."' AND StudentID = '".$studentid."'";
			$dogetpfid = mysqli_query($cxn,$getnewpfid);
			$setpfid = mysqli_fetch_row($dogetpfid);
			$portfolioratingid = $setpfid[0];

			$_SESSION['portfolioratingid'] = $portfolioratingid;

			echo "<table border=\"0\" width=\"95%\">";
			echo "<tr>";
			echo "<td colspan=\"2\" width=\"95%\" align=\"center\">";
			echo "The student's information has been validated.<br />
				You may now begin rating this student.";
			echo "</td>";
			echo "</tr>";
			echo "<tr>";
			echo "<td colspan=\"2\" width=\"95%\" align=\"center\">";
			echo "<input type=\"radio\" name=\"rater_task\" value=\"rate\">Rate this student";
			echo "</td>";
			echo "</tr>";
			echo "</table>";
		}

	}
	else
	{

		echo "<table border=\"0\" width=\"95%\">";
		echo "<tr>";
		echo "<td colspan=\"2\" width=\"95%\" align=\"center\">";
		echo "You have already rated this student. <br />Do you want to review your scores?";
		echo "</td>";
		echo "</tr>";
		echo "<tr>";
		echo "<td colspan=\"2\" width=\"95%\" align=\"center\">";
		echo "<input type=\"radio\" name=\"rater_task\" value=\"edit\">Review my scores";
		echo "</td>";
		echo "</tr>";
		echo "</table>";

	}
}

echo "<table border=\"0\" width=\"95%\">";
echo "<tr>";
echo "<td colspan=\"2\" width=\"95%\" align=\"center\">";
echo "<input type=\"submit\" value=\"Submit Selection\">";
echo "</td>";
echo "</tr>";
echo "</table>";


echo "</form>";	

end:


echo "</body>";
echo "</html>";

?>

