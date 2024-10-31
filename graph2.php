<?php
if (!function_exists('add_action')){
    require_once('../../../wp-config.php');
	include('classes/stats-presenter.php');
	
	$sp = new StatsPresenter();
	$lastDays = $sp->crawlers->generateCrawlerGraph($_GET['start'], $_GET['end']);
	
	$csv = 'Date,Crawlers,Views'."\n";
	foreach ($lastDays as $item){
		$csv.=$item['dat'].','.$item['botnr'].','.$item['visits']."\n";
	}
	echo $csv;

}
exit;
?>