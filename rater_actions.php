<?php
require 'utilities.php'; session_start();

$userid = $_SESSION['thisuser'];
$sessionid = $_SESSION['ratingsession'];
$rubricid = $_SESSION['rubricid'];



$cxn = connect_to_db();

$getadminstatus = "SELECT IsAdmin FROM user WHERE UserID = '".$userid."'";
$dogetadminstatus = mysqli_query($cxn,$getadminstatus)
		or die(mysqli_error($cxn));
$setadminstatus = mysqli_fetch_row($dogetadminstatus);
$adminstatus = $setadminstatus[0];


?>

<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/htm14/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset-iso-8859-1">
<link rel="stylesheet" type="text/css" href="mystyles.css" />
<title>Rater Options</title>



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
      <h1>Select Next Task</h1>
    </td>
  </tr>
  <tr>
    <td colspan="2" width="95%" align="center" valign="center" height="150">
    <p>Your scored rubric has been submitted.</p>
    <p>Please choose from one of the following actions.</p>

    </td>
  </tr>
</table>



<table border="0" width="95%" cellpadding="10">

  <tr>
    <form method="POST" action="student_info.php">
    <td width="30%" align="right" height="50">
     <input type="submit" value="Rate A Student">
    </td>
    <td width="55%" align="left" type="text/css" style="background-color: #669966; 
     color: #FFFFFF; font-weight: bolder;">
     Rate a student or modify the scores for a student you have already rated
    </td>
    <td width="10%">
    </td>
    </form>
  </tr>

<?php


if ($adminstatus == 1)
{
  echo "<tr>";
  echo "<form method=\"POST\" action=\"admin.php\">";
  echo "<td width=\"30%\" align=\"right\" height=\"50\">";
  echo "<input type=\"submit\" value=\"Return to Admin Page\">";
  echo "</td>";
  echo "  <td width=\"55%\" align=\"left\" type=\"text/css\" style=\"background-color: #669966;
     color: #FFFFFF; font-weight: bolder;\">";
  echo "Return to the main Administrator page</td>";
  echo "  <td width=\"10%\">";
  echo "  </td>";
  echo "</form>";
  echo "</tr>";
}

?>

  <tr>
    <form method="POST" action="logout.php">
    <td width="30%" align="right" height="50">
    <input type="submit" value="Logout">
    </td>
    <td width="55%" align="left" type="text/css" style="background-color: #669966; 
     color: #FFFFFF; font-weight: bolder;">
     Log out of the application
    </td>
    <td width="10%">
    </td>
    </form>
  </tr>



</table>




</body>
</html>