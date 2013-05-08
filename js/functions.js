$(document).ready(function(){
	
	var BASE_URL = $('#BASE_URL').val();
	
	setInterval(function(){
	
		fetch_statistics();
	
	}, 1000);
	
});

function fetch_statistics()
{
	
	var BASE_URL = $('#BASE_URL').val();
	
	$.ajax({
		url: BASE_URL + 'request/get_statistics/',
		dataType: 'json',
		success: function(data){
		
			update_data(data);
			
		}
	});

}

function update_data(data)
{

	$('#data_uptime').html('Uptime ' + data.uptime_str);
	$('#items_stored').html(data.curr_items);
	$('#data_pid').html(data.pid);
	$('#data_threads').html(data.threads);
	$('#data_time').html(data.time);
	$('#data_rusage_user').html(data.rusage_user);
	$('#data_rusage_system').html(data.rusage_system);
	$('#data_total_items').html(data.test);
	$('#data_curr_connections').html(data.curr_connections);
	$('#data_total_connections').html(data.total_connections);
	$('#data_connection_structures').html(data.connection_structures);
	$('#data_cmd_get').html(data.cmd_get);
	$('#data_cmd_set').html(data.cmd_set);
	$('#data_bytes_read').html(data.bytes_read);
	$('#data_bytes_written').html(data.bytes_written);
	$('#data_cache_used').html(data.cache_used);
	$('#data_cache_size').html(data.cache_size);
	$('#data_get_hits').html(data.get_hits);
	$('#data_get_misses').html(data.get_misses);

}