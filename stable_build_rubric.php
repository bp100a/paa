
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

$rubric=$_POST['rubric'];
$rubricid = $_POST['rubricid'];
$competency = $_POST['comp'];
$compvalue = $_POST['score'];


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

<?php
echo "<title>".$rubric."</title>";
?>

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

<?php


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
echo "    <td colspan=\"2\" height=\"35\" align=\"center\" valign=\"center\">";
echo "      Please rate the current student's portfolio in full.<br />
            You will not be allowed to submit your scores until all competencies are rated.";
echo "    </td>";
echo "  </tr>";
echo "</table>";

echo "<form method=\"POST\" action=\"storeratings.php\">";

if (strstr($rubric, 'HUM'))
{
	$rubricid = 1;
}
else 
{	if (strstr($rubric, '352'))
	{
		$rubricid = 2;
	}
	else 
	{	if (strstr($rubric, 'MSPTC'))
		{
			$rubricid = 3;
		}
		else
		{
			echo "Invalid selection";
		}
	}
}
echo "<input type=\"HIDDEN\" name=\"rubricid\" value=$rubricid>";
 
	$cxn = connect_to_db();

/* This MySQL query retrieves the IDs for
 * all the competencies associated with
 * the selected rubric. For each id, the
 * while loop will retrieve the competency
 * information.
 */


	$sql="SELECT CompetencyID FROM rubriccontent WHERE RubricID = $rubricid";

	$result = mysqli_query($cxn,$sql)
		or die ("couldn't execute query");

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


				$sql2 = "SELECT * from competency WHERE CompetencyID = $competencyid";

				$result2 = mysqli_query($cxn,$sql2)
					or die ("could not execute query");

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
                                         WHERE CompetencyID = $competencyid";

				$result3 = mysqli_query($cxn,$sql3)
					or die ("Could not execute query");


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
							WHERE CompValueID = $i";

						$result4 = mysqli_query($cxn,$sql4)
							or die ("Could not execute query");

	
						$ratings = mysqli_fetch_row($result4);
						$comptext = $ratings[0];
						$compValue = $ratings[1];
						$j=strval($i);
						$compvalue = strval($compvalue);
						$competencyid = strval($competencyid);
						$name = "competencyid_".$l;
						echo $name;
						$value = "rb".$l."_".$j;
						echo $value;


						echo "    <td width=\"15%\" align=\"center\" valign=\"center\">";
						echo "    <input type=\"radio\" name=\"comp($name)\"										value=\"score($value)\">$comptext";
						echo "    </td>";
						$i++;

					}

					echo "  </tr>";
					echo "</table>";
					

				}

				else

				{

	
					$sql3 = "SELECT CompValueID, ExtendedID from competencytovalues 
       	                                  WHERE CompetencyID = $competencyid";

					$result3 = mysqli_query($cxn,$sql3)
						or die ("Could not execute query");

					echo "<table border=\"1\" width=\"90%\">";
					echo "  <tr>";
					$i=1;

					while($value = mysqli_fetch_row($result3))
					{
						extract($value);
						$compvalueid = $value[0];
						$isextended = $value[1];

						$sql5 = "SELECT ExtendedText FROM compextendeddescription
							WHERE CompValueID = $compvalueid and ExtendedID = $isextended";
	
						$result5 = mysqli_query($cxn,$sql5)
							or die ("Could not execute query");
					
						$extended = mysqli_fetch_row($result5);
	
						$exttext = $extended[0];
						$extValue = $extended[1];
						
						$j=strval($i);
						$name = "competencyid_".$l;
						echo $name;
						$value = "rb".$l."_".$j;
						echo $value;
	
						echo "    <td width=\"15%\" align=\"center\" valign=\"center\">";
						echo "    <input type=\"radio\" name=\"score($name)\"
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



?>