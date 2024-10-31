<?php
/*
Plugin Name: nStatistics 
Plugin URI: http://wiki.nisi.ro/wordpress/nstatistics-wordpress-plugin/
Description: This plugin generate complex blog statistics for users and robots.
Author: Mihai Nisipeanu
Version: 2.0
Author URI: http://www.nisi.ro/
*/

global $wpdb;
define('TB_nstatistics', $wpdb->prefix.'nstatistics');
define('TB_arhive', $wpdb->prefix.'nstatistics_arhive');
define('TB_bots', $wpdb->prefix.'nstatistics_bots');
define('TB_pages', $wpdb->prefix.'nstatistics_pages');
define('TB_refer', $wpdb->prefix.'nstatistics_refer');
define('TB_log', $wpdb->prefix.'nstatistics_log');

define('nStat_DEBUG', true);
define('NSTATS_VERSION', '2.0.0');

include('classes/helper.functions.php');
/* Plugin Hooks */
// Activate / Deactivate
register_activation_hook(__FILE__, 'nStats_Activate');
register_deactivation_hook(__FILE__, 'nStats_Deactivate');

// Parse UserAgent
add_action('template_redirect', 'nS_parseAgent');

// Admin Hook
add_action('admin_menu', 'nS_register_admin');

function nS_parseAgent(){
	/* make a checkup */
	$version = get_option('nStats_version');
	checkup($version);

	if (is_404()){
		// save 404
	}else{
		include_once('classes/stats-parser.php');
		
		$stParser = new StatsParser();
		$stParser->setValue('server', $_SERVER);
		
		$stParser->getData();
		$stParser->saveData();
	}
}

function load_adminJS(){
	$semipath = get_option('siteurl').'/wp-content/plugins/nstatistics/js/dygraph';

	echo ('<!--[if IE]>');
    echo '<script type="text/javascript" src="'.$semipath .'/excanvas.js"></script>"></script>'; 
    echo ('<![endif]-->');
	echo '<script type="text/javascript" src="'.$semipath .'/dygraph-combined.js"></script>'; 
	echo '<script type="text/javascript" src="'.$semipath .'/dygraph-canvas.js"></script>'; 
	echo '<script type="text/javascript" src="'.$semipath .'/dygraph.js"></script>'; 
}

if (!function_exists("nS_register_admin")){
	function nS_register_admin(){
		add_action('admin_head','load_adminJS');
		add_action('admin_head','load_datepickerJS');
		
		add_menu_page('nStatistics ', 'nStatistics ', 'administrator', 'nstats-handle', 'nstats_nstats_graphs');
		add_submenu_page('nstats-handle', 'Overview', 'Overview', 'administrator', 'nstats-handle','nstats_nstats_graphs');
		
		//add_submenu_page('nstats-handle', 'Statistics', 'Statistics', 'administrator', dirname(__FILE__).'/nstats-statistics.php', 'nstats_tatistics');
	
		add_submenu_page('nstats-handle', 'Raw Data', 'Raw Data', 'administrator', dirname(__FILE__).'/nstats-rowdata.php', 'nstats_nstats_rowdata');
		add_submenu_page('nstats-handle', 'Bots & Crawlers', 'Bots & Crawlers', 'administrator', dirname(__FILE__).'/nstats-crawlers.php', 'nstats_crawler');

		add_submenu_page('nstats-handle', 'Settings', 'Settings', 'administrator', dirname(__FILE__).'/nstats-settings.php', 'nstats_nstats_settings');
		add_submenu_page('nstats-handle', 'About & TOS', 'About & TOS', 'administrator', dirname(__FILE__).'/nstats-about.php', 'nstats_nstats_about');
	}
}

function nstats_nstats_graphs(){
	include('nstats-graphs.php');
}
function nstats_crawler(){
	include('nstats-crawlers.php');
}
function nstats_tatistics(){
	include('nstats-statistics.php');
}
function nstats_nstats_settings(){
	include(dirname(__FILE__).'/nstats-install.php');
	include('nstats-settings.php');
}
function nstats_nstats_rowdata(){
	include('nstats-rowdata.php');
}
function nstats_nstats_about(){
	include('nstats-about.php');
}

function load_datepickerJS(){
	$semipath = get_option('siteurl').'/wp-content/plugins/nstatistics/js/datepicker';
    echo ('
	<link rel="stylesheet" href="'.$semipath.'/css/datepicker.css" type="text/css" />');
	echo ('
	<script type="text/javascript" src="'.$semipath.'/js/datepicker.js"></script>
	');
}
	
function tableExists($tbName){
	global $wpdb;
	$sql = 'SHOW TABLES LIKE \'' .$tbName. '\'';
	
    return $wpdb->query($sql);
}

function checkup($version){
	if ($version != NSTATS_VERSION)
		nStats_Activate();
}
function nStats_Activate(){
	include(dirname(__FILE__).'/nstats-install.php');
	global $wpdb;

    if (tableExists(TB_nstatistics)==0){
		$wpdb->query($nStatTables['TB_nstatistics']);
	}
    if (tableExists(TB_arhive)==0){
		$wpdb->query($nStatTables['TB_arhive']);
	}
    if (tableExists(TB_pages)==0){
		$wpdb->query($nStatTables['TB_pages']);
	}else{
		$wpdb->query($nStatTables['TB_pages_update1']);
	}
	
    if (tableExists(TB_bots)==0){
		$v=$wpdb->query($nStatTables['TB_bots']);
	}
    if (tableExists(TB_refer)==0){
		$wpdb->query($nStatTables['TB_refer']);
	}
    if (tableExists(TB_log)==0){
		$wpdb->query($nStatTables['TB_log']);
	}
	
	update_option('nStats_version', NSTATS_VERSION);
	update_option('nStats_crawler_log', '1');
	statusSender('Activated');
}
function nStats_Deactivate(){
	statusSender('Deactivated');	
}
?>