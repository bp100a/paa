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
<title>Import Student Rating Information</title>
</head>

<body>
<table border="0" width="95%">
  <tr>
    <td colspan="2" width="95%" align="left"
     type="text/css">
     <img src="njit_tag.jpg" width="233" height="60" border="0" />
     </td>
  </tr>
</table>



<table border="0" width="95%">
  <tr>
    <td width="95%" colspan="2" align="center">
        <h1>Select A CSV Input File to Import Student Rating Information</h1>
    </td>
  </tr>
</table>

<?php


$cxn = connect_to_db();
?>

<table border="0" width="95%">
  <tr>
    <td width="95%" colspan="2" align="center">
	<form method="POST" action="read_rating_list_file.php" enctype="multipart/form-data">

    Import File : <input type='file' name='sel_file' size='30'>
    </td>
  </tr>
  <tr>
    <td width="95%" colspan="2" align="center">
    <input type='submit' name='submit' value='Submit'>
    </td>
  </tr>
</table>

</form>




</body>
</html>
