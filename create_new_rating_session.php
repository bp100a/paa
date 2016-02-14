<?php
require 'utilities.php'; session_start();



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
<title>Create a New Rating Session</title>



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
      <h1>Create a New Rating Session</h1>
    </td>
  </tr>
</table>

<?php

	echo "<table width=\"95%\" border=\"0\">";
	echo "<tr>";
	echo "<form method=\"POST\" action=\"create_session.php\">";

	echo "<td width=\"30%\" align=\"right\" valign=\"center\">
         Rubric Name: ";
	echo "</td>";


	echo "<td width=\"65%\" align=\"left\" valign=\"center\">";


	$getrubrics = "SELECT rubricid, rubricname FROM rubric";
	$dogetrubrics = mysqli_query($cxn,$getrubrics)
			or die(mysqli_error($cxn));

	echo "<select name=\"rubric\">";

	while ($rubrics = mysqli_fetch_row($dogetrubrics))
	{
		$rubricid = $rubrics[0];
		$rubricname = $rubrics[1];
		echo "<option value=\"".$rubricid."\">".$rubricname."</option>";
	}

	echo "</select>";

	echo "</td> ";
	echo "</tr>";

	echo "<tr>";
	echo "<td width=\"40%\" align=\"right\" valign=\"center\">
	     Session Name: ";
	echo "</td>";
	echo "<td width=\"55%\" align=\"left\" valign=\"center\">";
	echo "<input type=\"text\" name=\"NewSession\" />";
	echo "</td> ";
	echo "</tr>";
	echo "<tr>";


	echo "<td width=\"40%\" align=\"right\" valign=\"center\">
	  Activate?: ";
	echo "</td>";
	echo "<td width=\"55%\" align=\"left\" valign=\"center\">";
 	echo "<input type=\"checkbox\" name=\"activate\" value=\"Yes\" />";
	echo "</td>";
	echo "</tr>";

	
	echo "<td colspan = \"2\" width=\"95%\" align=\"center\" valign=\"center\">";
	echo "<input type=\"submit\" value=\"Create New Rating Session\"/>";
	echo "</td>";
	echo "</tr>";
	echo "</form>";
	echo "</table>";



  
echo "</body>";
echo "</html>";
?>