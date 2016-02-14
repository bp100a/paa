<?php
require_once 'utilities.php'; session_start();

$sessionid = $_SESSION['ratingsession'];
$rubricid = $_SESSION['rubricid'];
$userid = $_SESSION['thisuser'];

$studentid = $_POST['student'];


?>

<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/htm14/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset-iso-8859-1">
<link rel="stylesheet" type="text/css" href="mystyles.css" />
<title>View Student Scores</title>


<?php

$cxn = connect_to_db();

/* First we will retrieve the information
 * for the student selected by the admin.
 */

$getstudentinfo = "SELECT * FROM student WHERE StudentID = '".$studentid."'";
$studentinfo = mysqli_query($cxn,$getstudentinfo)
		or die(mysql_error());

$row = mysqli_fetch_row($studentinfo);

	
	$studentid = $row[0];
	$njitid = $row[1];
	$section = $row[2];
	$first_name = $row[3];
	$last_name = $row[4];


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
      <h1>View Student Scores</h1>
    </td>
  </tr>
</table>


<table border="0" width="95%">
  <tr>
    <td colspan="2" width="95%" align="center" valign="center" height="100">
    <h2>Results</h2>
    </td>
  </tr>
  <tr>
    <td colspan="2" width="95%" align="center" valign="center" height="75">
    <h3>Scores for <?php echo $first_name." ".$last_name;?></h3>
    </td>
  </tr>
</table>

<?php


echo "<table border=\"1\" width=\"95%\">";
echo "<tr>";
echo "  <td class=\"header\" width=\"15%\" align=\"center\" valign=\"center\">";
echo "Rater";
echo "  </td>";

/* Now we retrieve the competencies for the current rubric.
 * We get the rubric information from our session variable.
 */




$getcompetencyids = "SELECT CompetencyID FROM rubriccontent WHERE RubricID = '".$rubricid."'";
$competencyids = mysqli_query($cxn,$getcompetencyids);
$numcomps = mysqli_num_rows($competencyids);

/* For each competency in the rubric, we get the name of the
 * competency so we can build the table headers.
 */

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
echo "</tr>";

/* Now we're going to retrieve the IDs of the
 * users who have rated this student during
 * this rating session. We will loop through
 * to retrieve the names of each of the raters.
 */


$getraterids = "SELECT UserID FROM portfoliorating WHERE (StudentID = '".$studentid."' 
		AND RatingSessionID = '".$sessionid."')";
$raterids = mysqli_query($cxn,$getraterids);

while ($rater = mysqli_fetch_row($raterids))
{


	$id = $rater[0];
	$getratername = "SELECT UserName FROM user WHERE UserID = '".$id."'";
	$dogetname = mysqli_query($cxn,$getratername)
		or die(mysql_error());
	$sqlname = mysqli_fetch_row($dogetname);
	$name = $sqlname[0];

	echo "<tr>";
	echo "  <td width=\"15%\" align=\"center\" valign=\"center\">";
	echo $name;
	echo "  </td>";


	$getpfid = "SELECT portfolioratingid FROM portfoliorating WHERE
		(RatingSessionID = '".$sessionid."' AND UserID = '".$id."' AND
		StudentID = '".$studentid."')";
	$dogetpfid = mysqli_query($cxn,$getpfid)
		or die(mysql_error());
	$returnpfid = mysqli_fetch_row($dogetpfid);
	$pfid = $returnpfid[0];

	$getscores = "SELECT CompetencyID, Score FROM sessionscoring WHERE PortfolioRatingID = '".$pfid."'
			ORDER BY CompetencyID ASC";
	$dogetscore = mysqli_query($cxn,$getscores) 
			or die(mysql_error());
	$numscores = mysqli_num_rows($dogetscore);

	$scoreresults = mysqli_fetch_row($dogetscore);
	$compids = $scoreresults[0];
	$score = $scoreresults[1];



	for ($i=0; $i < $numcomps; $i++)
	{


		if ($compids != $competencies[$i])
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
			$compids = $scoreresults[0];
			$score = $scoreresults[1];			

		}

	}
	echo "</tr>";

}

?>

</table>

<table border="0" width="95%" cellpadding="10">
   <tr>
    <td colspan="3" height="15" width="95%">
    </td>
  </tr>
  <tr>
    <form method="POST" action="report_by_student.php">
    <td width="30%" align="right" height="50">
    <input type="submit" value="View Another Student Report">
    </td>
    <td width="55%" align="left" type="text/css"
     style="background-color: #669966; color: white; font-weight: bolder;">
     View a report for a different student
	</td>
    <td width="10%">
    </td>
    </form>
  </tr>
  <tr>
    <form method="POST" action="student_info.php">
    <td width="30%" align="right" height="50">
    <input type="submit" value="Rate a Student">
    </td>
    <td width="55%" align="left" type="text/css"
     style="background-color: #669966; color: white; font-weight: bolder;">
     Rate another student or modify your scores for a rated student
	</td>
    <td width="10%">
    </td>
    </form>
  </tr>
  <tr>
    <form method="POST" action="select_report.php">
    <td width="30%" align="right" height="50">
    <input type="submit" value="View a Different Report">
    </td>
    <td width="55%" align="left" type="text/css"
     style="background-color: #669966; color: white; font-weight: bolder;">
     Select a different type of report to view
    </td>
    <td width="10%">
    </td>
    </form>
  </tr>
  <tr>
    <form method="POST" action="admin.php">
    <td width="30%" align="right" height="50">
    <input type="submit" value="Return to Admin Page">
    </td>
    <td width="55%" align="left" type="text/css"
     style="background-color: #669966; color: white; font-weight: bolder;">
     Return to the main Administrator page
    </td>
    <td width="10%">
    </td>
    </form>
  </tr>
 

</table>


</body>
</html>

