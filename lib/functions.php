<?php

//Configuration file
require_once('config.php');

function request_handler()
{

	$functionName = $_GET['function'];
	
	if(function_exists($functionName))
	{
	
		$function = new ReflectionFunction($functionName);
		echo $function->invoke();
		
	}
	else
	{
		
		echo 'Function doesn\'t exist: '.$functionName;
		
	}

}

function is_authenticated()
{

	if(!isset($_SESSION['authenticated']))
	{
		
		set_notification('You must be logged in to see that...', 'negative-notification');
		header('Location: '.BASE_URL);
		
	}

}

function logout()
{

	unset($_SESSION['authenticated']);
	
	set_notification('Logged out safely', 'positive-notification');
	
	header('Location: '.BASE_URL);

}

function post_var($key, $required = true)
{

	if(isset($_POST[$key]))
	{
		
		if($_POST[$key] == '')
		{
			
			return false;
			
		}
		else
		{
			
			return $_POST[$key];
			
		}
		
	}
	else
	{
		
		return false;
		
	}

}

function login()
{

	if(isset($_POST['submit']))
	{
	
		$user = post_var('user', true);
		$pass = post_var('pass', true);
		$url = '';
		
		if($user && $pass)
		{
			
			if($user == USERNAME && $pass == PASSWORD)
			{
			
				$_SESSION['authenticated'] = true;
				
				$url = 'status-board';
				
			}
			else
			{
				
				set_notification('Your username or password are incorrect...', 'negative-notification');
				
			}
			
		}
		else
		{
				
			set_notification('You didn\'t enter a username or password', 'negative-notification');
			
		}
		
	}
	
	header('Location: '.BASE_URL.$url);

}

function set_notification($message = 'Notifications are nice', $css_classes = '')
{

	$notification = array(
		'message' => $message,
		'css_classes' => $css_classes
	);
	
	$_SESSION['notification'] = serialize($notification);

}

function notification()
{
	
	if(isset($_SESSION['notification']))
	{
		
		$notification = unserialize($_SESSION['notification']);
		
		extract($notification);
		
		$notification = '<div class="notification '.$css_classes.'">'.$message.'</div>';
		
		echo $notification;
		
		unset($_SESSION['notification']);
		
	}
		
}

function error_handler($num, $str, $file, $line, $context)
{

	echo '
	
	<div class="php-error">
	
		<div class="error-container">
			<h1>PHP Error</h1>
			
			<table cellpadding="0" cellspacing="0" border="0" width="100%">
				<tr>
					<td><strong>Type</strong></td>
					<td>'.$str.'</td>
				</tr>
				<tr>
					<td><strong>File</strong></td>
					<td>'.$file.'</td>
				</tr>
				<tr>
					<td><strong>Line</strong></td>
					<td>'.$line.'</td>
				</tr>
			</table>
		</div>
		
		<div class="full-overlay"></div>
	
	</div>
	';

}

function array_to_object($array)
{

	$object = new stdClass();
	
	foreach($array as $key => $value)
	{
	
	    $object->$key = $value;
	    
	}
	
	return $object;

}

function seconds_to_time($input_seconds) 
{

    $secs_in_min = 60;
    $secs_in_hour  = 60 * $secs_in_min;
    $secs_in_day    = 24 * $secs_in_hour;

    //Extract days
    $days = floor($input_seconds / $secs_in_day);

    //Extract hours
    $hour_seconds = $input_seconds % $secs_in_day;
    $hours = floor($hour_seconds / $secs_in_hour);

    //Extract minutes
    $minute_seconds = $hour_seconds % $secs_in_hour;
    $minutes = floor($minute_seconds / $secs_in_min);

    //Extract the remaining seconds
    $remaining_seconds = $minute_seconds % $secs_in_min;
    $seconds = ceil($remaining_seconds);

    $array = array(
        'd' => (int) $days,
        'h' => (int) $hours,
        'm' => (int) $minutes,
        's' => (int) $seconds,
    );
    
    return $array;
    
}

function format_bytes($bytes)
{
	
	if($bytes < 1024)
	{ 
	
		return $bytes.' B';

	}
	elseif($bytes < 1048576)
	{ 
	
		return round($bytes / 1024, 2).' KB';
	
	}
	elseif($bytes < 1073741824)
	{ 
	
		return round($bytes / 1048576, 2).' MB';
	
	}
	elseif($bytes < 1099511627776)
	{ 
	
		return round($bytes / 1073741824, 2).' GB';
	
	}
	else
	{ 
	
		return round($bytes / 1099511627776, 2).' TB';
		
	}

}

function get_statistics()
{
	
	$memcache = new Memcache();
	
	$cache_available = $memcache->connect(MC_HOST, MC_PORT);
		
	$stats_array = $memcache->getStats();
	$stats = array_to_object($stats_array);
	
	$uptime = seconds_to_time($stats->uptime);
	$uptime_str = $uptime['d'].'d '.$uptime['h'].'h '.$uptime['m'].'m '.$uptime['s'].'s';
	$cache_size = format_bytes($stats->limit_maxbytes);
	$cache_used = format_bytes($stats->bytes);
	
	$stats->uptime_str = $uptime_str;
	$stats->cache_size = $cache_size;
	$stats->cache_used = $cache_used;
	$stats->time = date('d-m-Y H:i:s', $stats->time);
	
	header('Content-Type: application/json');
	
	echo json_encode($stats);

}


?>