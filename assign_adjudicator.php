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

<title>Show Students Needing Adjudication</title>
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
    <td colspan="2" width="95%" align="center" valign="center" height="50">
    <h1>Show Students Needing Adjudication</h1>
    </td>
  </tr>
</table>

<?php


/* This module lets the admin see
 * which students require adjudication
 * so that they can assign a rater to
 * that student. The system will determine
 * which competencies must be adjudicated
 * and only allow the adjudicator to rate
 * those competencies (in module
 * build_adjudication_rubric).
 * This module will display which raters have
 * already rated the student so that a third
 * rater can be assigned.
 */



/* First we will look for students who
 * have the AdjReqd bit set in their
 * StudentToBeRated table, and then
 * we will check if their AdjDone bit is
 * set.  If not, that means they require
 * adjudication.
 */

$cxn = connect_to_db();

/* Let's first find students who are marked as
 * needing adjudication but who don't have an
 * adjudicator assigned yet
 */



$checkadjreqdbit = "SELECT DISTINCT studenttoberated.StudentID FROM studenttoberated, portfoliorating
			WHERE studenttoberated.RatingSessionID = '".$sessionid."' 
			AND studenttoberated.AdjReqd = 1 
			AND studenttoberated.RatingCount < 3 
			AND studenttoberated.AdjDone = 0";
$result = mysqli_query($cxn,$checkadjreqdbit);


$num_adjudications = mysqli_num_rows($result);

if ($num_adjudications < 1)
{
	echo "<table border=\"0\" width=\"95%\">";
	echo "<tr>";
	echo "<td colspan=\"2\" width=\"95%\" align=\"center\">";
	echo "There are no students who require adjudication at this time.<br />
		Please check back again later.";
	echo "</td>";
	echo "</tr>";
	echo "<tr>";
	echo "<td colspan=\"2\" width=\"95%\" align=\"center\">";
	echo "<form method=\"POST\" action=\"admin.php\">";
	echo "<input type=\"submit\" value\"Return to Administrator Function page\">";
	echo "</td>";
	echo "</tr>";

	echo "</table>";
	echo "</form>";
}
else
{
	echo "<table border=\"1\" width=\"90%\">";
	echo "<form method=\"POST\" action=\"store_adjudicators.php\">";


	echo "<tr>";
	echo "<td class=\"header\" width=\"30%\" align=\"left\">";
	echo "Student to be rated";
	echo "</td>";
	echo "<td class=\"header\" width=\"30%\" align=\"left\">";
	echo "Rater 1";
	echo "</td>";
	echo "<td class=\"header\" width=\"30%\" align=\"left\">";
	echo "Rater 2";
	echo "</td>";
	echo "</tr>";

	$i=0;

	while ($row = mysqli_fetch_row($result))
	{
		$student = $row[0];
		

		/* Now that we know which student requires adjudication,
		 * we need to get the student's name to display to the admin.
		 */

		$getstudentname = "SELECT FirstName, LastName FROM student WHERE StudentID = '".$student."'";
		$dogetname = mysqli_query($cxn,$getstudentname);
		$result1 = mysqli_fetch_row($dogetname);
		$firstname = $result1[0];
		$lastname = $result1[1];

		/* Now we need to get all the users who have already rated
		 * this student and try to remove them from the list.
		 */

		$getstudentraters = "SELECT UserID FROM portfoliorating WHERE (RatingSessionID = '".$sessionid."') AND
			(StudentID = '".$student."')";
		$result2 = mysqli_query($cxn,$getstudentraters);

		$raters = mysqli_fetch_row($result2);
		$rater1 = $raters[0];
		$getrater1name = "SELECT UserName FROM user WHERE UserID = '".$rater1."'";
		$dorater1name = mysqli_query($cxn,$getrater1name);
		$result6 = mysqli_fetch_row($dorater1name);
		$rater1name = $result6[0];	

		$raters = mysqli_fetch_row($result2);
		$rater2 = $raters[0];
		$getrater2name = "SELECT UserName FROM user WHERE UserID = '".$rater2."'";
		$dorater2name = mysqli_query($cxn,$getrater2name);
		$result7 = mysqli_fetch_row($dorater2name);
		$rater2name = $result7[0];







		echo "<tr>";
		echo "<td width=\"30%\" align=\"left\">".$firstname." ".$lastname."</td>";
		echo "<td width=\"30%\" align=\"left\">".$rater1name."</td>";
		echo "<td width=\"30%\" align=\"left\">".$rater2name."</td>";
		echo "</td>";
		echo "</tr>";
		$i++;
	}
	echo "</table>";

	echo "<table border=\"0\" width=\"90%\">";
	echo "<tr>";

	echo "<td colspan=\"2\" width=\"90%\" align=\"center\">";
	echo "Assign expert readers to log into the system using the <br />
		Administrator1 through Administrator6 accounts to<br />
		adjudicate students.";
	echo "</td>";
	echo "</tr>";

	echo "<tr>";

	echo "<td colspan=\"2\" width=\"90%\" align=\"center\">";
	echo "<form method=\"POST\" action=\"admin.php\">";
	echo "<input type=\"submit\" value\"Return to Administrator Function page\">";
	echo "</td>";
	echo "</tr>";

	echo "</table>";
	echo "</form>";

}
?>

</body>
</html>











