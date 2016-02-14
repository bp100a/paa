<?php
require_once 'utilities.php'; session_start();

if (isset($_SESSION['ratingsession']))
{
	$sessionid = $_SESSION['ratingsession'];

}
else
{
	$sessionid = $_POST['session'];

	$_SESSION['ratingsession'] = $sessionid;
}


$userid = $_SESSION['thisuser'];





?>

<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/htm14/loose.dtd">


<html>

<head>
<meta http-equiv="Content-Type" content="text/html; charset-iso-8859-1">
<link rel="stylesheet" type="text/css" href="mystyles.css" />
<title>Select Rater Action</title>
</head>

<body>
<table border="0" width="95%">
  <tr>
    <td colspan="2" width="95%" align="left"
     type="text/css">
     <img src="njit_tag.jpg" width="233" height="60" border="0" />
     </td>
  </tr>
  <tr>
    <td width="95%" colspan="2"align="center">
        <h1>Select An Action</h1>
    </td>
  </tr>
</table>

<?php

/* Before we let the rater select a student
 * we will first check to make sure the rater has
 * not been assigned to adjudicate a student.
 */

$cxn = connect_to_db();
$getrubricinfo = "SELECT RubricID FROM ratingsession WHERE RatingSessionID = '".$sessionid."'";
$dogetrubric = mysqli_query($cxn,$getrubricinfo)
	or die(mysqli_error($cxn));
$setrubricid = mysqli_fetch_row($dogetrubric);
$rubricid = $setrubricid[0];

$_SESSION['rubricid'] = $rubricid;

$tab = "\t";



/* First we'll see what students this rater
 * is marked as an adjudicator for.
 */


$getadjlist = "SELECT portfoliorating.StudentID, studenttoberated.AdjDone FROM
		portfoliorating, studenttoberated WHERE portfoliorating.StudentID = studenttoberated.StudentID
		AND portfoliorating.RatingSessionID = '".$sessionid."'
		AND studenttoberated.RatingSessionID = '".$sessionid."'
		AND portfoliorating.IsAdjudicator=1 AND studenttoberated.AdjReqd=1 
		AND studenttoberated.AdjDone=0 AND portfoliorating.UserID = '".$userid."'";
$dogetadjlist = mysqli_query($cxn,$getadjlist)
		or die(mysql_error());

$num_students = mysqli_num_rows($dogetadjlist);

if ($num_students < 1)
{
	echo "<table border=\"0\" width=\"95%\" cellspacing=\"10\">";
	echo "<tr>";
	echo "<td colspan=\"3\" width=\"95%\" height=\"100\">";
	echo "</td>";
	echo "</tr>";

	echo "<tr>";
	echo "<td width=\"45%\" align=\"right\" height=\"50\">";
	echo "<form method=\"POST\" action=\"build_rubric.php\">";
	echo "<select name=\"student\">";
	echo "<option value=\"NULL\">Select a Student to Rate</option>\n";


/* We will get the list of students to be rated
 * but we will not display any students who have
 * already been rated twice.
 */

	$getstudentlist = "SELECT StudentID FROM studenttoberated WHERE RatingSessionID = '".$sessionid."' AND RatingCount < 2";
	$dogetstudents = mysqli_query($cxn,$getstudentlist)
			or die(mysqli_error($cxn));


	$getstudentlist = "SELECT studenttoberated.StudentID FROM studenttoberated WHERE 
			studenttoberated.ratingsessionid = '".$sessionid."' AND (studenttoberated.StudentID NOT IN 
			(SELECT portfoliorating.StudentID FROM portfoliorating WHERE 
			portfoliorating.ratingsessionid = '".$sessionid."' GROUP BY portfoliorating.StudentID 
			HAVING (COUNT( portfoliorating.StudentID ) >=2)))";
	$dogetstudents = mysqli_query($cxn,$getstudentlist)
			or die(mysqli_error($cxn));



	while ($setstudentlist = mysqli_fetch_row($dogetstudents))
	{

		$studentid = $setstudentlist[0];

		$getrater = "SELECT UserID FROM portfoliorating WHERE RatingSessionID = '".$sessionid."' AND 
			StudentID = '".$studentid."'";
		$dogetrater = mysqli_query($cxn,$getrater)
			or die(mysqli_error($cxn));
		$setrater = mysqli_fetch_row($dogetrater);
		$rater = $setrater[0];

/* For students who have been rated less than twice,
 * before we display their names for selection we will
 * make sure that they have not already been rated by
 * this particular rater.
 */


		if ($rater != $userid)
		{
		
			$getstudentname = "SELECT FirstName, LastName, NJITStudentiD FROM student WHERE 
				StudentID = '".$studentid."'";
			$dogetname = mysqli_query($cxn,$getstudentname)
				or die(mysqli_error($cxn));
			$setname = mysqli_fetch_row($dogetname);
			$first = $setname[0];
			$last = $setname[1];
			$njitid = $setname[2];

			echo "<option value=".$studentid.">".$njitid.$tab.$last.", ".$first."</option>\n";
		}
	}
	echo "</select>";
	echo "<br />";
	echo "<input type=\"submit\" value=\"Rate Student\">";
	echo "</td>";

	echo "<td width=\"40%\" align=\"left\" height=\"50\" type=\"text/css\"
	style=\"background-color: #669966; color: white; font-weight: bolder;\">";
	echo "Select a new student to rate";
	echo "</td>";
	echo "<td width=\"10%\">";
	echo "</td>";
	echo "</tr>";

	echo "</form>";	

	echo "<tr>";


	echo "<form method=\"POST\" action=\"build_rated_rubric.php\">";

	$getratedlist = "SELECT StudentID FROM portfoliorating WHERE RatingSessionID = '".$sessionid."' AND 
			UserID = '".$userid."'";
	$dogetrated = mysqli_query($cxn,$getratedlist)
			or die(mysqli_error($cxn));
	$getnumrated = mysqli_num_rows($dogetrated);

	if ($getnumrated < 1)
	{


		echo "<td width=\"45%\" align=\"right\" height=\"50\">";

		echo "You have not yet rated any students in this rating session.";
		echo "</td>";
	}
	else
	{
	

		echo "<td width=\"45%\" align=\"right\" height=\"50\">";
		echo "<select name=\"ratedstudent\">";
		echo "<option value=\"NULL\">Select a Student to Review</option>\n";

		while ($setratedlist = mysqli_fetch_row($dogetrated))
		{
		
			$ratedstudentid = $setratedlist[0];

			$getratedinfo = "SELECT FirstName, LastName, NJITStudentID FROM student WHERE 
					StudentID = '".$ratedstudentid."'";
			$dogetratedinfo = mysqli_query($cxn,$getratedinfo)
					or die(mysqli_error($cxn));

			$setratedinfo = mysqli_fetch_row($dogetratedinfo);
			$firstname = $setratedinfo[0];
			$lastname = $setratedinfo[1];
			$njitida = $setratedinfo[2];

			echo "<option value=".$ratedstudentid.">".$njitida.$tab.$lastname.", ".$firstname."</option>\n";

		}
		echo "</select>";
		echo "<br />";
		echo "<input type=\"submit\" value=\"Modify Student Scores\">";
		echo "</td>";

	}

	echo "<td width=\"40%\" align=\"left\" height=\"50\" type=\"text/css\"
	style=\"background-color: #669966; color: white; font-weight: bolder;\">";
	echo "Modify the scores you have already given a student";
	echo "</td>";
	echo "<td width=\"10%\">";
	echo "</td>";

	echo "</tr>";
	echo "</form>";	


	$getadminstatus = "SELECT IsAdmin FROM user WHERE UserID = '".$userid."'";
	$dogetadminstatus = mysqli_query($cxn,$getadminstatus)
			or die(mysqli_error($cxn));
	$setadminstatus = mysqli_fetch_row($dogetadminstatus);
	$adminstatus = $setadminstatus[0];

	if ($adminstatus == 1)
	{

		echo "<tr>";

		echo "<td width=\"45%\" align=\"right\" height=\"50\">";
		echo "<form method=\"POST\" action=\"admin.php\">";

		echo "<input type=\"submit\" value=\"Return to Administrator page\">";
		echo "</td>";
		echo "<td width=\"40%\" align=\"left\" height=\"50\" type=\"text/css\"
		style=\"background-color: #669966; color: white; font-weight: bolder;\">";
		echo "Return to the Administrator page";
		echo "</td>";
		echo "<td width=\"10%\">";
		echo "</td>";
		echo "</tr>";
		echo "</form>";		
	}


	echo "<tr>";

	echo "<td width=\"45%\" align=\"right\" height=\"50\">";
	echo "<form method=\"POST\" action=\"logout.php\">";

	echo "<input type=\"submit\" value=\"Logout\">";
	echo "</td>";
	echo "</form>";	
	echo "<td width=\"40%\" align=\"left\" height=\"50\" type=\"text/css\"
	style=\"background-color: #669966; color: white; font-weight: bolder\">";
	echo "Log out of the rating session";
	echo "</td>";
	echo "<td width=\"10%\">";
	echo "</td>";
	echo "</tr>";
	echo "</table>";
	

}
else
{


/* If we have students who require adjudication
 * we will get the first name from the list
 * and adjudicate it.
 */
	
	$studentlist = mysqli_fetch_row($dogetadjlist);
	$student = $studentlist[0];
	$_SESSION['currentstudent'] = $student;

	echo "<table border=\"0\" width=\"95%\">";
	echo "<tr>";
	echo "<td colspan=\"2\" width=\"95%\" align=\"center\" height=\"100\">";
	echo "<h2>You have been asked to adjudicate the following student:</h2>";
	echo "</td>";
	echo "</tr>";

	$getname = "SELECT FirstName, LastName FROM student WHERE StudentID = '".$student."'";
	$dogetname = mysqli_query($cxn,$getname);
	$nameresult = mysqli_fetch_row($dogetname);
	$first = $nameresult[0];
	$last = $nameresult[1];

	echo "<tr>";
	echo "<td colspan=\"2\" width=\"95%\" align=\"center\">";	
	echo $first." ".$last;
	echo "</td>";
	echo "</tr>";


	echo "<form method=\"POST\" action=\"build_adjudication_rubric.php\">";
	echo "<input type=\"hidden\" name=\"adj_student\" value=\"".$student."\">";

	echo "  <tr>";
	echo "    <td colspan=\"3\" width=\"95%\" align=\"center\">";
	echo "    <input type=\"submit\" value=\"Proceed to adjudication\">";
	echo "    </td>";
	echo " </tr>";

	echo "</form>";
	echo "</table>";
}
?>


</body>
</html>
