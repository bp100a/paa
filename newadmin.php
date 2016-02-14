<?php
require 'utilities.php'; session_start();

$userid = $_SESSION['thisuser'];
$rubricid = $_SESSION['rubricid'];
$sessionid = $_SESSION['ratingsession'];





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
     <td width="10%" align="center">
    </td>
    <td width="65%" align="center" valign="center">
      <h1>Administrator Functions</h1>
    </td>
    <td width="20%">
    </td>
  </tr>
  <tr>
    <td colspan="2" width="95%" height="30">
    </td>
  </tr>
</table>

<?php

$cxn = connect_to_db();
$getsessionadmin = "SELECT SessionAdmin FROM ratingsession WHERE ratingsessionid = '".$sessionid."'";
$dogetadmin = mysqli_query($cxn,$getsessionadmin);
$setsessionadmin = mysqli_fetch_row($dogetadmin);
$sessionadmin = $setsessionadmin[0];

$_SESSION['sessionadmin'] = $sessionadmin;

?>


<table border="0" width="95%" cellpadding="15">
  <form method="POST" action="admin_choice.php">
  <tr>
    <td width="10%" align="center">
    </td>
    <td width="75%" align="left" type="text/css"
     style="background-color: #669966; color: white; font-weight: bolder;">
     <input type="radio" name="selection" value="begin">Set Parameters for Assessment<p>This must be done before any       raters begin rating students<br />
     and can only be done once per rating session.</p>
    </td>
    <td width="10%">
    </td>
  </tr>
  <tr>
    <td width="10%" align="center">
    </td>
    <td width="75%" align="left" type="text/css"
     style="background-color: #669966; color: white; font-weight: bolder;">
     <input type="radio" name="selection" value="reports">View Reports<p>You can monitor the progress of the          rating session by viewing various reports.</p>
    </td>
    <td width="10%">
    </td>
  </tr>

  <tr>
    <td width="10%" align="center">
    </td>
    <td width="75%" align="left" type="text/css"
     style="background-color: #669966; color: white; font-weight: bolder;">
     <input type="radio" name="selection" value="ratings">Search for Incomplete Ratings<br />You can identify the students who have<br /> only been rated by one rater. This should be done towards the <br />end of the rating session to ensure that all students have been rated twice.</p>
    </td>
    <td width="10%">
    </td>
  </tr>
  <tr>
    <td width="10%" align="center">
    </td>
    <td width="75%" align="left" type="text/css"
     style="background-color: #669966; color: white; font-weight: bolder;">
     <input type="radio" name="selection" value="rate">Rate a Student<p>In addition to performing administrative functions,<br />
you can choose to rate students during the rating session.</p>
    </td>
    <td width="10%">
    </td>
  </tr>

<?php

if ($sessionadmin == $userid)
{

echo "  <tr>";
echo "    <td width=\"10%\" align=\"center\">";
echo "    </td>";
echo "    <td width=\"75%\" align=\"left\" type=\"text/css\"
     style=\"background-color: #669966; color: white; font-weight: bolder;\">";
echo "     <input type=\"radio\" name=\"selection\" value=\"adjudicate\">Assign Adjudicator<p>You can check for students who
    need adjudication and<br /> assign them to a rater. This should be done periodically<br /> throughout the rating session.</p>";
echo "    </td>";
echo "    <td width=\"10%\">";
echo "    </td>";
echo "  </tr>";
echo "  <tr>";
echo "    <td width=\"10%\" align=\"center\">";
echo "    </td>";
echo "    <td width=\"75%\" align=\"left\" type=\"text/css\"
     style=\"background-color: #669966; color: white; font-weight: bolder;\">";
echo "     <input type=\"radio\" name=\"selection\" value=\"export\">Export Data<p>Once the rating session is complete, you
    must <br />export the data to a file for analysis.</p>";
echo "    </td>";
echo "    <td width=\"10%\">";
echo "    </td>";
echo "  </tr>";
echo "  <tr>";
echo "    <td width=\"10%\" align=\"center\">";
echo "    </td>";
echo "    <td width=\"75%\" align=\"left\" type=\"text/css\"
     style=\"background-color: #669966; color: white; font-weight: bolder;\">";
echo "     <input type=\"radio\" name=\"selection\" value=\"logout\">Logout</td>";
echo "    <td width=\"10%\">";
echo "    </td>";
echo "  </tr>";
echo "  <tr>";
echo "    <td width=\"10%\" align=\"center\">";
echo "    </td>";
echo "    <td width = \"75%\" align=\"center\">";
echo "    <input type=\"submit\" value=\"Submit\">";
echo "    </td>";
echo "    <td width=\"10%\">";
echo "  </tr>";
}
?>

</table>


</form>
</body>
</html>