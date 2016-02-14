<?php
require_once 'utilities.php'; session_start();

$rubricid = $_SESSION['rubricid'];

$sessionid = $_SESSION['ratingsession'];
$userid = $_SESSION['thisuser'];


if (isset($_POST['ratedstudent']))
{
	$studentid = $_POST['ratedstudent'];
	$_SESSION['currentstudent'] = $studentid;
}
else
{
	header ('Location: student_info.php');
	exit();
}






$cxn = connect_to_db();

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/htm14/loose.dtd">
<html>

<head>
  <meta http-equiv="Content-Type" content="text/html; charset-iso-8859-1">
  <link rel="stylesheet" type="text/css" href="mystyles.css" />

<?php

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



/* The rubric value is passed
 * from the Select_Rubric module
 * Here we determine which rubric
 * has been selected and then
 * retrieve the competencies and
 * values for that rubric
 * from the database
 */





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

echo "</head>";

echo "<body>";
echo "<table border=\"0\" width=\"95%\">";
echo "  <tr>";
echo "    <td colspan=\"2\" width=\"95%\" valign=\"bottom\" align=\"left\"
     type=\"text/css\">";
echo "     <img src=\"njit_tag.jpg\" width=\"233\" height=\"60\" border=\"0\">";
echo "     </td>";
echo "  </tr>";
echo "</table>";







$getportfoliorating = "SELECT PortfolioRatingID FROM portfoliorating WHERE UserID = '".$userid."'
				AND RatingSessionID = '".$sessionid."' AND StudentID = '".$studentid."'";
$getrating = mysqli_query($cxn,$getportfoliorating);
$result = mysqli_fetch_row($getrating);
$portfolioratingid = $result[0];
$_SESSION['portfolioratingid'] = $portfolioratingid;





$sql1="SELECT * FROM student WHERE StudentID = '".$studentid."'";

$result2 = mysqli_query($cxn,$sql1)
		or die ("couldn't get student information");

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

/* Now we are going to get the raters scores
 * that they entered previously and we will display
 * them in case the rater wants to make any changes.
 */



echo "<form method=\"POST\" action=\"update_ratings.php\">";



/* This MySQL query retrieves the IDs for
 * all the competencies associated with
 * the selected rubric. For each id, the
 * while loop will retrieve the competency
 * information.
 */


	$sql="SELECT CompetencyID FROM rubriccontent WHERE RubricID = '".$rubricid."'
		ORDER BY cmporder ASC";

	$result = mysqli_query($cxn,$sql)
		or die ("Couldn't get competencyIDs from rubriccontent");

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
                                         WHERE CompetencyID = '".$competencyid."' ORDER BY CompValueId";

				$result3 = mysqli_query($cxn,$sql3)
					or die ("Couldn't get extended values");


				echo "<table border=\"1\" width=\"90%\">";
				echo "  <tr>";

				while($value = mysqli_fetch_row($result3))
				{
					$compvalueid = $value[0];
					$isextended = $value[1];
					$is_null = is_null($isextended);
					$k = $competencyid;
					$l = strval($k);
				}


				$getscore = "SELECT score FROM sessionscoring 
					WHERE PortfolioRatingID = '".$portfolioratingid."'
					AND CompetencyID = '".$competencyid."'";
				$scoreresult = mysqli_query($cxn,$getscore)
					or die("Couldn't get existing scores");
				$scorerow = mysqli_fetch_row($scoreresult);
				$score = $scorerow[0];




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
						$sql4 = "SELECT CompTextName, CompValue FROM competencyvalue
							WHERE CompValueID = '".$i."'";

						$result4 = mysqli_query($cxn,$sql4)
							or die ("Couldn't get score meanings");

	
						$ratings = mysqli_fetch_row($result4);
						$comptext = $ratings[0];
						$compvalue = $ratings[1];
						$j=strval($compvalue);
						$competencyid = strval($competencyid);
						$name = "competencyid_".$l;
						$value = "rb".$l."_".$j;
						
						if ($compvalue == $score)
						{

							echo "<td width=\"15%\" align=\"center\" valign=\"center\">";
							echo "<input type=\"radio\" name=\"comp[$k]\"												value=\"score($value)\" checked=\"checked\">$comptext";
							echo "    </td>";
							$i++;

						}
						else
						{

							echo "<td width=\"15%\" align=\"center\" valign=\"center\">";
							echo "<input type=\"radio\" name=\"comp[$k]\"												value=\"score($value)\">$comptext";
							echo "    </td>";
							$i++;
						}


					}

					echo "  </tr>";
					echo "</table>";
					

				}

				else

				{

	
					$sql3 = "SELECT CompValueID, ExtendedID from competencytovalues 
       	                                  WHERE CompetencyID = '".$competencyid."' ORDER BY CompValueId";

					$result3 = mysqli_query($cxn,$sql3)
						or die ("Couldn't get extended meanings");

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
							or die (mysqli_error($cxn));
					
						$extended = mysqli_fetch_row($result5);

						$getvalue = "SELECT CompValue FROM competencyvalue WHERE 
							CompValueID = '".$compvalueid."'";
						$dogetvalue = mysqli_query($cxn,$getvalue);
						$setvalue = mysqli_fetch_row($dogetvalue);
						$compvalue = $setvalue[0];
	
						$exttext = $extended[0];
						$extValue = $extended[1];
						
						$j=strval($compvalue);
						$value = "rb".$l."_".$j;
						
						if ($compvalue == $score)
						{
							echo "<td width=\"15%\" align=\"center\" valign=\"center\">";
							echo "<input type=\"radio\" name=\"comp[$k]\"
								value=\"score($value)\" checked=\"checked\">$exttext";
							echo "    </td>";

						}
						else
						{
							echo "<td width=\"15%\" align=\"center\" valign=\"center\">";
							echo "<input type=\"radio\" name=\"comp[$k]\"
								value=\"score($value)\">$exttext";
							echo "    </td>";
						}
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


?>