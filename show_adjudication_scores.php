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
<title>View Adjudicated Records</title>


<?php

$cxn = connect_to_db();
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
  <tr>
    <td colspan="2" width="95%" align="center" valign="center">
      <h1>View Records Requiring Adjudication</h1>
    </td>
  </tr>
</table>


<table border="0" width="95%">
  <tr>
    <td colspan="2" width="95%" align="center" valign="bottom" height="150">
    <h2>Results</h2>
    </td>
  </tr>
  <tr>
    <td colspan="2" width="95%" align="center" valign="bottom" height="50">
    </td>
  </tr>
</table>

<form method="POST" action="sort_by_rater.php">

<?php

$getrubricid = "SELECT RubricID FROM ratingsession WHERE RatingDate = CURDATE())";
$rubricid = mysqli_query($cxn,$getrubricid);



echo "<table border=\"1\" width=\"95%\">";
echo "<tr>";
echo "  <td class=\"header\" width=\"15%\" align=\"center\" valign=\"center\">";
echo "Student";
echo "  </td>";
echo "  <td class=\"header\" width=\"15%\" align=\"center\" valign=\"center\">";
echo "Variable";
echo "  </td>";
echo "  <td class=\"header\" width=\"15%\" align=\"center\" valign=\"center\">";
echo "Rater 1";
echo "  </td>";
echo "  <td class=\"header\" width=\"5%\" align=\"center\" valign=\"center\">";
echo "Score";
echo "  </td>";
echo "  <td class=\"header\" width=\"15%\" align=\"center\" valign=\"center\">";
echo "Rater 2";
echo "  </td>";
echo "  <td class=\"header\" width=\"5%\" align=\"center\" valign=\"center\">";
echo "Score";
echo "  </td>";
echo "  <td class=\"header\" width=\"15%\" align=\"center\" valign=\"center\">";
echo "Rater 3";
echo "  </td>";
echo "  <td class=\"header\" width=\"5%\" align=\"center\" valign=\"center\">";
echo "Score";
echo "  </td>";
echo "  <td class=\"header\" width=\"5%\" align=\"center\" valign=\"center\">";
echo "Adj.";
echo "  </td>";
echo "</tr>";

$getadjudicators = "SELECT * FROM portfoliorating WHERE IsAdjudicator = 1 AND RatingSessionID = '".$sessionid."'";
$adjudicators = mysqli_query($cxn,$getadjudicators);

while ($results = mysqli_fetch_row($adjudicators))
{
	$portfolioid = $results[0];
	$ratingsession = $results[1];
	$raterid = $results[2];
	$studentid = $results[3];
	$time = $results[4];
	$isadj = $results[5];

	$getstudentname = "SELECT FirstName, LastName FROM student WHERE StudentID = '".$studentid;
	$dogetstudentname = mysqli_query($cxn,$getstudentname);
	$setstudentname = mysqli_fetch_row($dogetstudentname);
	$first = $setstudentname[0];
	$last = $setstudentname[1];

	echo "<tr>";
	echo "<td width=\"15%\" align=\"center\" valign=\"center\">";
	echo $first." ".$last;
	echo "</td>";


	$getcompids = "SELECT CompetencyID From sessionscoring WHERE PortfolioRatingID = $portfolioid";
	$compids = mysqli_query($cxn,$getcompids);

	echo "<td width=\"15%\" align=\"center\" valign=\"center\">";
	$i=1;

	while ($results2 = mysqli_fetch_row($compids))
	{
		$complist[$i] = $results2;
		$getvariable = "SELECT CompName FROM competency WHERE CompetencyID = $results2";
		$variable = mysqli_query($cxn,$getvariable);
		$name = mysqli_fetch_row($variable);

		echo $variable;
		echo "<br />";
		$i++;
	}
	echo "</td>";
		
	
	$getotherraters = "SELECT UserID, PortfolioRatingID FROM portfoliorating WHERE 
			StudentID = $studentid AND RatingSessionID = $ratingsession";
	$otherraters = mysqli_query($cxn,$getotherraters);

	while ($results3 = mysqli_fetch_row($otherraters))
	{

		echo "<td width=\"15%\" align=\"center\" valign=\"center\">";
		$otherraterid = $results3[0];
		$otherportfolio = $results3[1];

		$getrater = "SELECT UserName FROM user WHERE UserID = $otherraterid";
		$rater = mysqli_query($cxn,$getrater);
		echo "<td width=\"15%\" align=\"center\" valign=\"center\">";		
				
		echo $rater;
		echo "</td>";


		$getcompids = "SELECT Score From sessionscoring WHERE PortfolioRatingID = $otherportfolio";
		$compids = mysqli_query($cxn,$getcompids);

		echo "<td width=\"5%\" align=\"center\" valign=\"center\">";
		while ($results2 = mysqli_fetch_row($compids))
		{
			$getvariable = "SELECT CompName FROM competency WHERE CompetencyID = $results2";
			$variable = mysqli_query($cxn,$getvariable);
			$name = mysqli_fetch_row($variable);

			echo $variable;
			echo "<br />";
		}
		echo "</td>";
	}
	

	echo "<td class=\"header\" width\"5%\" align=\"center\" valign=\"center\">";
	echo "Adj.";
	echo "</td>";
	echo "  </tr>";
}
echo "</table>";



?>

</table>
<form method="POST" action="adjudication_choices.php">
<table border="0" width="95%" cellpadding="5">
 
  <tr>
    <td width="10%" align="center" rowspan="3">
    </td>
    <td width="45%" align="left" type="text/css"
     style="background-color: #669966; color: white; font-weight: bolder;">
     <input type="radio" name="option1" value="rater1">Sort by Rater 1 Names</td>
    <td width="40%">
    </td>
  </tr>
  <tr>
    <td width="10%" align="center" rowspan="3">
    </td>
    <td width="45%" align="left" type="text/css"
     style="background-color: #669966; color: white; font-weight: bolder;">
     <input type="radio" name="option1" value="rater2">Sort by Rater 2 Names</td>
    <td width="40%">
    </td>
  </tr>
  <tr>
    <td width="45%" align="left" type="text/css"
     style="background-color: #669966; color: white; font-weight: bolder;">
     <input type="radio" name="option1" value="report">Select Another Type of Report</td>
    <td width="40%">
    </td>
  </tr>
  <tr>
    <td width="45%" align="left" type="text/css"
     style="background-color: #669966; color: white; font-weight: bolder;">
     <input type="radio" name="option1" value="admin">Return to Main Administrator Page
    </td>
    <td width="40%">
    </td>
  </tr>
 

</table>
<p>
<input type="submit" value="Submit">
</form>
</body>
</html>

 