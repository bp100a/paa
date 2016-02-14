<?php
require_once 'utilities.php'; session_start();



$cxn = connect_to_db();
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/htm14/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset-iso-8859-1">
<link rel="stylesheet" type="text/css" href="mystyles.css" />
<title>Add Students</title>



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
      <h1>Add Students to a Rating Session</h1>
    </td>
  </tr>
</table>

<?php

	$writefile = "SELECT * from RESULTS
			INTO OUTFILE 'results.csv'
			FIELDS TERMINATED BY ','
			LINES TERMINATED BY '\n'";
	$dowritefile = mysqli_query($cxn,$writefile)
			or die("Unable to write output file");
	

	echo "<table width=\"95%\" border=\"0\">";
	echo "<tr>";
	echo "<form method=\"POST\" action=\"admin.php\">";

	echo "<tr>";
	echo "<td width=\"30%\" align=\"right\" valign=\"center\">
         NJIT Student ID: ";
	echo "</td>";
	echo "<td width=\"65%\" align=\"left\" valign=\"center\">";
 	echo "<input type=\"submit\" value=\"Return to Main Administrator Page\" />";
	echo "</td> ";
	echo "</tr>";


  
echo "</body>";
echo "</html>";
?>