<?php

require_once 'utilities.php'; session_start();

function filled_out($form_vars)
{
	// test that each variable has a value

	foreach ($form_vars as $key => $value)
	{
		if ((!isset($key)) || ($value == ''))
		{
			return false;
		}
	}
	return true;
}

function valid_email($name)
{
	// check that the e-mail address is valid
	if (strstr($name,"@njit.edu") || (strstr($name, "@adm.njit.edu")))	
	{
		return true;
	}
	else
	{
		return false;
	}
}
	





?>

	