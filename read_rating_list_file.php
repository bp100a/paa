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

<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/htm14/loose.dtd">
<html>

<head>
  <meta http-equiv="Content-Type" content="text/html; charset-iso-8859-1">
  <link rel="stylesheet" type="text/css" href="mystyles.css" />

<title>Insert Rating Information</title>
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
    <h1>Insert Rating Information</h1>
    </td>
  </tr>
</table>

<?php

$cxn = connect_to_db();
     
         $filename = $_FILES['sel_file']['tmp_name'];
         $handle = fopen($filename, "r");
    
         while (($data = fgetcsv($handle, 1000, ",")) !== FALSE)
         {
 
 	   $findstudent = "SELECT studentid FROM student
			WHERE njitstudentid = '$data[0]'";
	   $dofindstudent = mysqli_query($cxn,$findstudent)
		or die("Couldn't find student to be rated in student list");
	   $getstudent = mysqli_fetch_row($dofindstudent);
	   $studentid = $getstudent[0];

	   $createrating = "INSERT INTO studenttoberated(studentid,ratingsessionid,numratings)
			VALUES('$studentid','$data[1]','$data[2]')";
           $docreaterating = mysqli_query($cxn,$createrating) or die(mysqli_error($cxn));
        

         }
    
         fclose($handle);

	echo "<table border=\"0\" width=\"95%\">";
	echo "<tr>";
	echo "<td colspan=\"2\" width=\"95%\" align=\"center\">";
         echo "Successfully Imported";
	echo "</td>";
	echo "</tr>";
	echo "</table>";


?>

<table border="0" width="95%">
<form method="POST" action="admin.php">
<tr>
<td colspan="2" width="95%" align="center">
<input type="submit" value="Return to main Administrator page">
</td>
</tr>
</table>
</form>
</body>
</html>
