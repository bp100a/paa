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

$cxn = connect_to_db();

$getrubricinfo = "SELECT RubricID FROM ratingsession WHERE RatingSessionID = '".$sessionid."'";
$dogetrubric = mysqli_query($cxn,$getrubricinfo)
	or die(mysqli_error($cxn));
$setrubricid = mysqli_fetch_row($dogetrubric);
$rubricid = $setrubricid[0];

$_SESSION['rubricid'] = $rubricid;



?>


<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/htm14/loose.dtd">
<html>

<head>
<meta http-equiv="Content-Type" content="text/html; charset-iso-8859-1">
<link rel="stylesheet" type="text/css" href="mystyles.css" />

<title>Administrator Session Management</title>
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
     <td width="20%" align="center">
    </td>
    <td width="45%" align="right" valign="center">
      <h1>Administrator Functions</h1>
    </td>
    <td width="30%">
    </td>
  </tr>
  <tr>
    <td colspan="3" width="95%" height="10">
    </td>
  </tr>
  <tr>
    <td colspan="3" width="95%" align="center">
    <h2>Manage Portfolio Assessment Sessions</h2>
    </td>
  </tr>
</table>

<table border="0" width="95%" cellspacing="10">

  <form method="POST" action="create_new_rating_session.php">
  <tr>
    <td width="30%" align="right" height="50">
    <input type="submit" value="Create a New Rating Session"
    </td>
    <td width="55%" align="left" height="50" type="text/css"
     style="background-color: #669966; color: white; font-weight: bolder;">
     Create a new rating session for an upcoming portfolio assessment.
    </td>
    <td width="10%">
    </td>
  </tr>
  </form>
  <form method="POST" action="deactivate_sessions.php">
  <tr>
    <td width="30%" align="right" height="50">
    <input type="submit" value="Deactivate an Active Rating Session"
    </td>
    <td width="55%" align="left" height="50" type="text/css"
     style="background-color: #669966; color: white; font-weight: bolder;">
     Deactivate a rating session that is currently active. You should not keep older sessions active
     as raters may inadvertently join the wrong session.
    </td>
    <td width="10%">
    </td>
  </tr>
  </form>
  <form method="POST" action="select_csv_file.php">
  <tr>
    <td width="30%" align="right" height="50">
    <input type="submit" value="Input New Students"
    </td>
    <td width="55%" align="left" height="50" type="text/css"
     style="background-color: #669966; color: white; font-weight: bolder;">
     Read in a CSV file that contains information for new students to be rated.
    </td>
    <td width="10%">
    </td>
  </tr>
  </form>
  <form method="POST" action="add_new_student.php">
  <tr>
    <td width="30%" align="right" height="50">
    <input type="submit" value="Add a Single Student"
    </td>
    <td width="55%" align="left" height="50" type="text/css"
     style="background-color: #669966; color: white; font-weight: bolder;">
     Add an individual student record for rating (can be done during an assessment session).
    </td>
    <td width="10%">
    </td>
  </tr>
  </form>
  <form method="POST" action="admin.php">
  <tr>
    <td width="30%" align="right" height="50">
    <input type="submit" value="Return to main Administrator page">
    </td>
    <td width="55%" align="left" height="50" type="text/css"
     style="background-color: #669966; color: white; font-weight: bolder;">
     Return to the main administrator page.
    </td>
    <td width="10%">
    </td>
  </tr>
  </form>
</table>


</form>
</body>
</html>