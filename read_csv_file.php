<?php
require_once 'utilities.php'; session_start();
$createdsessionid = $_SESSION['newsessionid'];
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/htm14/loose.dtd">
<html>

<head>
  <meta http-equiv="Content-Type" content="text/html; charset-iso-8859-1">
  <link rel="stylesheet" type="text/css" href="mystyles.css" />

<title>Insert New Student Names</title>
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
    <h1>Save Adjudicated Scores</h1>
    </td>
  </tr>
</table>

<?php



$cxn = connect_to_db();
     
$filename = $_FILES['sel_file']['tmp_name'];
$handle = fopen($filename, "r");
    
while (($data = fgetcsv($handle, 1000, ",")) !== FALSE)
{
 
	$first = addslashes($data[2]);
	$last = addslashes($data[3]);

	$checkstudent = "SELECT studentid FROM student WHERE njitstudentid = '".$data[0]."'";
	$docheckstudent = mysqli_query($cxn,$checkstudent) or die(mysqli_error($cxn));
        $existing = mysqli_num_rows($docheckstudent);

	if ($existing == 0)
        {
	
           $sql = "INSERT INTO student(njitstudentid,section,firstname,lastname,url,CreateTime) 
		VALUES('$data[0]','$data[1]','$first','$last','$data[5]',NOW())";
            $dosql = mysqli_query($cxn,$sql) or die(mysqli_error($cxn));

	   $getid = "SELECT studentid FROM student
			WHERE njitstudentid = '".$data[0]."'
			AND DATE(CreateTime) = CURDATE()";
	   $dogetid = mysqli_query($cxn,$getid) or die(mysqli_error($cxn));
	   $idresult = mysqli_fetch_row($dogetid);
	   $studentid = $idresult[0];

	   $setratings = "INSERT INTO studenttoberated(studentid,ratingsessionid,numratings,ratingcount,AdjReqd,AdjDone)
			VALUES('$studentid','$createdsessionid','$data[4]',0,0,0)";
	   $dosetratings = mysqli_query($cxn,$setratings) or die(mysqli_error($cxn));
	}

	else
	{
	   $getid = mysqli_fetch_row($docheckstudent);
	   $existingid = $getid[0];

	   $updateurl = "UPDATE student SET url='".$data[5]."', LastUpdateTime = NOW() 
			WHERE studentid = '".$existingid."'";
           $doupdateurl = mysqli_query($cxn,$updateurl) or die(mysqli_error($cxn));

	   $setratings = "INSERT INTO studenttoberated(studentid,ratingsessionid,numratings,ratingcount,AdjReqd,AdjDone)
			VALUES('$existingid','$createdsessionid','$data[4]',0,0,0)";
	   $dosetratings = mysqli_query($cxn,$setratings) or die(mysqli_error($cxn));
        }
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
