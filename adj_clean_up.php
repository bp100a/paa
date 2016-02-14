<?php
require 'utilities.php';
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

	
			

	$checkadjstat = "SELECT AdjDone FROM studenttoberated WHERE studentid = '".$studentid."'
				AND RatingSessionID = '".$sessionid."'";
	$docheckstat = mysqli_query($cxn,$checkadjstat)
				or die(mysqli_error($cxn));

	$getstat = mysqli_fetch_row($docheckstat);
	$adjstat = $getstat[0];

	if($adjstat == 1)
	{
		$newadjstat = 0;
		$fixadjstat = "UPDATE studenttoberated SET AdjDone = '".$newadjstat."'
				WHERE studentid = '".$studentid."'
				AND RatingSessionID = '".$sessionid."'";
		$dofixstat = mysqli_query($cxn,$fixadjstat)
				or die(mysqli_error($cxn));
	}

	


	$checkratecnt = "SELECT RatingCount FROM studenttoberated WHERE studentid = '".$studentid."'
				AND RatingSessionID = '".$sessionid."'";
	$docheckcnt = mysqli_query($cxn,$checkratecnt)
				or die(mysqli_error($cxn));

	$getcnt = mysqli_fetch_row($docheckcnt);
	$ratecnt = $getcnt[0];

	if($ratecnt == 3)
	{
		$newratecnt = 2;
		$fixcnt = "UPDATE studenttoberated SET RatingCount = '".$newratecnt."'
				WHERE studentid = '".$studentid."'
				AND RatingSessionID = '".$sessionid."'";
		$dofixcnt = mysqli_query($cxn,$fixcnt)
				or die(mysqli_error($cxn));
	}




echo "<table width=\"95%\" height = \"50\">";
echo "<tr>";
echo "<td colspan=\"2\" align=\"center\">";
echo "<form method=\"POST\" action=\"student_info.php\">";
echo "<input type=\"submit\" value=\"Go back and select another student to adjudicate\">";
echo "</td>";
echo "</form>";	
echo "</tr>";
echo "</table>";
	




?>


</body>
</html>
