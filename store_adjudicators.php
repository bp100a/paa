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

<title>Store Adjudicator Assignments</title>
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
    <h1>Store Adjudicator Assignments</h1>
    </td>
  </tr>
</table>

<?php 

print_r($_POST);

$count = $_POST['count'];


$cxn = connect_to_db();

for ($i = 0; $i < $count; $i++)
{

	$string1 = "student";
	$suffix = strval($i);
	$studentstring = $string1.$suffix;
	$string2 = "adjudicator";
	$adjstring = $string2.$suffix;
	$string3 = "assigned";
	$assignstring = $string3.$suffix;

	$studentforadj = $_POST[$studentstring];
	$assignedadj = $_POST[$adjstring];
	$isassigned = $_POST[$assignstring];


	if (isset($isassigned))
	{

		$writestudadj = "INSERT INTO portfoliorating (RatingSessionID, UserID, Studentid, IsAdjudicator)
			VALUES ('".$sessionid."', '".$assignedadj."', '".$studentforadj."', 1)";
		$result = mysqli_query($cxn,$writestudadj)
			or die("Couldn't update rater's record as adjudicator");

		$getratingcount = "SELECT RatingCount FROM studenttoberated WHERE StudentID = '".$studentforadj."'";
		$dogetratingcount = mysqli_query($cxn,$getratingcount);
		$setratingcount = mysqli_fetch_row($dogetratingcount);
		$ratingcount = $setratingcount[0];

		if ($ratingcount < 3)
		{
			$updateratingcount = "UPDATE studenttoberated SET RatingCount = RatingCount + 1 
				WHERE StudentID = '".$studentforadj."' AND RatingSessionID = '".$sessionid."'";
			$doupdatecount = mysqli_query($cxn,$updateratingcount)
				or die("Could not update rating count for adjudication");
		}
		else
		{
			echo "Student ".$studentforadj." has already been rated three times.";
		}

	}
}

?>

<table border="0" width="95%" align="center">
<tr>
<td colspan="2" align="center" height="50">
Your selected adjudication assignments have been stored.<br />
What would you like to do now?<br />
</td>
</tr>
</table>

<table border="0" width="95%" cellpadding="15">
  <tr>
  <form method="POST" action="student_info.php">
    <td width="30%" align="right">
    <input type="submit" value="Rate Student">
    </td>
    <td width="55%" align="left" type="text/css"
     style="background-color: #669966; color: white; font-weight: bolder;">
     Rate a student 
    </td>
    <td width="10%">
    </td>
    </form>
  </tr>
  <tr>
  <form method="POST" action="select_report.php">
    <td width="30%" align="right">
    <input type="submit" value="Select Report">
    </td>
    <td width="55%" align="left" type="text/css"
     style="background-color: #669966; color: white; font-weight: bolder;">
    Monitor the progress of the rating session by viewing various reports.</p>
    </td>
    <td width="10%">
    </td>
    </form>
  </tr>
  <tr>
  <form method="POST" action="find_incomplete_ratings.php">
    <td width="30%" align="right">
    <input type="submit" value="Find Incomplete Ratings">
    </td>
    <td width="55%" align="left" type="text/css"
     style="background-color: #669966; color: white; font-weight: bolder;">
     Prior to ending a rating session, locate any students who have only been rated once.
    </td>
    <td width="10%">
    </td>
    </form>
  </tr>
 <tr>
    <form method="POST" action="create_scores_table.php">
    <td width="30%" align="right">
    <input type="submit" value="Save Portfolio Scores">
    </td>
    <td width="55%" align="left" type="text/css"
     style="background-color: #669966; color: white; font-weight: bolder;">
     Once the rating session is complete, save the data to a results table in the database.
    </td>
    <td width="10%">
    </td>
    </form>
  </tr>
  <tr>
    <form method="POST" action="admin.php">
    <td width="30%" align="right">
	<input type="submit" value="Return to Admin Page">
    </td>
    <td width="55%" align="left" type="text/css"
     style="background-color: #669966; color: white; font-weight: bolder;">
    Return to the main Administrator page
    </td>
    <td width="10%">
    </td>
    </form>
  </tr>
  <tr>
    <form method="POST" action="logout.php">
    <td width="30%" align="right">
	<input type="submit" value="Logout">
    </td>
    <td width="55%" align="left" type="text/css"
     style="background-color: #669966; color: white; font-weight: bolder;">
    Log out of the application
    </td>
    <td width="10%">
    </td>
    </form>
  </tr>

</table>


</body>
</html>

