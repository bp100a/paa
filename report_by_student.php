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
<title>Select Student for Reporting</title>



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
      <h1>Select Student for Reporting</h1>
    </td>
  </tr>
</table>


    <form method="POST" action="show_student_Scores.php">

<?php

	
$cxn = connect_to_db();

$getratedstudents = "SELECT DISTINCT StudentID FROM portfoliorating WHERE RatingSessionID = '".$sessionid."'";
$dogetratedstudents = mysqli_query($cxn,$getratedstudents)
		or die(mysqli_error($cxn));

	echo "<table border=\"0\" width=\"95%\">";
	echo "<tr>";
	echo "<td colspan=\"2\" width=\"95%\" align=\"center\" height=\"50\" valign=\"bottom\">";
	echo "<h2>Select a Student Whose Scores You Want to View</h2>";
	echo "</td>";
	echo "</tr>";


	echo "<tr>";
	echo "<td colspan=\"2\" width=\"95%\" align=\"center\" height=\"50\">";
	echo "<select name=\"student\">";
	echo "<option value=\"NULL\">Select a Student</option>\n";


	while ($setratedlist = mysqli_fetch_row($dogetratedstudents))
	{

		$studentid = $setratedlist[0];

		$getstudentname = "SELECT FirstName, LastName, NJITStudentiD FROM student WHERE 
			StudentID = '".$studentid."'";
		$dogetname = mysqli_query($cxn,$getstudentname)
			or die(mysqli_error($cxn));
		$setname = mysqli_fetch_row($dogetname);
		$first = $setname[0];
		$last = $setname[1];
		$njitid = $setname[2];

		echo "<option value=".$studentid.">".$njitid.$tab.$last.", ".$first."</option>\n";
	}

	echo "</select>";
	echo "</td>";
	echo "</tr>";

	echo "<tr>";
	echo "<td colspan=\"2\" width=\"95%\" align=\"center\">";
	echo "<input type=\"submit\" value=\"View Scores for Student\">";
	echo "</td>";
	echo "</tr>";
	echo "</table>";
	echo "</form>";	

?>

 
</table>


  
</body>
</html>