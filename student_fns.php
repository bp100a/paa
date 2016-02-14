<?php
require 'utilities.php'; session_start();

function check_NJITid($NJITId)
{

	$string_to_be_stripped = $NJITid;
	$NJITid = ereg_replace("[^A-Za-z0-9]", "", $string_to_be_stripped);

	return $NJITid;
}



?>

	