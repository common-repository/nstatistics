<?php
/*
echo('Date,Visits,Views
20070101,62,39
20070102,62,44
20070103,62,42
20070104,57,45
20070105,54,44
20070106,55,36
20070107,62,45
20070108,66,48
20070109,63,39
20070110,57,37
20070111,50,37
20070112,48,35
20070113,48,30
20070114,48,28');
*/

if (!function_exists('add_action')){
    require_once('../../../wp-config.php');
	include('classes/stats-presenter.php');
	
	$sp = new StatsPresenter();
	
	if (isset($_GET['start']) && isset($_GET['end']))
		$lastDays = $sp->getVisitorsGraph($_GET['start'], $_GET['end']);
	else
		$lastDays = $sp->generateLastDaysStats(45);
	
	$csv = 'Date,Visits,Views'."\n";
	foreach ($lastDays as $item){
		$csv.=$item['reg_date'].','.$item['visits'].','.$item['pageviews']."\n";
	}
	echo $csv;

}
exit;

?>