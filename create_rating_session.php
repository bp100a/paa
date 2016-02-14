<?php
require 'utilities.php'; session_start();

$userid = $_SESSION['thisuser'];

$cxn = connect_to_db();
$user = "root";
$password = "gewbgttl";
$dbase = "mydb";

$cxn = mysqli_connect ($host,$user,$password,$dbase)
	or die ("couldn't connect to database");

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/htm14/loose.dtd">
<html>

<head>
  <meta http-equiv="Content-Type" content="text/html; charset-iso-8859-1">
  <link rel="stylesheet" type="text/css" href="mystyles.css" />
<title>Create Rating Session</title>
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
     <td colspan="3" width="95%" align="center" valign="center" height="50">
       <h1>Create Rating Session</h1>
    </td>
  </tr>
</table>


<?php



/* This module retrieves the
 * data input by the administrator
 * in the Begin_assessment
 * page and handled by the
 * select_rubric function
 * in the rubric_fns file.
 */

require_once('rubric_fns.php');

$sessionname = $_POST['SessionName'];
$rubricid = $_POST['option1'];
$threshold = $_POST['Threshold'];



/* The rubric value, session name
 * and threshold value are passed
 * from the rubric_fns module
 * Here we create the rating session
 * for this particular portfolio
 * rating event.
 */

$cxn = connect_to_db();
$user = "root";
$password = "gewbgttl";
$dbase = "mydb";

$cxn = mysqli_connect ($host,$user,$password,$dbase)
		or die ("couldn't connect to database");

$writesessioninfo = "INSERT INTO ratingsession(RubricID,RatingDate,SessionName, Threshold) 
			VALUES ('$rubricid', CURDATE(), '$sessionname', '$threshold')";
$sessioninfo = mysqli_query($cxn,$writesessioninfo);

$getsessionid = "SELECT MAX(RatingSessionID) FROM ratingsession";
$dogetsessionid = mysqli_query($cxn,$getsessionid);
$sessionresult = mysqli_fetch_row($dogetsessionid);
$sessionid = $sessionresult[0];


$_SESSION['newsession'] = $sessionid;
$_SESSION['rubricid'] = $rubricid;

echo "<table border=\"0\" width=\"95%\">";

if ($sessioninfo == false)
{
	echo "  <tr>";
	echo "    <td colspan=\"2\" width=\"95%\" height=\"50\" align=\"center\" valign=\"center\">";
	echo "<h4>".mysqli_error($cxn)."</h4>";
	echo "    </td>";
	echo "  </tr>";
}
elseif ($sessioninfo == true)
{

	echo "  <tr>";
	echo "    <td colspan=\"2\" width=\"95%\" height=\"50\" align=\"center\" valign=\"center\">";
	echo "Rating Session successfully created.<br />
		The Rating Session number is '".$sessionid."'";
	echo "    </td>";
	echo "  </tr>";
}
echo "  <tr>";
echo "    <td colspan=\"2\" width=\"95%\" height=\"30\" align=\"center\" valign=\"center\">";    
echo "     Would you like to add students to this rating session now?";
echo "    </td>";
echo "  </tr>";
echo "</table>";
?>



<table border="0" width="95%" cellpadding="20">
  <tr>
    <td colspan="3" height="20">
    </td>
  </tr>
  <tr>
    <form method="POST" action="add_new_student.php">
    <td width="40%" align="right">
    <input type="submit" name="add_student" value="Yes">
    </form>
    </td>
    <td width="20%">
    </td>
    <form method="POST" action="join_session.php">
    <td width="40%" align="left">
    <input type="submit" name="not_now" value="No">
    </form>
    </td>
  </tr>
</table>




</form>
</body>
</html>