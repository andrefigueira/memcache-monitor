<?php

session_start();

set_error_handler('error_handler', E_ALL);

date_default_timezone_set('Europe/London');

define('BASE_URL', 'https://staging.hollatme.com/dashboard/memcache-monitor/');
define('VERSION', '0.1');
define('DEBUG', true);

//User details for access to the service
define('USERNAME', 'admin');
define('PASSWORD', 'password');

//Memcache details
define('MC_HOST', '127.0.0.1');
define('MC_PORT', '11211');

if(DEBUG == true)
{

	error_reporting(E_ALL);
	ini_set('display_errors', 1);

}

?>