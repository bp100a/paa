<?php
require_once 'utilities.php'; session_start();

$userid = $_SESSION['thisuser'];
$sessionid = $_SESSION['ratingsession'];
$studentid = $_SESSION['currentstudent'];
$rubricid = $_SESSION['rubricid'];
$portfolioratingid = $_SESSION['portfolioratingid'];



?>

<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/htm14/loose.dtd">
<html>

<head>
  <meta http-equiv="Content-Type" content="text/html; charset-iso-8859-1">
  <link rel="stylesheet" type="text/css" href="mystyles.css" />

<title>Save Adjudicated Scores</title>
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
    <h1>Save Adjudicated Scores</h1>
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

/* Assign our session variables.
 */







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
	$scorevalue = (int)$value[$i];



	$sql= "INSERT INTO sessionscoring (CompetencyID, PortfolioRatingID, Score) 
		VALUES ('$tempcomp', '$portfolioratingid', '$scorevalue')";


	$result = mysqli_query($cxn,$sql);
	if ($result)
	{


		$fillportfoliorating = "UPDATE portfoliorating SET RatingCompletedTime = NOW() WHERE
			PortfolioRatingID = '".$portfolioratingid."'";
		$filltable = mysqli_query($cxn,$fillportfoliorating);
	

	}
	else
	{
		echo "An error has occurred - the score for {$cmp[$i]} was not added.";
		echo "<h4>Error: ".mysqli_error($cxn)."</h4>";
	}
	
	
}

echo "<table border=\"0\" width=\"95%\">";
echo "<tr>";
echo "<td colspan=\"2\" width=\"95%\" align=\"center\">";
echo "Student scores have been saved.";
echo "</td>";
echo "</tr>";

?>

<form method="POST" action="student_info.php">
<tr>
<td colspan="2" width="95%" align="center">
<input type="submit" value="Proceed?">
</td>
</tr>
</table>
</form>
</body>
</html>
