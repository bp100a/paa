<?php

require 'utilities.php'; /* consolidate DB connection information */

require 'utilities.php'; session_start();
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/htm14/loose.dtd">
<html>




<head>
<meta http-equiv="Content-Type" content="text/html; charset-iso-8859-1">
<link rel="stylesheet" type="text/css" href="mystyles.css" />
<title>Login Page</title>


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
      <h1>Portfolio Assessment Login</h1>
    </td>
  </tr>
  <tr>
    <td colspan="2" width="95%" align="center" valign="center" height="200">
	
    </td>
  </tr>


<?php


echo "    <form method=\"POST\" action=\"register.php\">";

/* If the session username is set at this point,
 * that means we got bounced back to here from
 * register.php due to an invalid username.
 */

if (isset($_SESSION['username']))
{
	echo "<tr>";
	echo "<td colspan=\"2\" width=\"95%\" align=\"center\" height=\"100\">";
	$username = $_SESSION['username'];
	echo "<h4>There was a problem with your login data.<br />Please check that your username is in the form<br />
		name@njit.edu OR name@adm.njit.edu<br />Also check that you typed your password correctly<br />
		in both password fields.</h4>";
	echo "</td>";
	echo "</tr>";

	echo "  <tr>";

	echo "    <td width=\"37%\" align=\"right\" valign=\"center\">";
     echo "Username:";
    echo "</td>";
    echo "<td width=\"48%\" align=\"left\" valign=\"center\">";
    echo "<input type=\"text\" name=\"username\" value=\"".$username."\">";
    echo "</td>";
    unset($_SESSION['username']);
}
else
{
	echo "<tr>";
	echo "    <td width=\"37%\" align=\"right\" valign=\"center\">";
    echo " Username:";
    echo "</td>";
    echo "<td width=\"48%\" align=\"left\" valign=\"center\">";
    echo "<input type=\"text\" name=\"username\" />";
    echo "</td>";
}
?>

  </tr>
<tr>
    <td width="37%">
    </td>
    <td width="48%" align="left" valign="center">
    <input type="submit" value="Submit"/>
    </td>
  </tr>
  </form>

</table>


  
</body>
</html>