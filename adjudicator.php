<?php
require 'utilities.php';
require 'utilities.php'; session_start();

if (isset($_SESSION['ratingsession']))
{
	$sessionid = $_SESSION['ratingsession'];

}
else
{
	$sessionid = $_POST['session'];

	$_SESSION['ratingsession'] = $sessionid;
}


$userid = $_SESSION['thisuser'];





?>

<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/htm14/loose.dtd">


<html>

<head>
<meta http-equiv="Content-Type" content="text/html; charset-iso-8859-1">
<link rel="stylesheet" type="text/css" href="mystyles.css" />
<title>Adjudicate Portfolios</title>
</head>

<body>
<table border="0" width="95%">
  <tr>
    <td colspan="2" width="95%" align="left"
     type="text/css">
     <img src="njit_tag.jpg" width="233" height="60" border="0" />
     </td>
  </tr>
  <tr>
    <td width="95%" colspan="2"align="center">
        <h1>Select An Action</h1>
    </td>
  </tr>
</table>

<?php


$cxn = connect_to_db();

$getrubricinfo = "SELECT RubricID FROM ratingsession WHERE RatingSessionID = '".$sessionid."'";
$dogetrubric = mysqli_query($cxn,$getrubricinfo)
	or die(mysqli_error($cxn));
$setrubricid = mysqli_fetch_row($dogetrubric);
$rubricid = $setrubricid[0];

$_SESSION['rubricid'] = $rubricid;

$tab = "\t";

/* Let's see if the rater got bounced back
 * to here because they didn't select a student to adjudicate
 */

if ($_SESSION['nostudent'] == 1)
{
	echo "<h2>You did not select a student to adjudicate.<br>
		Please select a student from the drop down list first.</h2>";
	$_SESSION['nostudent'] = 0;
}




/*Here we'll check to see if there are any students
 * available for adjudication and let them
 * select that option.
 */

echo "<form method=\"POST\" action=\"build_adjudication_rubric.php\">";

$getadjlist = "SELECT s.studentid, s.firstname, s.lastname, s.njitstudentid
		FROM student s, studenttoberated stbr
		WHERE stbr.ratingsessionid = '".$sessionid."'
		AND stbr.AdjReqd = 1
		AND stbr.AdjDone = 0
		AND stbr.studentid = s.studentid
		AND stbr.ratingcount = stbr.numratings
		ORDER BY s.lastname";


$dogetadjlist = mysqli_query($cxn,$getadjlist)
		or die(mysqli_error($cxn));
$getadjcount = mysqli_num_rows($dogetadjlist);

echo "<table border=\"0\" width=\"95%\" cellspacing=\"10\">";
echo "<tr>";
echo "<td colspan=\"3\" width=\"95%\" height=\"100\">";
echo "</td>";
echo "</tr>";

echo "<tr>";

if ($getadjcount < 1)
{


	echo "<td width=\"45%\" align=\"right\" height=\"50\">";

	echo "There are no students requiring adjudication.";
	echo "</td>";
}
else
{

	echo "<td width=\"45%\" align=\"right\" height=\"50\">";
	echo "<select name=\"adjstudent\">";
	echo "<option value=\"NULL\">Select a Student to Adjudicate</option>\n";

	while ($setadjlist = mysqli_fetch_row($dogetadjlist))
	{
		
		$adjstudentid = $setadjlist[0];
		$adjfirstname = $setadjlist[1];
		$adjlastname = $setadjlist[2];
		$adjnjitida = $setadjlist[3];

		echo "<option value=".$adjstudentid.">".$adjnjitida.$tab.$adjlastname.", ".$adjfirstname."</option>\n";
	}
	echo "</select>";
	echo "<br />";
	echo "<input type=\"submit\" value=\"Adjudicate Student\">";
	echo "</td>";
}

echo "<td width=\"40%\" align=\"left\" height=\"50\" type=\"text/css\"
	style=\"background-color: #669966; color: white; font-weight: bolder;\">";
echo "Adjudicate a student portfolio that has discrepant scores";
echo "</td>";
echo "<td width=\"10%\">";
echo "</td>";
echo "</tr>";
echo "</form>";	




echo "<tr>";
echo "<td width=\"45%\" align=\"right\" height=\"50\">";
echo "<form method=\"POST\" action=\"logout.php\">";
echo "<input type=\"submit\" value=\"Logout\">";
echo "</td>";
echo "</form>";	
echo "<td width=\"40%\" align=\"left\" height=\"50\" type=\"text/css\"
	style=\"background-color: #669966; color: white; font-weight: bolder\">";
echo "Log out of the rating session";
echo "</td>";
echo "<td width=\"10%\">";
echo "</td>";
echo "</tr>";
echo "</table>";
	




?>


</body>
</html>
