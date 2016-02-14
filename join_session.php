<?php
require 'utilities.php'; session_start();

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/htm14/loose.dtd">
<html>

<head>
<meta http-equiv="Content-Type" content="text/html; charset-iso-8859-1">
<link rel="stylesheet" type="text/css" href="mystyles.css" />

<title>Join a Rating Session</title>
</head>
<body>

<table border="0" width="95%" cellpadding="20">
  <tr>
     <td colspan="3" width="95%" align="left" valign="bottom"
      type="text/css"
      <img src="njit_tag.jpg" width="233" height="60" border="0" />
     </td>  
  </tr>
  <tr>
     <td colspan="2" width="95%" align="center" height="50">
      <h1>Select a Rating Session to Join</h1>
    </td>
  </tr>
</table>

<?php

/* Lets users join a rating session.  If the user
 * is not an admin, he is taken here directly after his login
 * status is verified.  If he is an admin,
 * he goes to the Admin Task screen first in case he
 * needs to create a new rating session or add students.
 */



$cxn = connect_to_db();

/* We should only get to this module
 * if the user is an Admin and they
 * select Join a Rating Session.
 */

$_SESSION['thisuser'] = $userid;

echo "<table border=\"0\" width=\"95%\">";
echo "<tr>";
echo "<td colspan=\"2\" width=\"95%\" align=\"center\" height=\"100\">";
echo "<h2>Please select a rating session to join.</h2>";
echo "</td>";
echo "</tr>";


$getratingsessions = "SELECT RatingSessionID, SessionName FROM ratingsession WHERE IsActive = 1";
$dogetsessions = mysqli_query($cxn,$getratingsessions)
		or die(mysqli_error($cxn));
if(mysqli_num_rows($dogetsessions))
{

	echo "<form method=\"POST\" action=\"admin.php\">";

	echo "<tr>";
	echo "<td colspan=\"2\" width=\"95%\" align=\"center\" height=\"100\">";
	echo "<select name=\"session\">";
	echo "<option value=\"NULL\">Select a Rating Session</option>\n";

	while ($setsession = mysqli_fetch_row($dogetsessions))
	{
		$sessionchoice = $setsession[0];
		$sessionname = $setsession[1];

		echo "<option value=".$sessionchoice.">".$sessionname."</option>\n";
	}
	echo "</select>";
	echo "</td>";
	echo "</tr>";

	echo "<tr>";
	echo "<td colspan=\"2\" width=\"95%\" align=\"center\">";
	echo "<input type=\"submit\" value=\"Join Rating Session\">";
	echo "</td>";
	echo "</tr>";
	echo "</form>";

}







echo "</table>";


?>