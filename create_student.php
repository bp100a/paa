<?php
require_once 'utilities.php'; session_start();



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
<title>New Student Record Created</title>



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
      <h1>The Student Record Has Been Created</h1>
    </td>
  </tr>
</table>

<?php

$newid = $_POST['NewID'];
$newsection = $_POST['NewSection'];
$newlast = $_POST['NewLast'];
$newfirst = $_POST['NewFirst'];
$numratings = $_POST['NumRatings'];
$newsession = $_POST['NewSession'];

$insertstudent = "INSERT INTO student (NJITStudentID, Section, FirstName, LastName, 
		CreateTime, LastUpdateTime) 
		VALUES ('".$newid."','".$newsection."','".$newfirst."','".$newlast."',
		NOW(),NOW())";
$doinsert = mysqli_query($cxn,$insertstudent)
		or die(mysqli_error($cxn));

if (!$doinsert)
{

	echo "<table border=\"0\" width=\"95%\" height=\"50\">";
	echo "<tr>";
	echo "<td colspan=\"2\" width=\"95%\" align=\"center\">";
	echo "We were unable to create the student record. <br />Please contact the system administrator.";
	echo "</td>";
	echo "</tr>";
	echo "</table>";

}
else
{
	$getstid = "SELECT StudentID FROM student WHERE NJITStudentID = '".$newid."'";
	$dogetstid = mysqli_query($cxn,$getstid);
	$temp = mysqli_fetch_row($dogetstid);
	$stid = $temp[0];

	$addtolist = "INSERT INTO studenttoberated (StudentID, RatingSessionID, NumRatings, RatingCount, 
			AdjReqd, AdjDone)
			VALUES ('".$stid."','".$newsession."','".$numratings."',0,0,0)";
	$doaddtolist = mysqli_query($cxn,$addtolist)
			or die(mysqli_error($cxn));
	
}	

	echo "<table border=\"0\" width=\"95%\">";
	echo "<tr>";
	echo "<td colspan=\"3\" width=\"95%\" align=\"center\">";
	echo "What would you like to do now? ";
	echo "</td>";
	echo "</tr>";
	echo "<tr>";
	echo "<td width=\"40%\" align=\"right\">";
	echo "<form method=\"POST\" action=\"add_new_student.php\">";
	echo "<input type=\"submit\" Value=\"Add Another Student\">";
	echo "</form>";
	echo "</td>";
	echo "<td width=\"15%\" align=\"center\">";
	echo "</td>";
	echo "<td width=\"40%\" align=\"left\">";
	echo "<form method=\"POST\" action=\"logout.php\">";
	echo "<input type=\"submit\" Value=\"Logout\">";
	echo "</form>";
	echo "</td>";
	echo "</tr>";

	echo "</table>";
  

?>