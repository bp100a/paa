<?php
require_once 'utilities.php'; session_start();

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

<title>Rating Session Administration</title>
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
      <h1>Rating Session Functions</h1>
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
  <form method="POST" action="create_new_rating_session.php">
  <tr>
    <td width="30%" align="right" height="50">
    <input type="submit" value="Create New Rating Sessions">
    </td>
    <td width="55%" align="left" height="50" type="text/css"
     style="background-color: #669966; color: white; font-weight: bolder;">
     Create a new rating session for every portfolio reading session.<br />
     If you have paper portfolios and e-portfolios, you need to create a<br />
     rating session for each one because they use different rubrics.
    </td>
    <td width="10%">
    </td>
  </tr>
  </form>
  <form method="post" action="deactivate_sessions.php">
  <tr>
    <td width="30%" align="right" height="50">
    <input type="submit" value="Deactive Current Rating Sessions">
    </td>
    <td width="55%" align="left" height="50" type="text/css"
     style="background-color: #669966; color: white; font-weight: bolder;">
     You should deactivate older rating sessions so that raters do not<br />
     inadvertently log into the wrong rating session.
    </td>
    <td width="10%">
    </td>
  </tr>
  </form>
  <form method="POST" action="admin.php">
  <tr>
    <td width="30%" align="right" height="50">
    <input type="submit" value="Return to Admin Page">
    </td>
    <td width="55%" align="left" height="50" type="text/css"
     style="background-color: #669966; color: white; font-weight: bolder;">
     Return to the main administrator page.
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