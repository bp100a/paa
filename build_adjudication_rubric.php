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

require_once('rubric_fns.php');


$userid = $_SESSION['thisuser'];
$sessionid = $_SESSION['ratingsession'];

$rubricid = $_SESSION['rubricid'];

if (isset($_POST['adjstudent']))
{
	$studentid = $_POST['adjstudent'];
	$_SESSION['currentstudent'] = $studentid;
}
else
{
	header ('Location: student_info.php');
	exit();
}



/* The rubric value is passed
 * from the Select_Rubric module
 * Here we determine which rubric
 * has been selected and then
 * retrieve the competencies and
 * values for that rubric
 * from the database
 */
?>




<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/htm14/loose.dtd">
<html>

<head>
  <meta http-equiv="Content-Type" content="text/html; charset-iso-8859-1">
  <link rel="stylesheet" type="text/css" href="mystyles.css" />
<title>Adjudicate Student</title>
</head>




<body>
<table border="0" width="95%">
  <tr>
    <td colspan="2" width="95%" valign="bottom" align="left"
     type="text/css">
     <img src="njit_tag.jpg" width="233" height="60" border="0" />
     </td>
  </tr>
</table>

<form method="POST" action="adj_clean_up.php">
<table width="95%" height="50">
<tr>
<td colspan="2" align="center">
<h2>I do not want to adjudicate this student</h2>
</td>
</tr>
<tr>
<td colspan="2" align="center">
<input type="submit" value="GO BACK">
</td>
</tr>
</table>
</form>

<?php

$cxn = connect_to_db();
$user = "root";
$password = "gewbgttl";
$dbase = "mydb";


$cxn = mysqli_connect ($host,$user,$password,$dbase)
		or die ("couldn't connect to database");



/* First we check to make sure that someone else isn't already 
 * adjudicating this student.  If not, then we 
 * increment the rating count for the student selected
 * so we know that the student is being rated.  However, we need
 * a way to make sure the rater doesn't leave this page without
 * rating the student and now that student is marked as rated
 * even though there are no actual scores
 */

$getadjstatus = "SELECT Adjdone FROM studenttoberated
		WHERE studentid = '".$studentid."' AND ratingsessionid = '".$sessionid."'";

$dogetadjstatus = mysqli_query($cxn,$getadjstatus)
		or die(mysqli_error($cxn));
$setadjstatus = mysqli_fetch_row($dogetadjstatus);
$adjstatus = $setadjstatus[0];


if($adjstatus > 0)
{


	echo "<form method=\"POST\" action=\"student_info.php\">";
	echo "<table width=\"95%\" height=\"50\">";
	echo "<tr>";
	echo "<td colspan=\"2\" align=\"center\">";
	echo "<h2>This student is already being adjudicated</h2>";
	echo "</td>";
	echo "</tr>";
	echo "<tr>";
	echo "<td colspan=\"2\" align=\"center\">";
	echo "<input type=\"submit\" value=\"SELECT ANOTHER STUDENT\">";
	echo "</td>";
	echo "</tr>";
	echo "</table>";
	echo "</form>";
}
else

{


/* First we'll update the rating count so someone else doesn't
 * adjudicate the same student.
 */

$getratingcount = "SELECT ratingcount FROM studenttoberated
		WHERE studentid = '".$studentid."'
		AND ratingsessionid = '".$sessionid."'";
$dogetratingcount = mysqli_query($cxn,$getratingcount)
		or die(mysqli_error($cxn));
$setratingcount = mysqli_fetch_row($dogetratingcount);
$ratingcount = $setratingcount[0];




$ratingcount = $ratingcount + 1;

$updatecount = "UPDATE studenttoberated SET ratingcount='".$ratingcount."' 
		WHERE studentid = '".$studentid."' AND ratingsessionid = '".$sessionid."'";
$doupdate = mysqli_query($cxn,$updatecount)
		or die(mysqli_error($cxn));

$setadjdone = "UPDATE studenttoberated SET adjdone = 1
		WHERE studentid = '".$studentid."'
		AND ratingsessionid = '".$sessionid."'";
$dosetadjdone = mysqli_query($cxn,$setadjdone)
		or die(mysqli_error($cxn));


/* In order to build the adjudication rubric,
 * we first need to see if a portfolio rating
 * exists for us to associate with the adjudication
 * session scores.
 */

$getportfoliorating = "SELECT PortfolioRatingID FROM portfoliorating WHERE UserID = '".$userid."'
				AND RatingSessionID = '".$sessionid."' AND StudentID = '".$studentid."'";
$getrating = mysqli_query($cxn,$getportfoliorating);
$pf_exists = mysqli_num_rows($getrating);

if ($pf_exists != 0)
{
	$result = mysqli_fetch_row($getrating);
	$portfolioratingid = $result[0];
	$_SESSION['portfolioratingid'] = $portfolioratingid;

	$setisadj = "UPDATE portfoliorating SET IsAdjudicator = 1
		WHERE portfolioratingid = '".$portfolioratingid."'";
	$dosetisadj = myqli_query($cxn,$setisadj)
		or die(mysqli_error($cxn));

}

else
{
	$createadjrating = "INSERT INTO portfoliorating (RatingSessionID, UserID, StudentID, RatingCompletedTime,
				ISAdjudicator) VALUES ('".$sessionid."', '".$userid."', '".$studentid."',
				NOW(), 1)";
	$docreate = mysqli_query($cxn,$createadjrating)
			or die(mysqli_error($cxn));

	$getportfoliorating = "SELECT PortfolioRatingID FROM portfoliorating WHERE UserID = '".$userid."'
				AND RatingSessionID = '".$sessionid."' AND StudentID = '".$studentid."'";
	$getrating = mysqli_query($cxn,$getportfoliorating);
	$dorating = mysqli_fetch_row($getrating);
	$portfolioratingid = $dorating[0];
	$_SESSION['portfolioratingid'] = $portfolioratingid;

}


/* This MySQL query retrieves the IDs for
 * all the competencies associated with
 * the selected rubric. For each id, the
 * while loop will retrieve the competency
 * information.
 */


$sql="SELECT RubricName FROM rubric WHERE RubricID = '".$rubricid."'";

$info = mysqli_query($cxn,$sql)
	or die ("couldn't execute query");
$row = mysqli_fetch_row($info);
$rubric = $row[0];


echo "<title>".$rubric."</title>";





$sql1="SELECT * FROM student WHERE StudentID = '".$studentid."'";

$result2 = mysqli_query($cxn,$sql1)
		or die ("couldn't execute query");

$row2 = mysqli_fetch_row($result2);

	$njitid = $row2[1];
	$section = $row2[2];
	$first_name = $row2[3];
	$last_name = $row2[4];
	$url = $row2[5];






/* Here we display the appropriate title
 * for the selected rubric
 */


echo "<table border=\"0\" width=\"95%\">";
echo "  <tr>";
echo "    <td width=\"5%\">";
echo "    </td>";
echo "    <td width=\"90%\" align=\"left\" valign=\"center\">";
echo "      <h1>".$rubric."</h1>";
echo "    </td>";
echo "  </tr>";
echo "  <tr>";
echo "    <td width=\"10%\" height=\"20\">";
echo "    </td> ";
echo "    <td width=\"85%\" align=\"center\" valign=\"center\" height=\"20\">";
echo "    </td>";
echo "  </tr>";
echo "</table>";


echo "<table border=\"0\" width=\"95%\">";
echo "  <tr>";
echo "    <td width = \"40%\" align=\"right\" valign=\"center\">";
echo "      Student name:";
echo "    </td>";
echo "    <td width=\"55%\" align=\"left\" valgin=\"center\">";
echo       $first_name." ".$last_name;
echo "    </td>";
echo "  </tr>";
echo "  <tr>";
echo "    <td width = \"40%\" align=\"right\" valign=\"center\">";
echo "      Student NJIT ID:";
echo "    </td>";
echo "    <td width=\"55%\" align=\"left\" valgin=\"center\">";
echo       $njitid;
echo "    </td>";
echo "  </tr>";
echo "  <tr>";
echo "    <td width = \"40%\" align=\"right\" valign=\"center\">";
echo "      Student Section:";
echo "    </td>";
echo "    <td width=\"55%\" align=\"left\" valgin=\"center\">";
echo       $section;
echo "    </td>";
echo "  </tr>";
echo "  <tr>";
echo "    <td width = \"40%\" align=\"right\" valign=\"center\">";
echo "      Student Portfolio URL:";
echo "    </td>";
echo "    <td width=\"55%\" align=\"left\" valign=\"center\">";
echo "    <a href=".$url." target=\"_blank\">".$url."</a>"; 
echo "    </td>";
echo "  </tr>";
echo "</table>";



echo "<table border=\"0\" width=\"95%\">";
echo "  <tr>";
echo "    <td colspan=\"2\" height=\"35\" align=\"center\" valign=\"center\">";
echo "      <p>Please provide a rating for each competency below.</p>";
echo "    </td>";
echo "  </tr>";
echo "</table>";

echo "<form method=\"POST\" action=\"store_adjudications.php\">";


$getthreshold = "SELECT Threshold FROM ratingsession WHERE RatingSessionID = '".$sessionid."'";
$result5 = mysqli_query($cxn,$getthreshold);
$temp5 = mysqli_fetch_row($result5);
$threshold = $temp5[0];

$getscoresets = "SELECT PortfolioRatingID FROM portfoliorating WHERE RatingSessionID = '".$sessionid."'
			AND studentID = '".$studentid."' AND IsAdjudicator = 0";
$result = mysqli_query($cxn,$getscoresets);
$pfstoadj1 = mysqli_fetch_row($result);
$pf1 = $pfstoadj1[0];
$pfstoadj2 = mysqli_fetch_row($result);
$pf2 = $pfstoadj2[0];

$getcomps = "SELECT CompetencyID FROM rubriccontent WHERE RubricID = '".$rubricid."'
		ORDER BY cmporder ASC";
$dogetcomps = mysqli_query($cxn,$getcomps);
$comp_cnt = mysqli_num_rows($dogetcomps);

while ($row = mysqli_fetch_row($dogetcomps))
{
	$compid = $row[0];			

	$getscore1 = "SELECT Score FROM sessionscoring WHERE PortfolioRatingID = '".$pf1."'
				AND CompetencyID = '".$compid."'";
	$dogetscore1 = mysqli_query($cxn,$getscore1);
	$setscore1 = mysqli_fetch_row($dogetscore1);
	$score1 = $setscore1[0];


	$getscore2 = "SELECT Score FROM sessionscoring WHERE PortfolioRatingID = '".$pf2."'
				AND CompetencyID = '".$compid."'";
	$dogetscore2 = mysqli_query($cxn,$getscore2);
	$setscore2 = mysqli_fetch_row($dogetscore2);
	$score2 = $setscore2[0];

	if (ABS($score2 - $score1) <= $threshold)
	{
					

		$getcompinfo = "SELECT * from competency WHERE CompetencyID = '".$compid."'";

		$docompinfo = mysqli_query($cxn,$getcompinfo)
				or die ("could not execute query");

		$competencyinfo = mysqli_fetch_row($docompinfo);
		$competencyname = $competencyinfo[1];
		$competencydescr = $competencyinfo[2];
		$order = $competencyinfo[3];

		echo "<br />";
		echo "<h2 class=\"rubric\">".$competencyname."</h2>";
		echo "<p>This competency does not need to be adjudicated.</p>";
		

	}
	else
	{


		$getcompinfo = "SELECT * from competency WHERE CompetencyID = '".$compid."'";

		$dogetcompinfo = mysqli_query($cxn,$getcompinfo)
				or die ("could not execute query");

		$competencyinfo = mysqli_fetch_row($dogetcompinfo);
		$competencyname = $competencyinfo[1];
		$competencydescr = $competencyinfo[2];
		$order = $competencyinfo[3];

		echo "<br />";
		echo "<h2 class=\"rubric\">".$competencyname."</h2>";
		echo "<p>".$competencydescr."</p>";


				
/* Now we have to retrieve the
 * competency values (possible ratings)
 * for each competencyID.
 */

		$getvalues = "SELECT CompValueID, ExtendedID from competencytovalues 
                                        WHERE CompetencyID = '".$compid."' ORDER BY CompValueId";
		$dogetvalues = mysqli_query($cxn,$getvalues)
				or die ("Could not execute query");

		echo "<table border=\"1\" width=\"90%\">";
		echo "  <tr>";
		while($value = mysqli_fetch_row($dogetvalues))
		{
			extract($value);
			$compvalueid = $value[0];
			$isextended = $value[1];
			$is_null = is_null($isextended);
			$k = $compid;
			$l = strval($k);
		}

/* If the ExtendedID is NULL,
 * we will display the selections
 * from Very Strongly Agree to
 * Very Strongly Disagree. If
 * the ExtendedID is NOT NULL,
 * we will display only the
 * extended text.
 */




		if($is_null)
		{
			$i=1;
			while ($i < 7)
			{
				$gettext = "SELECT CompTextName, CompValue FROM competencyvalue
					WHERE CompValueID = '".$i."'";
				$dogettext = mysqli_query($cxn,$gettext)
					or die ("Could not execute query");

	
				$ratings = mysqli_fetch_row($dogettext);
				$comptext = $ratings[0];
				$compvalue = $ratings[1];
				$j = strval($compvalue);
				$name = "competencyid_".$l;
				$value = "rb".$l."_".$j;

				echo "    <td width=\"15%\" align=\"center\" valign=\"center\">";
				echo "    <input type=\"radio\" name=\"comp[$k]\"											value=\"score($value)\">$comptext";
				echo "    </td>";
				$i++;

			}

			echo "  </tr>";
			echo "</table>";
					

		}

		else

		{


			$getvalueids = "SELECT CompValueID, ExtendedID from competencytovalues 
       	                                  WHERE CompetencyID = '".$compid."' ORDER BY CompValueId";

			$dogetvalueids = mysqli_query($cxn,$getvalueids)
				or die ("Could not execute query");

			echo "<table border=\"1\" width=\"90%\">";
			echo "  <tr>";
			$i=1;

			while($value = mysqli_fetch_row($dogetvalueids))
			{
				extract($value);
				$compvalueid = $value[0];
				$isextended = $value[1];
				$sql5 = "SELECT ExtendedText FROM compextendeddescription
					WHERE CompValueID = '".$compvalueid."' AND 
					ExtendedID = '".$isextended."'";
	
				$result5 = mysqli_query($cxn,$sql5)
					or die ("Could not execute query");
					
				$extended = mysqli_fetch_row($result5);

				$exttext = $extended[0];

				$getextvalue = "SELECT CompValue FROM competencyvalue WHERE
						CompValueID = '".$compvalueid."'";
				$dogetextvalue = mysqli_query($cxn,$getextvalue)
						or die (mysqli_error($cxn));
				$setextvalue = mysqli_fetch_row($dogetextvalue);
				$extvalue = $setextvalue[0];
						
				$j=strval($extvalue);
				$value = "rb".$l."_".$j;
	
				echo "    <td width=\"15%\" align=\"center\" valign=\"center\">";
				echo "    <input type=\"radio\" name=\"comp[$k]\"
						value=\"score($value)\">$exttext";
				echo "    </td>";
				$i++;
			}	


			echo "  </tr>";
			echo "</table>";
					

		}

		echo "<input type=\"HIDDEN\" value=$compid>";
		
				

			
	}

}



echo "<input type=\"submit\" value=\"Submit Scores\">";
echo "</form>";

echo "</body>";
echo "</html>";

}


?>