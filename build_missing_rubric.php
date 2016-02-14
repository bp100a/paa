<?php
require_once 'utilities.php'; session_start();

$rubricid = $_SESSION['rubricid'];

$sessionid = $_SESSION['ratingsession'];
$userid = $_SESSION['thisuser'];
$compids = $_SESSION['comps'];



$cxn = connect_to_db();
$user = "root";
$password = "gewbgttl";
$dbase = "mydb";

$cxn = mysqli_connect ($host,$user,$password,$dbase)
	or die ("couldn't connect to database");


?>

<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/htm14/loose.dtd">
<html>

<head>
  <meta http-equiv="Content-Type" content="text/html; charset-iso-8859-1">
  <link rel="stylesheet" type="text/css" href="mystyles.css" />

<?php

/* This module is called when
 * a rater did not completely
 * fill in a rubric for a student.
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


echo "<Table border=\"0\" width=\"95%\">";
echo "<tr>";
echo "<td colspan=\"2\" width=\"95%\" height=\"50\" align=\"center\">";
echo "<h2>You did not provide a score for each competency.</h2>";
echo "</td>";
echo "</tr>";
echo "<tr>";
echo "<td colspan=\"2\" width=\"95%\" height=\"50\" align=\"center\">";
echo "<h2>You did not provide a score for every competency.<br />
      Please select a score for every competency and resubmit.</h2>";
echo "</td>";
echo "</tr>";
echo "</table>";


$studentid = $_SESSION['currentstudent'];



$getportfoliorating = "SELECT PortfolioRatingID FROM portfoliorating WHERE UserID = '".$userid."'
				AND RatingSessionID = '".$sessionid."' AND StudentID = '".$studentid."'";
$getrating = mysqli_query($cxn,$getportfoliorating)
		or die("Portfolio rating does not exist");
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




/* Now we are going to get the raters scores
 * that they entered previously and we will display
 * them in case the rater wants to make any changes.
 */







function get_scores($item, $key)
{

	global $cmp, $val;
	$pos1 = strrpos($item, "b");
	$pos2 = strrpos($item, "_");
	$pos3 = strrpos($item, ")");
	$cmp[] = substr($item, $pos1+1, $pos2-($pos1+1));
	$val[] = substr($item, $pos2+1, $pos3-($pos2+1));

}

array_walk_recursive($compids, 'get_scores');



$num = count($compids);




echo "<form method=\"POST\" action=\"store_ratings.php\">";

$getcompetencies = "SELECT CompetencyID FROM rubriccontent WHERE RubricID = '".$rubricid."'
		ORDER BY cmporder ASC";
$dogetcomps = mysqli_query($cxn,$getcompetencies)
		or die(mysqli_error($cxn));

$comp_cnt = mysqli_num_rows($dogetcomps);

$i=0;

while ($setcomps = mysqli_fetch_row($dogetcomps))

{
	$competencyid = $setcomps[0];


	if ($competencyid == $cmp[$i])
	{

		$tempcomp = (int)$cmp[$i];
		$scorevalue = (int)$val[$i];



/* This MySQL query retrieves the IDs for
 * all the competencies associated with
 * the selected rubric. For each id, the
 * while loop will retrieve the competency
 * information.
 */



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
                          WHERE CompetencyID = '".$competencyid."' ORDER BY CompValueID";

		$result3 = mysqli_query($cxn,$sql3)
			or die ("Couldn't get extended values");


		echo "<table border=\"1\" width=\"90%\">";
		echo "  <tr>";

		while($tempvalue = mysqli_fetch_row($result3))
		{
			$compvalueid = $tempvalue[0];
			$isextended = $tempvalue[1];
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
			$n=1;
			while ($n < 7)
			{
				$sql4 = "SELECT CompTextName, CompValue FROM competencyvalue
					WHERE CompValueID = '".$n."'";

				$result4 = mysqli_query($cxn,$sql4)
					or die ("Couldn't get score meanings");

	
				$ratings = mysqli_fetch_row($result4);
				$comptext = $ratings[0];
				$compvalue = $ratings[1];
				$j=strval($compvalue);
				$competencyid = strval($competencyid);
				$name = "competencyid_".$l;
				$value = "rb".$l."_".$j;
						
				if ($compvalue == $scorevalue)
				{
					echo "<td width=\"15%\" align=\"center\" valign=\"center\">";
					echo "<input type=\"radio\" name=\"comp[$k]\"	
						value=\"score($value)\" checked=\"checked\">$comptext";
					echo "    </td>";
					$n++;

				}
				else
				{

					echo "<td width=\"15%\" align=\"center\" valign=\"center\">";
					echo "<input type=\"radio\" name=\"comp[$k]\"
						value=\"score($value)\">$comptext";
					echo "    </td>";
					$n++;
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
			$n=1;

			while($tempvalue = mysqli_fetch_row($result3))
			{
				extract($tempvalue);
				$compvalueid = $tempvalue[0];
				$isextended = $tempvalue[1];

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
						
				if ($compvalue == $scorevalue)
				{
					echo "<td width=\"15%\" align=\"center\" valign=\"center\">";
					echo "<input type=\"radio\" name=\"comp[$k]\"
						value=\"score($value)\" checked=\"checked\">$exttext";
					echo "    </td>";
					$n++;

				}
				else
				{
					echo "<td width=\"15%\" align=\"center\" valign=\"center\">";
					echo "<input type=\"radio\" name=\"comp[$k]\"
						value=\"score($value)\">$exttext";
					echo "    </td>";
					$n++;
				}
				
			}	


			echo "  </tr>";
			echo "</table>";
						

		}


	$i++;				

			
	}

	else
	{

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
                          WHERE CompetencyID = '".$competencyid."' ORDER BY CompValueID";

		$result3 = mysqli_query($cxn,$sql3)
			or die ("Couldn't get extended values");


		echo "<table border=\"1\" width=\"90%\">";
		echo "  <tr>";

		while($tempvalue = mysqli_fetch_row($result3))
		{
			$compvalueid = $tempvalue[0];
			$isextended = $tempvalue[1];
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
			$n=1;
			while ($n < 7)
			{
				$sql4 = "SELECT CompTextName, CompValue FROM competencyvalue
					WHERE CompValueID = '".$n."'";

				$result4 = mysqli_query($cxn,$sql4)
					or die ("Couldn't get score meanings");

	
				$ratings = mysqli_fetch_row($result4);
				$comptext = $ratings[0];
				$compvalue = $ratings[1];
				$j=strval($compvalue);
				$competencyid = strval($competencyid);
				$name = "competencyid_".$l;
				$value = "rb".$l."_".$j;
						


				echo "<td width=\"15%\" align=\"center\" valign=\"center\">";
				echo "<input type=\"radio\" name=\"comp[$k]\" value=\"score($value)\">$comptext";
				echo "    </td>";
				$n++;
				


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
			$n=1;

			while($tempvalue = mysqli_fetch_row($result3))
			{
				extract($tempvalue);
				$compvalueid = $tempvalue[0];
				$isextended = $tempvalue[1];

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
						
				
				echo "<td width=\"15%\" align=\"center\" valign=\"center\">";
				echo "<input type=\"radio\" name=\"comp[$k]\"
						value=\"score($value)\">$exttext";
				echo "    </td>";
				$n++;
			
			}	


			echo "  </tr>";
			echo "  </table>";

						

		}

		

			
	}

	
}
echo "<input type=\"submit\" value=\"Submit Scores\">";
echo "</form>";



?>