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
</table>



<table border="0" width="95%">
  <tr>
    <td width="95%" colspan="2"align="center">
        <h1>Select An Action</h1>
    </td>
  </tr>
</table>

<?php
echo $sessionid;

$cxn = connect_to_db();

$getrubricinfo = "SELECT RubricID FROM ratingsession WHERE RatingSessionID = '".$sessionid."'";
$dogetrubric = mysqli_query($cxn,$getrubricinfo)
	or die(mysqli_error($cxn));
$setrubricid = mysqli_fetch_row($dogetrubric);
$rubricid = $setrubricid[0];

$_SESSION['rubricid'] = $rubricid;

$tab = "\t";

/* Let's see if the rater got bounced back
 * to here because they didn't select a student to rate.
 */

if ($_SESSION['nostudent'] == 1)
{
	echo "<h2>You did not select a student to rate.<br>
		Please select a student from the drop down list first.</h2>";
	$_SESSION['nostudent'] = 0;
}



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


$getstudentname = "SELECT s.studentid, s.njitstudentid, s.firstname, s.lastname
		FROM student s, studenttoberated stbr
		WHERE stbr.studentid = s.studentid AND
		stbr.ratingsessionid = '".$sessionid."'
		ORDER BY s.lastname ASC";

$dogetname = mysqli_query($cxn,$getstudentname)
		or die(mysqli_error($cxn));

While($setname = mysqli_fetch_row($dogetname))
{
	$studentid = $setname[0];
	$njitid = $setname[1];
	$first = $setname[2];
	$last = $setname[3];	



/* We will get the list of students to be rated
 * but we will not display any students who have
 * already been rated twice.
 */


	$getnumratings = "SELECT NumRatings, RatingCount FROM studenttoberated 
			WHERE RatingSessionID = '".$sessionid."' 
			AND StudentID = '".$studentid."'";
	$dogetcount = mysqli_query($cxn,$getnumratings)
			or die(mysqli_error($cxn));
	$setcount = mysqli_fetch_row($dogetcount);
	$numratings = $setcount[0];
	$ratingcount = $setcount[1];


	if ($ratingcount < $numratings )
	{
		$getrater = "SELECT UserID FROM portfoliorating WHERE RatingSessionID = '".$sessionid."' AND 
				StudentID = '".$studentid."'";
		$dogetrater = mysqli_query($cxn,$getrater)
				or die(mysqli_error($cxn));
		$setrater = mysqli_fetch_row($dogetrater);
		$rater = $setrater[0];

/* For students who have been rated less than their specified number of ratings,
 * before we display their names for selection we will
 * make sure that they have not already been rated by
 * this particular rater.  
 */


		if ($rater != $userid)
		{
		
			echo "<option value=".$studentid.">".$njitid.$tab.$last.", ".$first."</option>\n";
		}
	
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

echo "<form method=\"POST\" action=\"build_adjudication_rubric.php\">";

$getadjlist = "SELECT s.studentid, s.njitstudentid, s.firstname, s.lastname
		FROM student s, studenttoberated stbr
		WHERE stbr.ratingsessionid = '".$sessionid."'
		AND stbr.studentid = s.studentid
		AND stbr.adjreqd = 1
		AND stbr.adjdone = 0
		AND stbr.studentid NOT IN (
			SELECT stbr.studentid
			FROM studenttoberated stbr, portfoliorating p
			WHERE stbr.ratingsessionid = '".$sessionid."'
			AND p.ratingsessionid = stbr.ratingsessionid
			AND stbr.studentid = p.studentid
			AND p.userid = '".$userid."')";




$dogetadjlist = mysqli_query($cxn,$getadjlist)
		or die(mysqli_error($cxn));
$getadjcount = mysqli_num_rows($dogetadjlist);



echo "<tr>";

if ($getadjcount < 1)
{


	echo "<td width=\"45%\" align=\"right\" height=\"50\">";

	echo "There are currently no students requiring adjudication.";
	echo "</td>";
}
else
{

	echo "<td width=\"45%\" align=\"right\" height=\"50\">";
	echo "<select name=\"adjstudent\">";
	echo "<option value=\"NULL\">Select a Student to Adjudicate</option>\n";

	while ($setadjlist = mysqli_fetch_row($dogetadjlist))
	{

		$adjstudentid = $setadjlist[0];		
		$adjnjitida = $setadjlist[1];
		$adjfirstname = $setadjlist[2];
		$adjlastname = $setadjlist[3];

		echo "<option value=".$adjstudentid.">".$adjnjitida.$tab.$adjlastname.", ".$adjfirstname."</option>\n";
	}
	echo "</select>";
	echo "<br />";
	echo "<input type=\"submit\" value=\"Adjudicate Student\">";
	echo "</td>";
}

echo "<td width=\"40%\" align=\"left\" height=\"50\" type=\"text/css\"
	style=\"background-color: #669966; color: white; font-weight: bolder;\">";
echo "Adjudicate a student portfolio that has discrepant scores";
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
	




?>


</body>
</html>
