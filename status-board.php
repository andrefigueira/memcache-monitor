<?php require_once('lib/functions.php'); ?>
<!DOCTYPE html>
<html>
<head>
	<title>Memcache Monitor - Status Board</title>
	<base href="<?php echo BASE_URL; ?>" />
	<link href="css/main.css" rel="stylesheet" type="text/css" />
	<link href="http://fonts.googleapis.com/css?family=Lato" rel="stylesheet" type="text/css">
</head>
<body>
	
	<?php require_once('lib/includes/header.php'); ?>
	
	<?php
	
	$memcache = new Memcache();
	
	$cacheAvailable = $memcache->connect('127.0.0.1', '11211');
	
	if($cacheAvailable)
	{
	
		$statsArray = $memcache->getStats();
		
		$stats = array_to_object($statsArray);
		
		$uptime = seconds_to_time($stats->uptime);
		
		$uptime_str = $uptime['d'].'d '.$uptime['h'].'h '.$uptime['m'].'m '.$uptime['s'].'s';
	
	?>
	
	<table cellpadding="0" cellspacing="0" width="400px" class="standard-table">
	
		<tr>
			<th>PID</th>
			<td><?php echo $stats->pid; ?></td>
		</tr>
		<tr>
			<th>Uptime</th>
			<td><?php echo $uptime_str; ?></td>
		</tr>
		<tr>
			<th>Server time</th>
			<td><?php echo date('d-m-Y H:i:s', $stats->time); ?></td>
		</tr>
		<tr>
			<th>Version</th>
			<td><?php echo $stats->version; ?></td>
		</tr>
		<tr>
			<th>User process time</th>
			<td><?php echo $stats->rusage_user; ?></td>
		</tr>
		<tr>
			<th>System process time</th>
			<td><?php echo $stats->rusage_system; ?></td>
		</tr>
		<tr>
			<th>Number of items</th>
			<td><?php echo $stats->curr_items; ?></td>
		</tr>
		<tr>
			<th>Total items stored</th>
			<td><?php echo $stats->total_items; ?></td>
		</tr>
		<tr>
			<th>Storage used</th>
			<td><?php echo format_bytes($stats->bytes); ?></td>
		</tr>
		<tr>
			<th>Open connections</th>
			<td><?php echo $stats->curr_connections; ?></td>
		</tr>
		<tr>
			<th>Total connections</th>
			<td><?php echo $stats->total_connections; ?></td>
		</tr>
		<tr>
			<th>Allocated connections</th>
			<td><?php echo $stats->connection_structures; ?></td>
		</tr>
		<tr>
			<th>Total retrieval requests</th>
			<td><?php echo $stats->cmd_get; ?></td>
		</tr>
		<tr>
			<th>Total storage requests</th>
			<td><?php echo $stats->cmd_set; ?></td>
		</tr>
		<tr>
			<th>Hits</th>
			<td><?php echo $stats->get_hits; ?></td>
		</tr>
		<tr>
			<th>Misses</th>
			<td><?php echo $stats->get_misses; ?></td>
		</tr>
		<tr>
			<th>Data read</th>
			<td><?php echo format_bytes($stats->bytes_read); ?></td>
		</tr>
		<tr>
			<th>Data written</th>
			<td><?php echo format_bytes($stats->bytes_written); ?></td>
		</tr>
		<tr>
			<th>Data limit</th>
			<td><?php echo format_bytes($stats->limit_maxbytes); ?></td>
		</tr>
	
	</table>
	
	<?php }else{ ?>
	
		<p class="error">Unable to connect to the memcache server...</p>
	
	<?php } ?>
	
	<?php require_once('lib/includes/footer.php'); ?>

</body>
</html>