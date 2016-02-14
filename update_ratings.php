<?php
require 'utilities.php'; session_start();

$portfolioratingid = $_SESSION['portfolioratingid'];
$rubricid = $_SESSION['rubricid'];
$studentid = $_SESSION['currentstudent'];
$sessionid = $_SESSION['ratingsession'];
$userid = $_SESSION['thisuser'];



?>

<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/htm14/loose.dtd">
<html>

<head>
  <meta http-equiv="Content-Type" content="text/html; charset-iso-8859-1">
  <link rel="stylesheet" type="text/css" href="mystyles.css" />

<title>Update Student Score</title>
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
    <h1>Update Student Scores</h1>
    </td>
  </tr>
</table>

<?php

/* Web-Based Portfolio Assessment
 * Module Store Ratings
 * This module retrieves the portfolio
 * ratings from the $_POST array and writes
 * the scores to the database.
 */

require_once('rubric_fns.php');



$compids = array($_POST['comp']);



function get_scores($item, $key)
{

	global $cmp, $value;
	$pos1 = strrpos($item, "b");
	$pos2 = strrpos($item, "_");
	$pos3 = strrpos($item, ")");
	$cmp[] = substr($item, $pos1+1, $pos2-($pos1+1));
	$value[] = substr($item, $pos2+1, $pos3-($pos2+1));

}

array_walk_recursive($compids, 'get_scores');


$num = count($value);

$cxn = connect_to_db();

for ($i=0; $i < $num; $i++)
{
	$tempcomp = (int)$cmp[$i];
	$tempval = (int)$value[$i];


	$sql= "UPDATE sessionscoring SET Score = '".$tempval."' WHERE PortfolioRatingID = '".$portfolioratingid."' 
		AND CompetencyID = '".$tempcomp."'"; 


	$result = mysqli_query($cxn,$sql);

	$fillportfoliorating = "UPDATE portfoliorating SET RatingCompletedTime = NOW() WHERE
			PortfolioRatingID = '".$portfolioratingid."'";
	$filltable = mysqli_query($cxn,$fillportfoliorating);
	


	if (!$result)
	{
		echo "An error has occurred - the score for {$cmp[$i]} was not added.";
		echo "<h4>Error: ".mysqli_error($cxn)."</h4>";
	}
	
	
}

/* Here we'll check to see if the changes made to the scores have
 * affected the need for adjudication
 */

$checkratings = "SELECT NumRatings, RatingCount FROM studenttoberated WHERE RatingSessionID = '".$sessionid."'
		AND StudentID = '".$studentid."'";
$docheck = mysqli_query($cxn,$checkratings);
$ratingcheck = mysqli_fetch_row($docheck);
$numratings = $ratingcheck[0];
$ratingcount = $ratingcheck[1];

/* If the student has been rated twice,
 * we need to test to see if they require
 * adjudication.
 */

if ($ratingcount == 2)
{

	$resetadj = "UPDATE studenttoberated SET AdjReqd = 0
			WHERE StudentID = '".$studentid."' AND RatingSessionID = '".$sessionid."'";
	$doreset = mysqli_query($cxn,$resetadj);

	$getthreshold = "SELECT Threshold FROM ratingsession WHERE RatingSessionID = '".$sessionid."'";
	$result5 = mysqli_query($cxn,$getthreshold);
	$temp5 = mysqli_fetch_row($result5);
	$threshold = $temp5[0];

	$getpfs = "SELECT portfolioratingid FROM portfoliorating WHERE RatingSessionID = '".$sessionid."'
		AND StudentID = '".$studentid."'";
	$dogetpfs = mysqli_query($cxn,$getpfs)
		or die(mysqli_error($cxn));
	 
		
	$pfstoadj = mysqli_fetch_row($dogetpfs);
	$pf1 = $pfstoadj[0];
	$pfstoadj = mysqli_fetch_row($dogetpfs);
	$pf2 = $pfstoadj[0];

	$getcompids = "SELECT CompetencyID FROM rubriccontent WHERE RubricID = '".$rubricid."'
		ORDER BY cmporder ASC";
	$result2 = mysqli_query($cxn,$getcompids);
	$comp_cnt = mysqli_num_rows($result2);
		
	while ($row = mysqli_fetch_row($result2))
	{
		$compid = $row[0];			

		$getscore1 = "SELECT Score FROM sessionscoring WHERE PortfolioRatingID = '".$pf1."'
				AND CompetencyID = '".$compid."'";
		$result3 = mysqli_query($cxn,$getscore1);
		$temp3 = mysqli_fetch_row($result3);
		$score1 = $temp3[0];


		$getscore2 = "SELECT Score FROM sessionscoring WHERE PortfolioRatingID = '".$pf2."'
			AND CompetencyID = '".$compid."'";
		$result4 = mysqli_query($cxn,$getscore2);
		$temp4 = mysqli_fetch_row($result4);
		$score2 = $temp4[0];


		if (ABS($score2 - $score1) > $threshold)
		{
			$markforadj = "UPDATE studenttoberated SET AdjReqd = 1 WHERE 
				StudentID ='".$studentid."' AND RatingSessionID = '".$sessionid."'";
			$setmark = mysqli_query($cxn,$markforadj);
		}
	


	}
}


?>

<form method="POST" action="student_info.php">
<table border="0" width="95%">
<tr>
<td colspan="2" width="95%" align="center" height=\"100\">
Your scores for this student have been updated.
</td>
</tr>
<tr>
<td colspan="2" width="95%" align="center" height=\"100\">
<input type="submit" value="Go to Rater Page to Select Next Action">
</td>
</tr>
</table>
</form>
</body>
</html>
