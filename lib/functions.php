<?php

//Configuration file
require_once('config.php');

//Classes
require_once('classes/mcmonitor.class.php');

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








?>