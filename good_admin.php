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
$user = "root";
$password = "gewbgttl";
$dbase = "mydb";

$cxn = mysqli_connect ($host,$user,$password,$dbase)
	or die ("couldn't connect to database");

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
    <td width="45%" align="center" valign="center">
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

<table border="0" width="95%" cellpadding="15">
  <form method="POST" action="admin_choice.php">
  <tr>
    <td width="20%" align="center">
    </td>
    <td width="55%" align="left" type="text/css"
     style="background-color: #669966; color: white; font-weight: bolder;">
     <input type="radio" name="selection" value="rate"><h3>Rate a Student</h3>In addition to performing administrative functions,
you can choose to rate students during the rating session.
    </td>
    <td width="20%">
    </td>
  </tr>
  <tr>
    <td width="20%" align="center">
    </td>
    <td width="55%" align="left" type="text/css"
     style="background-color: #669966; color: white; font-weight: bolder;">
     <input type="radio" name="selection" value="reports"><h3>View Reports</h3>You can monitor the progress of the          rating session by viewing various reports.
    </td>
    <td width="20%">
    </td>
  </tr>
  <tr>
    <td width="20%" align="center">
    </td>
    <td width="55%" align="left" type="text/css"
     style="background-color: #669966; color: white; font-weight: bolder;">
     <input type="radio" name="selection" value="adjudicate"><h3>Assign Adjudicator</h3>You can check for students who
    need adjudication andassign them to a rater. You should do this periodically throughout the rating session.
    </td>
    <td width="20%">
    </td>
  </tr>
  <tr>
    <td width="20%" align="center">
    </td>
    <td width="55%" align="left" type="text/css"
     style="background-color: #669966; color: white; font-weight: bolder;">
     <input type="radio" name="selection" value="ratings"><h3>Search for Incomplete Ratings</h3>You can identify the students who haveonly been rated by one rater. Do this towards the end of the rating session to ensure that all students have been rated twice.</p>
    </td>
    <td width="20%">
    </td>
  </tr>

  <tr>
    <td width="20%" align="center">
    </td>
    <td width="55%" align="left" type="text/css"
     style="background-color: #669966; color: white; font-weight: bolder;">
     <input type="radio" name="selection" value="export"><h3>Prepare Data for Export</h3>Once the rating session is complete, you
    must prepare the data for export using this option.
    </td>
    <td width="20%">
    </td>
  </tr>
  <tr>
    <td width="20%" align="center">
    </td>
    <td width="45%" align="left" type="text/css"
     style="background-color: #669966; color: white; font-weight: bolder;">
     <input type="radio" name="selection" value="logout">Logout</td>
    <td width="30%">
    </td>
  </tr>
  <tr>
    <td width="20%" align="center">
    </td>
    <td width = "55%" align="center">
    <input type="submit" value="Submit">
    </td>
    <td width="20%">
  </tr>
</table>


</form>
</body>
</html>