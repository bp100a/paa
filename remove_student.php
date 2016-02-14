<?php
require_once 'utilities.php'; session_start();

/* Web-Based Portfolio Assessment
 * This module builds the rubric
 * as selected by the Administrator
 * for the rating session.
 * This rubric is then presented
 * to the rater each time she
 * rates a student.
 * Only one rubric can be selected
 * per rating session
 */

try
{
	if (($_POST['student'] < 1) || ($_POST['student'] === NULL))
	{
		throw new Exception("No student selected.");
	}
}

catch (Exception $e)
{
	
	$_SESSION['nostudent'] = 1;
	header('Location: student_info.php');
}




$studentid = $_POST['student'];
$_SESSION['currentstudent'] = $studentid;	

require_once('rubric_fns.php');

$cxn = connect_to_db();

$sessionid = $_SESSION['ratingsession'];
$rubricid = $_SESSION['rubricid'];
$userid = $_SESSION['thisuser'];
$_SESSION['currentstudent'] = $studentid;
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/htm14/loose.dtd">
<html>

<head>
  <meta http-equiv="Content-Type" content="text/html; charset-iso-8859-1">
  <link rel="stylesheet" type="text/css" href="mystyles.css" />

<title>Student Removed</title>
</head>

<body>
<table border="0" width="95%">
<tr>
   <td colspan="2" width="95%" valign="bottom" align="left" type="text/css">
   <img src="njit_tag.jpg" width="233" height="60" border="0" />
   </td>
</tr>
</table>



<?php

/* First we check to make sure that this student has not
 * already been rated.  If so, we need to clean up
 * any session scores and portfoliorating ids
 */

	$getscores = "SELECT DISTINCT s.portfolioratingid, COUNT(*), p.isadjudicator
			 FROM sessionscoring s
			LEFT JOIN portfoliorating p ON s.portfolioratingid = p.portfolioratingid
			WHERE s.portfolioratingid IN (
			SELECT portfolioratingid FROM portfoliorating
			WHERE studentid = '".$studentid."'
			AND ratingsessionid = '".$sessionid."')
			GROUP BY s.portfolioratingid ORDER BY p.isadjudicator ASC";
	$dogetscores = mysqli_query($cxn,$getscores)
			or die ("Could not retrieve portfoliorating info.");
	$numpfids = mysqli_num_rows($dogetscores);

while($scoreinfo = mysqli_fetch_row($dogetscores))
{

	$ratingid = $scoreinfo[0];
	$ratingcnt = $scoreinfo[1];
	$ratingadj = $scoreinfo[2];

	$deletescores = "DELETE FROM sessionscoring
			WHERE portfolioratingid = '".$ratingid."'";
	$dodeletescores = mysqli_query($cxn,$deletescores)
			or die ("Could not delete score");
}

$getpf_ids = "SELECT portfolioratingid FROM portfoliorating
		WHERE studentid = '".$studentid."'
		AND ratingsessionid = '".$sessionid."'";
$dogetpf_ids = mysqli_query($cxn,$getpf_ids)
		or die ("Could not get portfolioratings");

while($pf_idinfo = mysqli_fetch_row($dogetpf_ids))
{
	$pfid_old = $pf_idinfo[0];


	$deletepfrating = "DELETE FROM portfoliorating
			WHERE portfolioratingid = '".$pfid_old."'";
	$dodeletepfrating = mysqli_query($cxn,$deletepfrating)
			or die ("could not delete portfolio rating");

}

$deletestudent = "DELETE FROM studenttoberated
			WHERE studentid = '".$studentid."'
			AND ratingsessionid = '".$sessionid."'";
$dodeletestudent = mysqli_query($cxn,$deletestudent)
			or die ("Could not remove student from list of those to be rated.");
	
echo "<table width=\"95%\" height = \"50\">";
echo "<tr>";
echo "<td colspan=\"2\" align=\"center\">";echo "<form method=\"POST\" action=\"admin.php\">";
echo "<input type=\"submit\" value=\"Go back to the admin page\">";
echo "</td>";
echo "</form>";	
echo "</tr>";
echo "</table>";

?>