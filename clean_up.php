<?php
require 'utilities.php'; session_start();

if (isset($_SESSION['ratingsession']))
{
	$sessionid = $_SESSION['ratingsession'];

}
else
{
	$sessionid = $_POST['session'];

	$_SESSION['ratingsession'] = $sessionid;
}


$userid = $_SESSION['thisuser'];

$studentid = $_SESSION['currentstudent'];



?>

<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/htm14/loose.dtd">


<html>

<head>
<meta http-equiv="Content-Type" content="text/html; charset-iso-8859-1">
<link rel="stylesheet" type="text/css" href="mystyles.css" />
<title>Select Rater Action</title>
</head>

<body>
<table border="0" width="95%">
  <tr>
    <td colspan="2" width="95%" align="left"
     type="text/css">
     <img src="njit_tag.jpg" width="233" height="60" border="0" />
     </td>
  </tr>
  <tr>
    <td width="95%" colspan="2"align="center">
        <h1>Cleaning the Data for the Previously Selected Student</h1>
    </td>
  </tr>
</table>

<?php


$cxn = connect_to_db();


	$checkratingcount = "SELECT PortfolioRatingID FROM portfoliorating 
				WHERE RatingSessionID = '".$sessionid."'
				AND studentID = '".$studentid."' AND UserID = '".$userid."'";
	$docheckrating = mysqli_query($cxn,$checkratingcount)
				or die(mysqli_error($cxn));
	$getid = mysqli_fetch_row($docheckrating);

	$portfolioratingid = $getid[0];


		$getoldcount = "SELECT RatingCount FROM studenttoberated WHERE
				StudentID = '".$studentid."'
				AND RatingSessionID = '".$sessionid."'";
		$dogetoldcount = mysqli_query($cxn,$getoldcount)
				or die(mysqli_error($cxn));
		$ratingcountresult = mysqli_fetch_row($dogetoldcount);
		$oldratingcount = $ratingcountresult[0];
		
			

		$oldratingcount = $oldratingcount - 1;
		$fixratingcount = "UPDATE studenttoberated SET RatingCount = '".$oldratingcount."'
				WHERE StudentID = '".$studentid."' AND
				RatingSessionID = '".$sessionid."'";
		$dofixcount = mysqli_query($cxn,$fixratingcount)
				or die(mysqli_error($cxn));
		

				
	$delincrating = "DELETE FROM portfoliorating WHERE PortfolioRatingID = '".$portfolioratingid."'";
	$dodelincrating = mysqli_query($cxn,$delincrating)
			or die(mysqli_error($cxn));
			

	


echo "<table width=\"95%\" height = \"50\">";
echo "<tr>";
echo "<td colspan=\"2\" align=\"center\">";
echo "<form method=\"POST\" action=\"student_info.php\">";
echo "<input type=\"submit\" value=\"Go back and select another student to rate\">";
echo "</td>";
echo "</form>";	
echo "</tr>";
echo "</table>";
	




?>


</body>
</html>
