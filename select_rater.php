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
<title>Select Rater for Reporting</title>



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
      <h1>Select Rater for Reporting</h1>
    </td>
  </tr>
</table>


    <form method="POST" action="show_rater_scores.php">
<table border="1" width="95%">
  <tr>
    <td width="40%" align="right" valign="center">
     Rater Name: 
    </td>
    <td width="55%" align="left" valign="center">
    <input type="text" name="ratername" />
    * This field is required to uniquely identify the student.
    </td> 
  </tr>
 
   <tr>
    <td width="40%">
    </td>
    <td width="55%" align="left" valign="center">
    <input type="submit" value="Show Rater Scores"/>
    </td>
  </tr>
  </form>

 
</table>


  
</body>
</html>