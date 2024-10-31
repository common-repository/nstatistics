<pre>
<?php
if (!function_exists('add_action')){
    require_once('../../../wp-config.php');
	include('classes/stats-presenter.php');
	
	$sp = new StatsPresenter();
	$code = $sp->getTraficSources(10, $_GET['date'], $_GET['date'], 0);
	//print_r($_POST);
	//print_r($_GET);
	echo($code);
}
//exit;
?>
</pre>