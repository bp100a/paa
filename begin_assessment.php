<?php
require_once 'utilities.php'; session_start();
?>



<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/htm14/loose.dtd">
<?php
require_once('rubric_fns.php');
?>


<html>

<head>
<meta http-equiv="Content-Type" content="text/html; charset-iso-8859-1">
<link rel="stylesheet" type="text/css" href="mystyles.css" />

<title>Begin Assessment</title>

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
      <h1>Begin Assessment</h1>
    </td>
  </tr>
  <tr>
    <td colspan="2" width="95%" height="20">
    </td>
  </tr>
  <tr>
    <td colspan="2" width="95%" align="center" valign="center" height="75">
	When you begin a session, the application creates a unique <br />
	rating session identifier for this portfolio rating event.
	<p>
	The rubric you select will be used throughout the entirety of<br />
	this rating session and will also be associated with<br />
	this rating session in the database.
	</p>
    </td>
  </tr> 
</table>

<?php
	$rubric = select_rubric();
	
?>

</body>
</html>
