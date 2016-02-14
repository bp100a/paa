<?php
require 'utilities.php'; session_start();

$userid = $_SESSION['thisuser'];
$sessionid = $_SESSION['ratingsession'];
$studentid = $_SESSION['currentstudent'];
$rubricid = $_SESSION['rubricid'];
$portfolioratingid = $_SESSION['portfolioratingid'];
$_SESSION['error'] = 0;

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
$_SESSION['comps'] = $compids;

$num = count($value);

$cxn = connect_to_db();

$sql="SELECT CompetencyID FROM rubriccontent WHERE RubricID = '".$rubricid."'";

$result = mysqli_query($cxn,$sql)
	or die ("couldn't get competency ids");

$comp_cnt = mysqli_num_rows($result);

/* If the rater has not provided a rating for every item in the rubric,
 * the following test will send them back and prompt them to
 * provide a score for every item in the rubric
 */


if ($num != $comp_cnt)
{

	header('Location: build_missing_rubric.php');
	exit();	

}


?>

<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/htm14/loose.dtd">
<html>

<head>
  <meta http-equiv="Content-Type" content="text/html; charset-iso-8859-1">
  <link rel="stylesheet" type="text/css" href="mystyles.css" />

<title>Save Student Score</title>
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
    <h1>Save Student Scores</h1>
    </td>
  </tr>
</table>

<?php

/* Web-Based Portfolio Assessment
 * Module Store Ratings
 * This module retrieves the portfolio
 * ratings from the $_POST array and writes
 * the scores to the database.
 * It also tests against the number of ratings
 * in case not every student is to receive two
 * individual ratings.
 */

require_once('rubric_fns.php');

/* Assign our session variables.
 */



$compids = array($_POST['comp']);

$tab = "\t";


for ($i=0; $i < $num; $i++)
{
	$tempcomp = (int)$cmp[$i];
	$scorevalue = (int)$value[$i];


	$sql= "INSERT INTO sessionscoring (CompetencyID, PortfolioRatingID, Score) 
		VALUES ('$tempcomp', '$portfolioratingid', '$scorevalue')";

	$result = mysqli_query($cxn,$sql)
		or die("An error has occurred - the score for ".$cmp[$i]." was not added.");

}



/* We'll check to see how many times this person has been rated
 * by counting how many competencies there are in the rubric, multiplying by two
 * and checking to make sure we have that many scores saved for that person.
 * We'll also check to see if they require adjudication.
 */

$checkratings = "SELECT COUNT(*) FROM rubriccontent
	WHERE rubricid = '".$rubricid."' GROUP BY rubricid";
$dochecknumrat = mysqli_query($cxn,$checkratings) or die(mysqli_error($cxn));
$numcomps = mysqli_fetch_row($dochecknumrat);
$expectedratings = $numcomps[0]*2;

$countscores = "SELECT COUNT(*) FROM sessionscoring, portfoliorating
	WHERE sessionscoring.portfolioratingid = portfoliorating.portfolioratingid
	AND portfoliorating.ratingsessionid = '".$sessionid."'
	AND portfoliorating.studentid = '".$studentid."'
	AND portfoliorating.IsAdjudicator = 0";
$getcount = mysqli_query($cxn,$countscores) or die(mysqli_error($cxn));
$fetchcount = mysqli_fetch_row($getcount);
$scorecount = $fetchcount[0];





/* If we've saved two sets of scores for the student,
 * we need to test to see if they require
 * adjudication.
 */

if ($scorecount == $expectedratings)
{

	$getthreshold = "SELECT Threshold FROM ratingsession WHERE RatingSessionID = '".$sessionid."'";
	$result5 = mysqli_query($cxn,$getthreshold);
	$temp5 = mysqli_fetch_row($result5);
	$threshold = $temp5[0];

	$getpfs = "SELECT portfolioratingid FROM portfoliorating WHERE RatingSessionID = '".$sessionid."'
		AND StudentID = '".$studentid."'
		AND IsAdjudicator = 0";
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
	$fillportfoliorating = "UPDATE portfoliorating SET RatingCompletedTime = NOW() WHERE
		PortfolioRatingID = '".$portfolioratingid."'";
	$filltable = mysqli_query($cxn,$fillportfoliorating);








echo "<table border=\"0\" width=\"95%\">";
echo "<tr>";
echo "<td colspan=\"2\" width=\"95%\" align=\"center\" height=\"100\">";
echo "Student scores have been saved.";
echo "</td>";
echo "</tr>";




echo "<form method=\"POST\" action=\"student_info.php\">";
echo "<tr>";
echo "<td colspan=\"2\" width=\"95%\" align=\"center\" height=\"100\">";
echo "<input type=\"submit\" value=\"Go to Rater Page to Select Next Action\">";
echo "</td>";
echo "</form>";
echo "</tr>";
echo "</table>";


?>



</body>
</html>
