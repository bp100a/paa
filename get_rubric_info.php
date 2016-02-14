<?php

require 'utilities.php'; session_start();

function get_rubric_name()



$cxn = connect_to_db();


$query = "SELECT * FROM rubric";

$result = mysql_query($cxn,$query);
echo "<p>$cxn</p>";

if (!$result) die ("Database access failed: " . mysql_error());

$rows = mysql_num_rows($result);
echo "<p>$rows</p>";




while($row = mysqli_fetch_assoc($result))
{
	extract ($row);
	echo "<p>{$row['RubricName']}</p>";

}

mysql_close();	

?>
