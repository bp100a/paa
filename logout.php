<?php
require 'utilities.php'; session_start();
$userid = $_SESSION['thisuser'];

$cxn = connect_to_db();

/* I'm commenting out htese lines of code
 * because if the work is done asynchronously
 * we need to be able to assign users as adjudicators
 * even when they're currently inactive.
 * 
 *  $setinactive = "UPDATE user SET IsActive = 0 WHERE UserID = '".$userid."'";
 *  $dosetinactive = mysqli_query($cxn,$setinactive);
 */

?>


<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/htm14/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset-iso-8859-1">
<link rel="stylesheet" type="text/css" href="mystyles.css" />
<title>Logged Out</title>



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
     <td colspan="2" width="95%" align="center">
       <h1>Log Out</h1>
    </td>
  </tr>
</table>

<table border="0" width="95%">
  <tr>
    <td colspan="2" width="95%" height="200" align="center" valign="center">
     <p>You are now logged out of the rating session.</p>
    </td>
  </tr>
</table>

<table border="0" width="95%">
  <tr>
    <td width="25%">
    </td>
    <td width="45%" height="150" align="center" valign="center" type="text/css"
     style="background-color: #669966; color: white; font-weight: bolder;">
        <p>We thank you for your time and efforts on behalf of<br />
        the students and the writing department.</p>
    </td>
    <td width="25%">
    </td>
  </tr>
</table>

<?php

session_unset();
session_destroy();

?>

  
</body>
</html>