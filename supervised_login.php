<?php
require_once 'utilities.php'; /* consolidate DB connection information */
session_start();
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
<form method=POST action=register.php>
        <table border="0" width="95%">
            <tr>
                <td colspan="2" width="95%" valign="bottom" align="left"
                    type="text/css">
                    <img src="njit_tag.jpg" width="233" height="60" border="0" />
                </td>
            </tr>
            <tr>
                <td colspan="2" width="95%" align="center" valign="middle">
                    <h1><b>Supervised</b> Portfolio Assessment Login</h1>
                </td>
            </tr>
            <tr>
                <td colspan="2" width="95%" align="center" valign="middle" height="200"></td>
            </tr>
            <tr>
                <td>
                    <h2>Please have an administrator login </h2>
                </td>
            </tr>

            <?php
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
     echo "Admin Username:";
    echo "</td>";
    echo "<td width=\"48%\" align=\"left\" valign=\"center\">";
    echo "<input type=\"text\" name=\"username\" value=\"".$username."\">";
    echo "</td>";
    unset($_SESSION['username']);
    echo "<input type=\"text\" name=\"pwd\" value=\"".$pwd."\">";
    echo "</td>";
    unset($_SESSION['pwd']);
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
    echo "<tr>";
	echo "    <td width=\"37%\" align=\"right\" valign=\"center\">";
    echo " Password:";
    echo "</td>";
    echo "<td width=\"48%\" align=\"left\" valign=\"center\">";
    echo "<input type=\"password\" name=\"pwd\" />";
    echo "</td>";
}
            ?>

            </tr>
            <tr>
                <td width="37%"></td>
                <td width="48%" align="left" valign="center">
                    <input type="submit" value="Submit" />
                </td>
            </tr>

        </table>
    </form>


  
</body>
</html>