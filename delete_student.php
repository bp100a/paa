<?php
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
<title>Select Rater Action</title>
</head>

<body>
<table border="0" width="95%">
  <tr>
    <td colspan="2" width="95%" align="left"
     type="text/css">
     <img src="njit_tag.jpg" width="233" height="60" border="0" />
     </td>
  </tr>
</table>



<table border="0" width="95%">
  <tr>
    <td width="95%" colspan="2"align="center">
        <h1>Select An Action</h1>
    </td>
  </tr>
</table>

<?php
echo $sessionid;

$cxn = connect_to_db();
$user = "root";
$password = "gewbgttl";
$dbase = "mydb";

$cxn = mysqli_connect ($host,$user,$password,$dbase)
	or die ("couldn't connect to database");

$getrubricinfo = "SELECT RubricID FROM ratingsession WHERE RatingSessionID = '".$sessionid."'";
$dogetrubric = mysqli_query($cxn,$getrubricinfo)
	or die(mysqli_error($cxn));
$setrubricid = mysqli_fetch_row($dogetrubric);
$rubricid = $setrubricid[0];

$_SESSION['rubricid'] = $rubricid;

$tab = "\t";





echo "<table border=\"0\" width=\"95%\" cellspacing=\"10\">";
echo "<tr>";
echo "<td colspan=\"3\" width=\"95%\" height=\"100\">";
echo "</td>";
echo "</tr>";

echo "<tr>";
echo "<td width=\"45%\" align=\"right\" height=\"50\">";
echo "<form method=\"POST\" action=\"remove_student.php\">";
echo "<select name=\"student\">";
echo "<option value=\"NULL\">Select a Student to delete</option>\n";


$getstudentname = "SELECT s.studentid, s.njitstudentid, s.firstname, s.lastname
		FROM student s, studenttoberated stbr
		WHERE stbr.studentid = s.studentid AND
		stbr.ratingsessionid = '".$sessionid."'
		ORDER BY s.lastname ASC";

$dogetname = mysqli_query($cxn,$getstudentname)
		or die(mysqli_error($cxn));

While($setname = mysqli_fetch_row($dogetname))
{
	$studentid = $setname[0];
	$njitid = $setname[1];
	$first = $setname[2];
	$last = $setname[3];	



/* We will get the list of students to be rated
 * but we will not display any students who have
 * already been rated twice.
 */


	$getnumratings = "SELECT NumRatings, RatingCount FROM studenttoberated 
			WHERE RatingSessionID = '".$sessionid."' 
			AND StudentID = '".$studentid."'";
	$dogetcount = mysqli_query($cxn,$getnumratings)
			or die(mysqli_error($cxn));
	$setcount = mysqli_fetch_row($dogetcount);
	$numratings = $setcount[0];
	$ratingcount = $setcount[1];

	echo "<option value=".$studentid.">".$njitid.$tab.$last.", ".$first.$tab.$ratingcount."</option>\n";

}

echo "</select>";
echo "<br />";
echo "<input type=\"submit\" value=\"Delete Student\">";
echo "</td>";
echo "<td width=\"40%\" align=\"left\" height=\"50\" type=\"text/css\"
	style=\"background-color: #669966; color: white; font-weight: bolder;\">";
echo "Delete the selected student";
echo "</td>";
echo "<td width=\"10%\">";
echo "</td>";
echo "</tr>";

echo "</form>";	

echo "<tr>";





$getadminstatus = "SELECT IsAdmin FROM user WHERE UserID = '".$userid."'";
$dogetadminstatus = mysqli_query($cxn,$getadminstatus)
		or die(mysqli_error($cxn));
$setadminstatus = mysqli_fetch_row($dogetadminstatus);
$adminstatus = $setadminstatus[0];

if ($adminstatus == 1)
{

	echo "<tr>";
	echo "<td width=\"45%\" align=\"right\" height=\"50\">";
	echo "<form method=\"POST\" action=\"admin.php\">";

	echo "<input type=\"submit\" value=\"Return to Administrator page\">";
	echo "</td>";
	echo "<td width=\"40%\" align=\"left\" height=\"50\" type=\"text/css\"
		style=\"background-color: #669966; color: white; font-weight: bolder;\">";
	echo "Return to the Administrator page";
	echo "</td>";
	echo "<td width=\"10%\">";
	echo "</td>";
	echo "</tr>";
	echo "</form>";		
}


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
