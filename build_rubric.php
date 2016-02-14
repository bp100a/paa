<?php
require 'utilities.php'; session_start();

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

<title>Rate a Student</title>
</head>

<body>
<table border="0" width="95%">
<tr>
   <td colspan="2" width="95%" valign="bottom" align="left" type="text/css">
   <img src="njit_tag.jpg" width="233" height="60" border="0" />
   </td>
</tr>
</table>


<form method="POST" action="clean_up.php">
<table width="95%" height="50">
<tr>
<td colspan="2" align="center">
<h2>I do not want to rate this student</h2>
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

/* First we check to make sure that someone else isn't already rating the
 * student a second time.  If not, then we 
 * increment the rating count for the student selected
 * so we know that the student is being rated.  However, we need
 * a way to make sure the rater doesn't leave this page without
 * rating the student and now that student is marked as rated
 * even though there are no actual scores
 */

$getratingcount = "SELECT NumRatings, RatingCount FROM studenttoberated WHERE 
		StudentID = '".$studentid."' AND RatingSessionID = '".$sessionid."'";
$dogetratingcount = mysqli_query($cxn,$getratingcount)
		or die(mysqli_error($cxn));
$setratingcount = mysqli_fetch_row($dogetratingcount);
$numratings = $setratingcount[0];
$ratingcount = $setratingcount[1];


if($ratingcount >= $numratings)
{
	echo "<form method=\"POST\" action=\"student_info.php\">";
	echo "<table width=\"95%\" height=\"50\">";
	echo "<tr>";
	echo "<td colspan=\"2\" align=\"center\">";
	echo "<h2>This student has received the required number of ratings</h2>";
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

	$ratingcount = $ratingcount + 1;


	$updateratingcount = "UPDATE studenttoberated SET RatingCount = '".$ratingcount."' WHERE 
			RatingSessionID = '".$sessionid."' AND StudentID = '".$studentid."'";
	$doupdatecount = mysqli_query($cxn,$updateratingcount)
		or die(mysqli_error($cxn));


/* Here I create a portfolioratingID for the student being rated
 * The portfolioratingID is autoincremented, so by inserting a new record
 * into the table, I can then get the ID to pass to the store_rubric
 * page.  I don't set the rating completed time until the scores are
 * stored, though.
 */

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





/* This MySQL query retrieves the IDs for
 * all the competencies associated with
 * the selected rubric. For each id, the
 * while loop will retrieve the competency
 * information.
 */



	$sql="SELECT RubricName FROM rubric WHERE RubricID = '".$rubricid."'";

	$info = mysqli_query($cxn,$sql)
	or die ("couldn't get rubric name");
	$row = mysqli_fetch_row($info);
	$rubric = $row[0];


	echo "<title>".$rubric."</title>";





	$sql1="SELECT * FROM student WHERE StudentID = '".$studentid."'";

	$result2 = mysqli_query($cxn,$sql1)
		or die ("couldn't get student info");

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
echo "    <td width=\"90%\" align=\"center\" valign=\"center\">";
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
echo "    <td width=\"55%\" align=\"left\" valign=\"center\">";
echo       $first_name." ".$last_name;
echo "    </td>";
echo "  </tr>";
echo "  <tr>";
echo "    <td width = \"40%\" align=\"right\" valign=\"center\">";
echo "      Student NJIT ID:";
echo "    </td>";
echo "    <td width=\"55%\" align=\"left\" valign=\"center\">";
echo       $njitid;
echo "    </td>";
echo "  </tr>";
echo "  <tr>";
echo "    <td width = \"40%\" align=\"right\" valign=\"center\">";
echo "      Student Section:";
echo "    </td>";
echo "    <td width=\"55%\" align=\"left\" valign=\"center\">";
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
echo "      <p>Please provide a rating for each competency in the rubric.</p>";
echo "    </td>";
echo "  </tr>";
echo "</table>";

echo "<form method=\"POST\" action=\"store_ratings.php\">";



/* This MySQL query retrieves the IDs for
 * all the competencies associated with
 * the selected rubric. For each id, the
 * while loop will retrieve the competency
 * information.
 */


	$sql="SELECT CompetencyID FROM rubriccontent WHERE RubricID = '".$rubricid."'
		ORDER BY cmporder ASC";

	$result = mysqli_query($cxn,$sql)
		or die ("couldn't get competency ids");

	$comp_cnt = mysqli_num_rows($result);


	if($result == false)
	{
		echo "<h4>Error: ".mysqli_error($cxn)."</h4>";
	}
	else
	{
		if(mysqli_num_rows($result) < 1)
		{
			echo mysqli_num_rows($result);
		}
		else
		{
			while($row = mysqli_fetch_row($result))
			{
				extract($row);
				$competencyid = $row[0];

/* This MySQL query retrieves the
 * competency information for each
 * competency associated with the
 * rubric.  The competency information
 * includes the competency name, the
 * textual description, and the
 * order in which the competency
 * should be displayed.
 */


				$sql2 = "SELECT * from competency WHERE CompetencyID = '".$competencyid."'";

				$result2 = mysqli_query($cxn,$sql2)
					or die ("Couldn't get competency info");

				$competencyinfo = mysqli_fetch_row($result2);
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

				$sql3 = "SELECT CompValueID, ExtendedID from competencytovalues 
                                         WHERE CompetencyID = '".$competencyid."' ORDER BY CompValueID asc";

				$result3 = mysqli_query($cxn,$sql3)
					or die ("Couldn't get competency scores");


				echo "<table border=\"1\" width=\"90%\">";
				echo "  <tr>";

				while($value = mysqli_fetch_row($result3))
				{
					extract($value);
					$compvalueid = $value[0];
					$isextended = $value[1];
					$is_null = is_null($isextended);
					$k = $competencyid;
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
					while ($i < '7')
					{

						$sql4 = "SELECT CompTextName, CompValue FROM competencyvalue
							WHERE CompValueID = '".$i."'";

						$result4 = mysqli_query($cxn,$sql4)
							or die ("Coudn't get competency text");

	
						$ratings = mysqli_fetch_row($result4);
						$comptext = $ratings[0];
						$compvalue = $ratings[1];
						$j=strval($compvalue);
						$compvalue = strval($compvalue);
						$competencyid = strval($competencyid);
						$name = "competencyid_".$l;
						$value = "rb".$l."_".$j;


						echo "    <td width=\"15%\" align=\"center\" valign=\"center\">";
						echo "    <input type=\"radio\" name=\"comp[$k]\" value=\"score($value)\">$comptext";
						echo "    </td>";
						$i++;

					}

					echo "  </tr>";
					echo "</table>";
					

				}

				else

				{

	
					$sql3 = "SELECT CompValueID, ExtendedID from competencytovalues 
       	                                  WHERE CompetencyID = '".$competencyid."' ORDER BY CompValueId asc";

					$result3 = mysqli_query($cxn,$sql3)
						or die ("Could not get comp extended values");

					echo "<table border=\"1\" width=\"90%\">";
					echo "  <tr>";
					$i=1;

					while($value = mysqli_fetch_row($result3))
					{
						extract($value);
						$compvalueid = $value[0];
						$isextended = $value[1];

						$sql5 = "SELECT ExtendedText FROM compextendeddescription
							WHERE CompValueID = '".$compvalueid."' AND 
							ExtendedID = '".$isextended."'";
	
						$result5 = mysqli_query($cxn,$sql5)
							or die ("Could not get comp extended text");
					
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

				echo "<input type=\"HIDDEN\" value=$competencyid>";
			
				

			
			}

	
		}

	echo "<input type=\"submit\" value=\"Submit Scores\">";
	echo "</form>";

	}

}

?>