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
<title>View Records that Required Adjudication</title>

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
      <h1>View Records that Required Adjudication</h1>
    </td>
  </tr>
</table>






<?php

echo "<table border=\"1\" width=\"95%\">";


	echo "<tr>";
	echo "<td class=\"header\" width=\"20%\" align=\"left\">";
	echo "Student";
	echo "</td>";
	echo "<td class=\"header\" width=\"20%\" align=\"left\">";
	echo "Rater 1";
	echo "</td>";
	echo "<td class=\"header\" width=\"20%\" align=\"left\">";
	echo "Rater 2";
	echo "</td>";
	echo "<td class=\"header\" width=\"20%\" align=\"left\">";
	echo "Assigned Adjudicator";
	echo "</td>";
	echo "<td class=\"header\" width=\"15%\" align=\"left\">";
	echo "Adjudicated?";
	echo "</td>";
	echo "</tr>";





$cxn = connect_to_db();

/* First we will find all students who required
 * or still require adjudication.
 */

$getadjinfo = "SELECT studentID FROM studenttoberated WHERE RatingSessionID = '".$sessionid."'
		AND AdjReqd = 1";
$dogetadjinfo = mysqli_query($cxn,$getadjinfo)
		or die(mysql_error());


while ($adjstudentid = mysqli_fetch_row($dogetadjinfo))
{

	$id = $adjstudentid[0];
	$getstudentname = "SELECT FirstName, LastName FROM student WHERE StudentID = '".$id."'";
	$dogetname = mysqli_query($cxn,$getstudentname);
	$nameresults = mysqli_fetch_row($dogetname);
	$first_name = $nameresults[0];
	$last_name = $nameresults[1];

	echo "<tr>";
	echo "<td width=\"20%\" align=\"left\">";
	echo $first_name." ".$last_name;
	echo "</td>";

	$getstudentraters = "SELECT UserID FROM portfoliorating WHERE (RatingSessionID = '".$sessionid."') AND
			(StudentID = '".$id."') AND IsAdjudicator = 0";
	$dogetstudentraters = mysqli_query($cxn,$getstudentraters);
	$num_results = mysqli_num_rows($dogetstudentraters);




	$raters = mysqli_fetch_row($dogetstudentraters);
	$rater1 = $raters[0];
	$getrater1name = "SELECT UserName FROM user WHERE UserID = '".$rater1."'";
	$dorater1name = mysqli_query($cxn,$getrater1name);
	$result6 = mysqli_fetch_row($dorater1name);
	$rater1name = $result6[0];


	echo "<td width=\"20%\" align=\"left\">";
	echo $rater1name;
	echo "</td>";
	

	if ($num_results == 2)
	{
		$raters = mysqli_fetch_row($dogetstudentraters);
		$rater2 = $raters[0];
		$getrater2name = "SELECT UserName FROM user WHERE UserID = '".$rater2."'";
		$dorater2name = mysqli_query($cxn,$getrater2name);
		$result7 = mysqli_fetch_row($dorater2name);
		$rater2name = $result7[0];
	}
	else
	{
		$rater2name = 0;
	}
	

	echo "<td width=\"20%\" align=\"left\">";
	echo $rater2name;
	echo "</td>";

	$getadjudicator = "SELECT UserID FROM portfoliorating WHERE (RatingSessionID = '".$sessionid."') AND
		(StudentID = '".$id."') AND IsAdjudicator = 1";
	$dogetadj = mysqli_query($cxn,$getadjudicator);
	$adj = mysqli_fetch_row($dogetadj);
	$adjudicator = $adj[0];
	$getadjname = "SELECT UserName FROM user WHERE UserID = '".$adjudicator."'";
	$dogetadjname = mysqli_query($cxn,$getadjname);
	$setadjname = mysqli_fetch_row($dogetadjname);
	$adjname = $setadjname[0];


	echo "<td width=\"20%\" align=\"left\">";
	echo $adjname;
	echo "</td>";
	
	$getdonebit = "SELECT AdjDone FROM studenttoberated WHERE studentID = '".$id."' AND
		RatingSessionID = '".$sessionid."'";
	$dogetdonebit = mysqli_query($cxn,$getdonebit);
	$setdonebit = mysqli_fetch_row($dogetdonebit);
	$donebit = $setdonebit[0];


	if ($donebit == 1)
	{
		echo "<td width=\"15%\">";
		echo "<input type = \"checkbox\" checked>";
		echo "</td>";
	}
	else
	{
		echo "<td width=\"15%\">";
		echo "<input type = \"checkbox\">";
		echo "</td>";
	}		
		
	echo "</tr>";

}
echo "</table>";
?>

<form method="POST" action="return_from_adjudication_report.php">

<table border="0" width="95%" cellpadding="10">
  <form method="POST" action="return_from_student_scores.php">
  <tr>
    <td colspan="3" height="15" width="95%" align="center">
	What would you like to do now?
    </td>
  </tr>
  <tr>
    <td width="20%">
    </td>
    <td width="45%" align="left" type="text/css"
     style="background-color: #669966; color: white; font-weight: bolder;">
     <input type="radio" name="option1" value="report">Select Another Type of Report</td>
    <td width="30%">
    </td>
  </tr>
  <tr>
    <td width="20%">
    </td>
    <td width="45%" align="left" type="text/css"
     style="background-color: #669966; color: white; font-weight: bolder;">
     <input type="radio" name="option1" value="admin">Return to Main Administrator Page
    </td>
    <td width="30%">
    </td>
  </tr>
  <tr>
    <td colspan="3" width="95%" height="10" align="center" valign="bottom">
    <input type="submit" value="Submit">
    </td>
  </tr>
 

</table>

</form>
</body>
</html>

  
</body>
</html>