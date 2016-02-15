<?php
require_once 'utilities.php'; /* consolidate DB connection information */

session_start();

// Create short names for variables
$username=$_POST['username'];
$f_password=$_POST['pwd'];




$username = strtolower($username);

function filled_out($form_vars)
{
	// test that each variable has a value

	foreach ($form_vars as $key => $value)
	{
		if ((!isset($key)) || ($value == ''))
		{
			return false;
		}
	}
	return true;
}



try
{

	if (!filled_out($_POST))
	{
		throw new Exception('You have not filled out the form correctly -
		please go back and try again.');

	}


}
	
catch (Exception $e)
{

	$_SESSION['username'] = $username;
	header('Location: login.php');
	

}
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
      <h1>Join a Rating Session</h1>
    </td>
  </tr>
</table>

<?php

/* This module checks to see if
 * the user already exists in the database.
 * If so, the password is checked and
 * the user is logged in and a new session is begun.
 * If the user is new, a record is created and
 * then the user is logged in and a new
 * session is started.
 */


$cxn = connect_to_db();



/* The default setting for adminstatus
 * is 0 because most users will be raters.
 * We test for adminstatus later, but any new
 * account that is created defaults to rater. 
 * Only the admin can change a user's status.
 */



$getuserid = "SELECT UserID, Password FROM user WHERE UserName ='".$username."'";

$result1 = mysqli_query($cxn,$getuserid);
$row_cnt = mysqli_num_rows($result1);

if ($row_cnt < 1)
{
	//create user record
	$createnewuser = "INSERT INTO user (UserName,Password,IsAdmin, CreateTime,LastLoginTime,IsActive)
			VALUES ('".$username."','$passwd',0, CURDATE(),CURDATE(),1)";
	$newuser = mysqli_query($cxn,$createnewuser);


	if (!$newuser)
	{
		echo "<table border=\"0\" width=\"95%\">";
		echo "<tr>";
		echo "<td colspan=\"2\" width=\"95%\" align=\"center\">";
		echo "We were unable to create your user record. <br />Please contact the system administrator.";
		echo "</td>";
		echo "</tr>";
		echo "</table>";


	}
	else
	{
		$newuserid = "SELECT UserID FROM user WHERE username = '".$username."'";
		$result = mysqli_query($cxn,$newuserid);
		$row = mysqli_fetch_row($result);
		$userid = $row[0];

		echo "<table border=\"0\" width=\"95%\">";
		echo "<tr>";
		echo "<td colspan=\"2\" width=\"95%\" align=\"center\">";
		echo "Your user record has been created.";
		echo "</td>";
		echo "</tr>";
		echo "</table>";
	}
}
else
{
	$row1 = mysqli_fetch_row($result1);
	$userid = $row1[0];
    $pwd = $row1[1];

    if ($f_password != $pwd)    // if user doesn't know their password, offer supervised login
    {
        header('Location: supervised_login.php');
        exit;
    }

	//update existing user record
	$updateuser = "UPDATE user SET LastLoginTime=CURDATE(), IsActive=1 WHERE UserID = '".$userid."'";
	$update = mysqli_query($cxn,$updateuser);
		echo "<h4>".mysqli_error($cxn)."</h4>";
	$getstatus = "SELECT IsAdmin, IsAdjudicator FROM user WHERE UserID = '".$userid."'";
	$status = mysqli_query($cxn,$getstatus)
		or die(mysqli_error($cxn));
	$temp = mysqli_fetch_row($status);
	$isadmin = $temp[0];
	$isadj = $temp[1];

		echo "<table border=\"0\" width=\"95%\">";
		echo "<tr>";
		echo "<td colspan=\"2\" width=\"95%\" align=\"center\">";
		echo "Welcome back.";
		echo "</td>";
		echo "</tr>";

}

/* If IsAdmin is 0, we send
 * the user to the student info page.
 * Otherwise, if IsAdmin is 1,
 * we send the user to the admin page.
 * If the user is an adjudicator, we
 * send them to the adjudication page.
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
	if ($isadmin == 0 && $isadj == 0)
	{
		echo "<form method=\"POST\" action=\"student_info.php\">";
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

	}


	elseif ($isadmin == 1)
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



	}

	elseif ($isadj == 1)
	{
		echo "<form method=\"POST\" action=\"adjudicator.php\">";

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



	}

	echo "</form>";


}




echo "</table>";


?>
