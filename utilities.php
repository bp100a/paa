<?php

require_once('configuration.php');

function connect_to_db() {
	$host = Configuration::DB_HOST;
    $user = Configuration::DB_USERNAME;
	$password = Configuration::DB_PASSWORD;
	$dbase = Configuration::DB_NAME;

	$con = mysqli_connect ($host,$user,$password,$dbase)
			or die ("couldn't connect to database");

	return $con;
}

?>