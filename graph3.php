<?php
if (!function_exists('add_action')){
    require_once('../../../wp-config.php');
	include('classes/stats-presenter.php');
	
	$sp = new StatsPresenter();
	$lastDays = $sp->getVisitorsByHour($_GET['start'], $_GET['end']);
	
	$csv = 'Hour,Visits,Views'."\n";
	$now = date('Y-m-d', strtotime("now"));
	foreach ($lastDays as $item){
		$csv.=$now.' '.$item['dat'].':00:00,'.$item['visits'].','.$item['pageviews']."\n";
	}
	echo $csv;

}
exit;
?>