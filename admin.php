<?php
require_once 'utilities.php';
session_start();

$_SESSION['newsessionid'] = 0;

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

<title>Administrator Functions</title>
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
     <td width="20%" align="center">
    </td>
    <td width="45%" align="right" valign="center">
      <h1>Administrator Functions</h1>
    </td>
    <td width="30%">
    </td>
  </tr>
  <tr>
    <td colspan="2" width="95%" height="30">
    </td>
  </tr>
</table>

<table border="0" width="95%" cellspacing="10">
  <form method="POST" action="student_info.php">
  <tr>
    <td width="30%" align="right" height="50">
    <input type="submit" value="Rate a Student">
    </td>
    <td width="55%" align="left" height="50" type="text/css"
     style="background-color: #669966; color: white; font-weight: bolder;">
     Rate a student portfolio.
    </td>
    <td width="10%">
    </td>
  </tr>
  </form>
  <form method="post" action="admin_monitor.php">
  <tr>
    <td width="30%" align="right" height="50">
    <input type="submit" value="Monitor Portfolio Assessment">
    </td>
    <td width="55%" align="left" height="50" type="text/css"
     style="background-color: #669966; color: white; font-weight: bolder;">
     Monitor the progress of the rating session, including deleting students, checking adjudications, 
	reviewing students still requiring rating, and generating various reports.
    </td>
    <td width="10%">
    </td>
  </tr>
  </form>
  <form method="POST" action="admin_manage.php">
  <tr>
    <td width="30%" align="right" height="50">
    <input type="submit" value="Manage Rating Sessions">
    </td>
    <td width="55%" align="left" height="50" type="text/css"
     style="background-color: #669966; color: white; font-weight: bolder;">
     Manage rating sessions. From here you can create new sessions, deactivate old sessions, and create new rubrics.
    </td>
    <td width="10%">
    </td>
  </tr>
  </form>

  <form method="POST" action="create_Scores_table.php">
  <tr>
    <td width="30%" align="right" height="50">
    <input type="submit" value="Save Final Scores"
    </td>
    <td width="55%" align="left" height="50" type="text/css"
     style="background-color: #669966; color: white; font-weight: bolder;">
     Once the rating session is complete, use this
     option to save the final data to a table in the database.
    </td>
    <td width="10%">
    </td>
  </tr>
  </form>

  <form method="POST" action="logout.php">
  <tr>
    <td width="30%" align="right" height="50">
    <input type="submit" value="Logout">
    </td>
    <td width="55%" align="left" height="50" type="text/css"
     style="background-color: #669966; color: white; font-weight: bolder;">
     Log out of the application after completing all other administrative tasks.
    </td>
    <td width="10%">
    </td>
  </tr>
  </form>
</table>


</form>
</body>
</html>