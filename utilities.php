<?php
function connect_to_db() {
	$cxn = connect_to_db();
	$user = "root";
	$password = "gewbgttl";
	$dbase = "mydb";

	$cxn = mysqli_connect ($host,$user,$password,$dbase)
			or die ("couldn't connect to database");

	return $cxn;
}

?>