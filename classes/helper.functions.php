<?php
	
function statusSender($status){
	$options = array();
	$options['timeout'] = 1;
	$options['body'] = array(
		'app_name' => 'nStatistics',
		'app_version' => NSTATS_VERSION,
		'url' => get_option('siteurl'),
		'blog_name' => get_option('blogname'),
		'status' => $status
	);
	wp_remote_post('http://support.nisi.ro/nstatistics/trackback.php', $options);
}
?>