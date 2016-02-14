<?php
require 'utilities.php'; session_start();
$rubricid = $_SESSION['rubricid'];
$sessionid = $_SESSION['ratingsession'];

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/htm14/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset-iso-8859-1">
<link rel="stylesheet" type="text/css" href="mystyles.css" />
<title>Select Rater for Reporting</title>



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
      <h1>Select Rater for Reporting</h1>
    </td>
  </tr>
</table>


    <form method="POST" action="show_rater_scores.php">

<?php

	
$cxn = connect_to_db();

$getrater = "SELECT DISTINCT UserID FROM portfoliorating WHERE RatingSessionID = '".$sessionid."'";
$dogetrater = mysqli_query($cxn,$getrater)
		or die(mysqli_error($cxn));

	echo "<table border=\"0\" width=\"95%\">";
	echo "<tr>";
	echo "<td colspan=\"2\" width=\"95%\" align=\"center\" height=\"50\" valign=\"bottom\">";
	echo "<h2>Select a Rater Whose Scores You Want to View</h2>";
	echo "</td>";
	echo "</tr>";


	echo "<tr>";
	echo "<td colspan=\"2\" width=\"95%\" align=\"center\" height=\"50\">";
	echo "<select name=\"rater\">";
	echo "<option value=\"NULL\">Select a Rater</option>\n";


	while ($setraterlist = mysqli_fetch_row($dogetrater))
	{

		$raterid = $setraterlist[0];

		$getratername = "SELECT UserName FROM user WHERE 
			UserID = '".$raterid."'";
		$dogetname = mysqli_query($cxn,$getratername)
			or die(mysqli_error($cxn));
		$setname = mysqli_fetch_row($dogetname);
		$ratername = $setname[0];


		echo "<option value=".$raterid.">".$ratername."</option>\n";
	}

	echo "</select>";
	echo "</td>";
	echo "</tr>";

	echo "<tr>";
	echo "<td colspan=\"2\" width=\"95%\" align=\"center\">";
	echo "<input type=\"submit\" value=\"View Scores for this Rater\">";
	echo "</td>";
	echo "</tr>";
	echo "</table>";
	echo "</form>";	

?>
 
</table>


  
</body>
</html>