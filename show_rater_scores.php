<?php
require 'utilities.php'; session_start();

$sessionid = $_SESSION['ratingsession'];
$rubricid = $_SESSION['rubricid'];
$userid = $_SESSION['thisuser'];

$raterid = $_POST['rater'];



?>


<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/htm14/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset-iso-8859-1">
<link rel="stylesheet" type="text/css" href="mystyles.css" />
<title>View Rater Scores</title>


<?php

$cxn = connect_to_db();

/* First we will get the rater's name and find it in 
 * the database.  If it's not in the database, we
 * will ask the admin to check the name and try
 * again.
 */


$getraterinfo = "SELECT UserName FROM user WHERE UserID = '".$raterid."'";
$raterinfo = mysqli_query($cxn,$getraterinfo) 
	or die(mysqli_error($cxn));

$row = mysqli_fetch_row($raterinfo);
	
$ratername = $row[0];

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
      <h1>View Rater Scores</h1>
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
    <h3>Scores for <?php echo $ratername;?></h3>
    </td>
  </tr>
</table>



<?php

echo "<table border=\"1\" width=\"100%\">";
echo "<tr>";
echo "  <td class=\"header\" width=\"15%\" align=\"center\" valign=\"center\">";
echo "Student";
echo "  </td>";

/* Now we start to build our table by first
 * retrieving the competencies associated with
 * this rubric to build the table headers.
 * We will also add a header called Adj.
 * to indicate whether the rater was the
 * adjudicator for that particular student.
 */

$getcompetencyids = "SELECT CompetencyID FROM rubriccontent WHERE RubricID = '".$rubricid."'";
$competencyids = mysqli_query($cxn,$getcompetencyids)
	or die(mysql_error());
$numcomps = mysqli_num_rows($competencyids);

$i=0;
while ($setcompname = mysqli_fetch_row($competencyids))
{

	$compid = $setcompname[0];
	$competencies[$i] = $compid;
	$getcompname = "SELECT CompName FROM Competency WHERE CompetencyID = '".$compid."'";
	$dogetcompname = mysqli_query($cxn,$getcompname);
	$setcompname = mysqli_fetch_row($dogetcompname);
	$compname = $setcompname[0];

	echo "<td class=\"header\" width=\"15%\" align=\"center\" valign=\"center\">";
	echo $compname;
	echo "</td>";
	$i++;


}
echo "<td class=\"header\" width\"5%\" align=\"center\" valign=\"center\">";
echo "Adj.";
echo "</td>";
echo "  </tr>";

/* Now we will retrieve the students that
 * were scored by this rater.
 */

$getstudentids = "SELECT StudentID FROM portfoliorating WHERE UserID = '".$raterid."'
		AND RatingSessionID = '".$sessionid."'";
$dogetstudentid = mysqli_query($cxn,$getstudentids)
	or die(mysql_error());


while ($student = mysqli_fetch_row($dogetstudentid))
{


	$id = $student[0];
	$getstudentname = "SELECT FirstName, LastName FROM student WHERE StudentID = '".$id."'";
	$dogetname = mysqli_query($cxn,$getstudentname);
	$nameresults = mysqli_fetch_row($dogetname);
	$first_name = $nameresults[0];
	$last_name = $nameresults[1];

	echo "<tr>";
	echo "  <td width=\"15%\" align=\"center\" valign=\"center\">";
	echo $first_name." ".$last_name;
	echo "  </td>";


	$getpfid = "SELECT portfolioratingid, IsAdjudicator FROM portfoliorating WHERE
		(RatingSessionID = '".$sessionid."' AND UserID = '".$raterid."' AND
		StudentID = '".$id."')";
	$dogetpfid = mysqli_query($cxn,$getpfid)
		or die(mysql_error());
	$returnpfid = mysqli_fetch_row($dogetpfid);
	$pfid = $returnpfid[0];
	$adjbit = $returnpfid[1];



	$getscores = "SELECT CompetencyID, Score FROM sessionscoring WHERE PortfolioRatingID = '".$pfid."'
		ORDER BY CompetencyID ASC";
	$dogetscore = mysqli_query($cxn,$getscores) 
		or die(mysql_error());
	$numscores = mysqli_num_rows($dogetscore);


	$scoreresults = mysqli_fetch_row($dogetscore);
	$compid = $scoreresults[0];
	$score = $scoreresults[1];



	for ($i=0; $i < $numcomps; $i++)
	{


		if ($compid != $competencies[$i])
		{
			echo "<td width=\"15%\" align=\"center\" valign=\"center\">";
			echo "Not Adjudicated";
			echo "</td>";

		}
		else
		{
						
			echo "<td width=\"15%\" align=\"center\" valign=\"center\">";
			echo $score;
			echo "</td>";
			$scoreresults = mysqli_fetch_row($dogetscore);
			$compid = $scoreresults[0];
			$score = $scoreresults[1];			

		}

	}

/* Now we will check to see if this rater
 * was the adjudicator for the student. If
 * so, we will check the Adj. box.
 */




	if ($adjbit == 1)
	{
		echo "<td width=\"5%\">";
		echo "<input type = \"checkbox\" checked>";
		echo "</td>";
	}
	else
	{
		echo "<td width=\"5%\">";
		echo "<input type = \"checkbox\">";
		echo "</td>";
	}		
		
	echo "</tr>";

}





?>

</table>

<table border="0" width="95%" cellpadding="10">
 
  <tr>
    <td colspan="3" width="95%" height="50">
    </td>
  </tr>
  <tr>
    <form method="POST" action="report_by_rater.php">
    <td width="30%" align="right">
    <input type="submit" value="View Another Rater Report">
    </td>
    <td width="55%" align="left" type="text/css"
     style="background-color: #669966; color: white; font-weight: bolder;">
     Select another rater for whom to view scores.
    </td>
    <td width="10%">
    </td>
    </form>
  </tr>
  <tr>
    <form method="POST" action="select_report.php">
    <td width="30%" align="right">
    <input type="submit" value="View A Different Report">
    </td>
    <td width="55%" align="left" type="text/css"
     style="background-color: #669966; color: white; font-weight: bolder;">
    Select a different type of report to view.
    </td>
    <td width="10%">
    </td>
    </form>
  </tr>
  <tr>
    <form method="POST" action="student_info.php">
    <td width="30%" align="right">
    <input type="submit" value="Rate a Student">
    </td>
    <td width="55%" align="left" type="text/css"
     style="background-color: #669966; color: white; font-weight: bolder;">
    Rate a student's portfolio.
    </td>
    <td width="10%">
    </td>
    </form>
  </tr>
  <tr>
    <form method="POST" action="admin.php">
    <td width="30%" align="right">
    <input type="submit" value="Return to Admin Page">
    </td>
    <td width="55%" align="left" type="text/css"
     style="background-color: #669966; color: white; font-weight: bolder;">
     Return to the main Administrator page.
    </td>
    <td width="10%">
    </td>
    </form>
  </tr>

</table>


</form>
</body>
</html>

