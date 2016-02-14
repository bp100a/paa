<?php
require 'utilities.php'; session_start();

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

<title>Select Report Type</title>
</head>
<body>

<table border="0" width="95%" cellpadding="20">
  <tr>
     <td colspan="3" width="95%" align="left" valign="bottom"
      type="text/css"
      <img src="njit_tag.jpg" width="233" height="60" border="0" />
     </td>  
  </tr>
</table>


<table border="0" width="95%" cellspacing="20">
  <tr>
    <td colspan="3" align="center" height="100" valign="center">
      <h1>Select Report Type</h1>
    </td>
  </tr>
   <tr>
    <form method="POST" action="report_by_student.php">
    <td width="30%" align="right" height="50">
	<input type="submit" value="View Scores by Student">
    </td>
    <td width="55%" align="left" type="text/css"
     style="background-color: #669966; color: white; font-weight: bolder;">
	View the scores that have
	been given to a particular student during this rating session.
    </td>
    <td width="10%">
    </td>
  </form>
  </tr>
  <tr>
    <form method="POST" action="report_by_rater.php">
    <td width="30%" align="right" height="50">
	<input type="submit" value="View Scores By Rater">
    </td>
    <td width="55%" align="left" type="text/css"
     style="background-color: #669966; color: white; font-weight: bolder;">
     View the scores assigned by a
	particular rater during this rating session.
    </td>
    <td width="10%">
    </td>
    </form>
  </tr>
  <tr>
    <form method="POST" action="report_by_variable.php">
    <td width="30%" align="right" height="50">
	<input type="submit" value="View Scores by Variable">
    </td>
    <td width="55%" align="left" type="text/css"
     style="background-color: #669966; color: white; font-weight: bolder;">
     View the distribution of 
	scores for any particular competency in this rubric.</p>
    </td>
    <td width="10%">
    </td>
    </form>
  </tr>


</table>

</body>
</html>