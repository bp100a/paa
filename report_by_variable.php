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
<title>Select Variable for Reporting</title>



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
      <h1>Select Variable for Reporting</h1>
    </td>
  </tr>
</table>

<?php

$cxn = connect_to_db();

/* This MySQL query retrieves the rating
 * session information, including the rubricID, 
 * which will allow us to produce a list of variables
 * for the administrator to select.
 */


$getcompids = "SELECT CompetencyID FROM rubriccontent WHERE RubricID = '".$rubricid."'";
$dogetcompids = mysqli_query($cxn,$getcompids)
	or die ("Couldn't execute query");



echo "<table border=\"0\" width=\"95%\">";
echo "<tr>";

echo "<td colspan=\"2\" width=\"95%\" height=\"100\">";
echo "</td>";
echo "</tr>";
echo "<tr>";

echo "<td width=\"30%\">";
echo "</td>";

echo "<td width=\"65%\" align=\"left\">";
echo "Please select only one variable at a time.";
echo "<br />";
echo "<form method=\"POST\" action=\"show_variable_scores.php\">";
echo "<select name=\"compid\">";

	
while ($compids = mysqli_fetch_row($dogetcompids))
{
	$compid = $compids[0];

	$getcompname = "SELECT CompName FROM competency WHERE CompetencyID = '".$compid."'";
	$dogetcompname = mysqli_query($cxn,$getcompname);
	$result = mysqli_fetch_row($dogetcompname);
	$compname = $result[0];

	echo "<option value=".$compid.">".$compname."</option>";
}
echo "</select>";


echo "<br />";
echo "<input type=\"submit\" value=\"Submit Selection\">";
echo "</td>";
echo "</tr>";
echo "</table>";
echo "</form>";

?>
  
</body>
</html>

