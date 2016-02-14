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

<title>Assign Adjudicators</title>
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
    <td colspan="2" width="95%" align="center" valign="center" height="50">
    <h1>Rating Session Control</h1>
    </td>
  </tr>
</table>

<table border="0" width="95%">
  <tr>
    <td colspan="2" width="95%" align="center" valign="center" height="50">
    <h4>Please select the rating sessions <br />
    you would like to deactivate.<br />
    You can select multiple sessions using the <br/>
    SHIFT key for sequential sessions or the<br />
    CTRL key for non-sequential selection.</h4>
    </td>
  </tr>
</table>

<?php


/* This module lets the admin create
 * new rating sessions or
 * deactivate old ratings sessions
 * or both.
 */



/* First we will look for currently
 * active rating sessions and ask
 * if the admin wants to deactivate them.
 */

$cxn = connect_to_db();
$user = "root";
$password = "gewbgttl";
$dbase = "mydb";

$cxn = mysqli_connect ($host,$user,$password,$dbase)
	or die ("couldn't connect to database");


/* Let's first find currently active
 * rating sessions.
 */



$getsessioninfo = "SELECT ratingsessionid, sessionname
		FROM ratingsession WHERE IsActive = 1";

$result = mysqli_query($cxn,$getsessioninfo);


$num_active_sess = mysqli_num_rows($result);

if ($num_active_sess < 1)
{
	echo "<table border=\"0\" width=\"95%\">";
	echo "<tr>";
	echo "<td colspan=\"2\" width=\"95%\" align=\"center\">";
	echo "There are no rating sessions currently active.";
	echo "</td>";
	echo "</tr>";
	echo "<tr>";
	echo "<td colspan=\"2\" width=\"95%\" align=\"center\">";
	echo "<form method=\"POST\" action=\"admin.php\">";
	echo "<input type=\"submit\" value=\"Return to Administrator Function page\">";
	echo "</td>";
	echo "</tr>";

	echo "</table>";
	echo "</form>";
}
else
{

	echo "<table border=\"0\" width=\"95%\" height=\"100\">";
	echo "<tr>";
	echo "<td colspan=\"2\" width=\"95%\" align=\"center\" valign=\"center\">";

	echo "<form action=\"complete_deactivation.php\" method=\"post\">";
	echo "<select name=\"sessionids[]\" multiple=\"multiple\" size=\"5\">";

	$avail_sessions = "SELECT ratingsessionid, sessionname FROM ratingsession
			WHERE IsActive = 1 ORDER BY RatingDate ASC";

	$dogetavail = mysqli_query($cxn,$avail_sessions)
			or die(mysqli_error($cxn));


	while ($sessinfo = mysqli_fetch_row($dogetavail))
	{
		$session_id = $sessinfo[0];
		$session_name = $sessinfo[1];
		echo "<option value=".$session_id.">".$session_name."</option>\n";
	}
	echo "</select>";

	echo "</td>";
	echo "</tr>";
	echo "<tr>";
	echo "<td colspan=\"2\" width=\"95%\" align=\"center\">";

	echo "<input type=\"submit\" value=\"Deactivate Selected Sessions\">";

	echo "</td>";
	echo "</tr>";

	echo "</table>";
	echo "</form>";

	echo "<table border=\"0\" width=\"95%\" height=\"150\">";
	echo "<tr>";
	echo "<td colspan=\"2\" width=\"95%\" align=\"center\" valign = \"center\">";
	echo "<form method=\"POST\" action=\"admin.php\">";
	echo "<input type=\"submit\" value=\"I do not want to deactivate any sessions; return to Administrator page\">";
	echo "</td>";
	echo "</tr>";

	echo "</table>";
	echo "</form>";
}
?>

</body>
</html>











