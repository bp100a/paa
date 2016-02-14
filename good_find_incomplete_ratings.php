<?php
require_once 'utilities.php'; session_start();

$sessionid = $_SESSION['ratingsession'];
$rubricid = $_SESSION['rubricid'];
$userid = $_SESSION['thisuser'];



$cxn = connect_to_db();
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/htm14/loose.dtd">
<html>

<head>
  <meta http-equiv="Content-Type" content="text/html; charset-iso-8859-1">
  <link rel="stylesheet" type="text/css" href="mystyles.css" />

<title>Find Incomplete Ratings</title>
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
    <h1>Check for Students With Incomplete Ratings</h1>
    </td>
  </tr>
</table>

<?php


/* This module lets the admin see
 * which students have only been rated
 * by one rater.
 */



/* First we will look for students who
 * only have one portfoliorating. That
 * means they need to be rated by a second rater.
 */


$checknumratings = "SELECT StudentID, UserID FROM portfoliorating WHERE RatingSessionID = '".$sessionid."' GROUP BY StudentID HAVING (COUNT(StudentID) < 2)";
$dochecknumratings = mysqli_query($cxn,$checknumratings)
		or die(mysqli_error($cxn));
$num_students = mysqli_num_rows($dochecknumratings);

if ($num_students < 1)
{
	echo "<table border=\"0\" width=\"95%\">";
	echo "<tr>";
	echo "<td colspan=\"2\" width=\"95%\" align=\"center\">";
	echo "There are no students still requiring rating.<br />
		Please check back again later.";
	echo "</td>";
	echo "</tr>";
	echo "<tr>";
	echo "<td colspan=\"2\" width=\"95%\" align=\"center\">";
	echo "<form method=\"POST\" action=\"admin.php\">";
	echo "<input type=\"submit\" value\"Return to Administrator Function page\">";
	echo "</td>";
	echo "</tr>";

	echo "</table>";
}	
else
{
	echo "<table border=\"1\" width=\"85%\">";

	echo "<tr>";
	echo "<td class=\"header\" width=\"35%\" align=\"left\">";
	echo "Student Requiring Second Rating";
	echo "</td>";
	echo "<td class=\"header\" width=\"15%\" align=\"left\">";
	echo "NJIT Student ID";
	echo "</td>";
	echo "<td class=\"header\" width=\"35%\" align=\"left\">";
	echo "Rater 1";
	echo "</td>";
	echo "</tr>";

	while ($studentsneedingrating = mysqli_fetch_row($dochecknumratings))
	{
		$student = $studentsneedingrating[0];
		$raterid = $studentsneedingrating[1];


		/* Now that we know which student requires rating,
		 * we need to get the student's name to display to the admin.
		 */

		$getstudentname = "SELECT FirstName, LastName, NJITStudentID FROM student 
			WHERE StudentID = '".$student."'";
		$dogetname = mysqli_query($cxn,$getstudentname);
		$result1 = mysqli_fetch_row($dogetname);
		$firstname = $result1[0];
		$lastname = $result1[1];
		$njitid = $result1[2];



		$getratername = "SELECT UserName FROM user WHERE UserID = '".$raterid."'";
		$doratername = mysqli_query($cxn,$getratername);
		$result7 = mysqli_fetch_row($doratername);
		$ratername = $result7[0];


		echo "<tr>";
		echo "<td width=\"35%\" align=\"left\">".$firstname." ".$lastname."</td>";
		echo "<td width=\"15%\" align=\"left\">".$njitid."</td>";
		echo "<td width=\"35%\" align=\"left\">".$ratername."</td>";
		echo "</tr>";
	}
	echo "</table>";


echo "<table border=\"0\" width=\"95%\" cellpadding=\"20\">";
echo "  <tr>";
echo "  <form method=\"POST\" action=\"select_report.php\">";
echo "    <td width=\"30%\" height=\"50\" align=\"right\">";
echo "    <input type=\"submit\" value=\"Select Report\">";
echo "    </td>";
echo "    <td width=\"55%\" align=\"left\" type=\"text/css\"
     style=\"background-color: #669966; color: white; font-weight: bolder;\">";
echo "    Monitor the progress of the rating session by viewing various reports";
echo "    </td>";
echo "    <td width=\"10%\">";
echo "    </td>";
echo "    </form>";
echo "  </tr>";
echo "  <tr>";
echo "    <form method=\"POST\" action=\"assign_adjudicator.php\">";
echo "    <td width=\"30%\" align=\"right\" height=\"50\">";
echo "    <input type=\"submit\" value=\"Assign Adjudicator\">";
echo "    </td>";
echo "    <td width=\"55%\" align=\"left\" type=\"text/css\"
     style=\"background-color: #669966; color: white; font-weight: bolder;\">";
echo "     View the records that need adjudication and assign them to raters - do this periodically throughout the rating session";
echo "   </td>";
echo "    <td width=\"10%\">";
echo "    </td>";
echo "    </form>";
echo "  </tr>";

echo "  <tr>";
echo "    <form method=\"POST\" action=\"create_scores_table.php\">";
echo "    <td width=\"30%\" height=\"50\" align=\"right\">";
echo "    <input type=\"submit\" value=\"Save Data">";
</td>";
echo "    <td width=\"55%\" align=\"left\" type=\"text/css\"
     style=\"background-color: #669966; color: white; font-weight: bolder;\">";
echo "     Once the rating session is complete, save the data to a file for analysis.</p>";
echo "    </td>";
echo "    <td width=\"10%\">";
echo "    </td>";
echo "  </tr>";
echo "  </form>";
echo " <tr>";
echo "    <form method=\"POST\" action=\"logout.php\">";
echo "    <td width=\"30%\" height=\"50\" align=\"right\">";
echo "    <input type=\"submit\" value=\"Logout\">";
echo "    </td>";
echo "    <td width=\"55%\" align=\"left\" type=\"text/css\"
     style=\"background-color: #669966; color: white; font-weight: bolder;\">";
echo "     Log out of the application";
echo "    </td>";
echo "    <td width=\"10%\">";
echo "    </td>";
echo "    </form>";
echo "  </tr>";

echo "</table>";






	
}
?>



</body>
</html>