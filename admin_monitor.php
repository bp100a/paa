<?php
require_once 'utilities.php';
session_start();

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

<title>Administrator Monitoring Functions</title>
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
      <h1>Administrator Monitoring Functions</h1>
    </td>
    <td width="30%">
    </td>
  </tr>
  <tr>
    <td colspan="2" width="95%" height="30">
    </td>
  </tr>
  <tr>
    <td colspan="2" width="95%" height="30">
    <h2>Monitor an active portfolio rating session</h2>
    </td>
  </tr>
</table>

<table border="0" width="95%" cellspacing="10">

  <form method="post" action="delete_student.php">
  <tr>
    <td width="30%" align="right" height="50">
    <input type="submit" value="Delete Student">
    </td>
    <td width="55%" align="left" height="50" type="text/css"
     style="background-color: #669966; color: white; font-weight: bolder;">
     Delete a student from the list of students to be rated in this rating session.
    </td>
    <td width="10%">
    </td>
  </tr>
  </form>

  <form method="post" action="select_report.php">
  <tr>
    <td width="30%" align="right" height="50">
    <input type="submit" value="View Reports">
    </td>
    <td width="55%" align="left" height="50" type="text/css"
     style="background-color: #669966; color: white; font-weight: bolder;">
     Monitor the progress of the rating session by viewing reports.
    </td>
    <td width="10%">
    </td>
  </tr>
  </form>
  <form method="POST" action="students_to_be_adjudicated.php">
  <tr>
    <td width="30%" align="right" height="50">
    <input type="submit" value="View Adjudication Info">
    </td>
    <td width="55%" align="left" height="50" type="text/css"
     style="background-color: #669966; color: white; font-weight: bolder;">
     Monitor students requiring adjudication.  You must select expert readers
	to log in as Adjudicator1 - Adjudicator6 to perform adjudications.
    </td>
    <td width="10%">
    </td>
  </tr>
  </form>
  <form method="POST" action="review_adjudications.php">
  <tr>
    <td width="30%" align="right" height="50">
    <input type="submit" value="Monitor Adjudication Progress">
    </td>
    <td width="55%" align="left" height="50" type="text/css"
     style="background-color: #669966; color: white; font-weight: bolder;">
     Monitor progress of adjudicators.  You can see which students have been adjudicated by
	which adjudicators.
    </td>
    <td width="10%">
    </td>
  </tr>
  </form>
  <form method="POST" action="find_incomplete_ratings.php">
  <tr>
    <td width="30%" align="right" height="50">
    <input type="submit" value="Search for Incomplete Ratings">
    </td>
    <td width="55%" align="left" height="50" type="text/css"
     style="background-color: #669966; color: white; font-weight: bolder;">
     Identify the students who have only been rated once. Do this towards 
     the end of the rating session to ensure that all students      have been rated twice.
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

  <form method="POST" action="admin.php">
  <tr>
    <td width="30%" align="right" height="50">
    <input type="submit" value="Return to the main Administrator page">
    </td>
    <td width="55%" align="left" height="50" type="text/css"
     style="background-color: #669966; color: white; font-weight: bolder;">
     Return to the main Administrator page.
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