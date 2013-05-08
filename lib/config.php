<?php

session_start();

set_error_handler('error_handler', E_ALL);

define('BASE_URL', 'https://staging.hollatme.com/dashboard/memcache-monitor/');
define('VERSION', '0.1');
define('DEBUG', true);

//User details for access to the service
define('USERNAME', 'admin');
define('PASSWORD', 'password');

if(DEBUG == true)
{

	error_reporting(E_ALL);
	ini_set('display_errors', 1);

}

?>