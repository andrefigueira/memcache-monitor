<?php require_once('lib/functions.php'); is_authenticated(); ?>
<!DOCTYPE html>
<html>
<head>
	<title>Memcache Monitor - Status Board</title>
	<base href="<?php echo BASE_URL; ?>" />
	<link href="css/main.css" rel="stylesheet" type="text/css" />
	<link href="http://fonts.googleapis.com/css?family=Lato" rel="stylesheet" type="text/css" />
	<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/2.0.0/jquery.min.js"></script>
	<script type="text/javascript" src="js/functions.js"></script>
	<script type="text/javascript" src="https://www.google.com/jsapi"></script>
	<script type="text/javascript">
	
      google.load("visualization", "1", {packages:["corechart"]});
      google.setOnLoadCallback(draw_chart);
      
      function draw_chart() 
      {
      
      	var cache_size = Number($('#cache_size').val());
      	var cache_used = Number($('#cache_used').val());
      
        var pie_data = google.visualization.arrayToDataTable([
          ['Type', 'Percentage'],
          ['Free', cache_size],
          ['Used', cache_used]
        ]);

        var pie_options = {
	        is3D: false,
	        title: 'Cache usage in bytes'
        };

        var pie_chart = new google.visualization.PieChart(document.getElementById('pie-chart'));
        pie_chart.draw(pie_data, pie_options);
      
      	var hits = Number($('#hits').val());
      	var misses = Number($('#misses').val());
      
        var area_data = google.visualization.arrayToDataTable([
          ['Test', 'Hits', 'Misses'],
          ['', hits, misses]
        ]);

        var area_options = {
	        is3D: false,
	        title: 'Hits/Misses'
        };

        var area_chart = new google.visualization.ColumnChart(document.getElementById('area-chart'));
        area_chart.draw(area_data, area_options);
        
      }
      
    </script>
</head>
<body>
	
	<input type="hidden" name="BASE_URL" id="BASE_URL" value="<?php echo BASE_URL; ?>" />
	
	<?php require_once('lib/includes/header.php'); ?>
	
	<div class="container">
	
		<?php
		
		$memcache = new Memcache();
		
		$cache_available = $memcache->connect(MC_HOST, MC_PORT);
		
		if($cache_available)
		{
		
			$stats_array = $memcache->getStats();
			$stats = array_to_object($stats_array);
			$uptime = seconds_to_time($stats->uptime);
			$uptime_str = $uptime['d'].'d '.$uptime['h'].'h '.$uptime['m'].'m '.$uptime['s'].'s';
			$cache_size = format_bytes($stats->limit_maxbytes);
			$cache_used = format_bytes($stats->bytes);
		
		?>
		
		<input type="hidden" name="cache_size" id="cache_size" value="<?php echo $stats->limit_maxbytes; ?>" />
		<input type="hidden" name="cache_used" id="cache_used" value="<?php echo $stats->bytes, 2; ?>" />
		
		<input type="hidden" name="hits" id="hits" value="<?php echo $stats->get_hits; ?>" />
		<input type="hidden" name="misses" id="misses" value="<?php echo $stats->get_misses; ?>" />
		
		<div class="sub-header">
		
			<div class="wrapper">
			
				<div class="sub-header-left">
					<span id="data_uptime">Uptime <?php echo $uptime_str; ?></span>
					<span id="items_curr_items">Items stored <?php echo $stats->curr_items; ?></span>
				</div>
				<div class="sub-header-right"></div>
				
				<div class="clear"></div>
			
			</div><!-- End wrapper -->
			
		</div><!-- End sub header -->
		
		<div class="wrapper">
			
			<div class="portal">
			
				<div class="title">Server Statistics</div>
			
				<table cellpadding="0" cellspacing="0" class="standard-table">
				
					<tr>
						<th>Host</th>
						<td><?php echo MC_HOST; ?></td>
					</tr>
					
					<tr>
						<th>Port</th>
						<td><?php echo MC_PORT; ?></td>
					</tr>
				
					<tr>
						<th>Process ID</th>
						<td id="data_pid"><?php echo $stats->pid; ?></td>
					</tr>
					<tr>
						<th>Threads</th>
						<td id="data_threads"><?php echo $stats->threads; ?></td>
					</tr>
					<tr>
						<th>Server time</th>
						<td id="data_time"><?php echo date('d-m-Y H:i:s', $stats->time); ?></td>
					</tr>
					<tr>
						<th>Version</th>
						<td><?php echo $stats->version; ?></td>
					</tr>
					<tr>
						<th>User process time</th>
						<td id="data_rusage_user"><?php echo $stats->rusage_user; ?></td>
					</tr>
					<tr>
						<th>System process time</th>
						<td id="data_rusage_system"><?php echo $stats->rusage_system; ?></td>
					</tr>
					<tr>
						<th>Total items stored</th>
						<td id="data_total_items"><?php echo $stats->total_items; ?></td>
					</tr>
					<tr>
						<th>Open connections</th>
						<td id="data_curr_connections"><?php echo $stats->curr_connections; ?></td>
					</tr>
					<tr>
						<th>Total connections</th>
						<td id="data_total_connections"><?php echo $stats->total_connections; ?></td>
					</tr>
					<tr>
						<th>Allocated connections</th>
						<td id="data_connection_structures"><?php echo $stats->connection_structures; ?></td>
					</tr>
					<tr>
						<th>Total retrieval requests</th>
						<td id="data_cmd_get"><?php echo $stats->cmd_get; ?></td>
					</tr>
					<tr>
						<th>Total storage requests</th>
						<td id="data_cmd_set"><?php echo $stats->cmd_set; ?></td>
					</tr>
					<tr>
						<th>Data read</th>
						<td id="data_bytes_read"><?php echo format_bytes($stats->bytes_read); ?></td>
					</tr>
					<tr>
						<th>Data written</th>
						<td id="data_bytes_written"><?php echo format_bytes($stats->bytes_written); ?></td>
					</tr>
				
				</table>
			
			</div><!-- End portal -->
			
			<div class="portal">
			
				<div class="title">Cache Usage Chart</div>
			
				<div class="pie-chart" id="pie-chart"></div>
				
			</div><!-- End portal -->
			
			<div class="portal">
			
				<div class="title">Cache Usage Details</div>
			
				<table cellpadding="0" cellspacing="0" class="standard-table">
				
					<tr>
						<th>Cache used</th>
						<td id="data_cache_used"><?php echo $cache_used; ?></td>
					</tr>
					<tr>
						<th>Cache size</th>
						<td id="data_cache_size"><?php echo $cache_size; ?></td>
					</tr>
				
				</table>
				
			</div><!-- End portal -->
			
			<div class="portal">
			
				<div class="title">Hits/Misses Details</div>
			
				<table cellpadding="0" cellspacing="0" class="standard-table">
				
					<tr>
						<th>Hits</th>
						<td id="data_get_hits"><?php echo $stats->get_hits; ?></td>
					</tr>
					<tr>
						<th>Misses</th>
						<td id="data_get_misses"><?php echo $stats->get_misses; ?></td>
					</tr>
				
				</table>
				
			</div><!-- End portal -->
			
			<div class="portal">
			
				<div class="title">Hit/Misses Chart</div>
			
				<div class="area-chart" id="area-chart"></div>
				
			</div><!-- End portal -->
			
			<?php }else{ ?>
			
				<p class="error">Unable to connect to the memcache server...</p>
			
			<?php } ?>
			
			<div class="clear"></div>
		
		</div><!-- End wrapper -->
		
	</div><!-- End container -->
	
	<?php require_once('lib/includes/footer.php'); ?>

</body>
</html>