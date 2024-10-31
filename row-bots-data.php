<table border="0" cellspacing="0" cellpadding="0" class="widefat post fixed" style="width: 891px;">
  <thead>
  	<tr>
  	<th style="width: 10px;"></th>
  	<th style="width: 120px;">Crawler</th>
  	<th style="width: 100px;">IP</th>
  	<th style="width: 55px;">Time</th>
  	<th style="width: 100px;">Views</th>
  	<th style="width: 420px;">&nbsp;</th>
    </tr>
  </thead>  
  <?php
if (!function_exists('add_action')){
    require_once('../../../wp-config.php');
	include('classes/stats-presenter.php');

	$sp = new StatsPresenter();	
	$lastData = $sp->crawlers->getRawCrawlersData($_GET['start'], $_GET['end']);
 
 	$i = 0;
	foreach ($lastData as $item){
		$i++;
		if (isset($item['data'])){
			$img = '<img src="../wp-content/plugins/nstatistics/images/other/plus.png" class="img_d'.ereg_replace("[^A-Za-z0-9 _]", "", $item['data']).'"/>';
			echo ('
		  <tr class="parent" id="d'.ereg_replace("[^A-Za-z0-9 _]", "", $item['data']).'">
		  	<td>'.$img.'</td>
			<td colspan="5" style="font-size: 14px; background-color: #fdd0022"><strong> '.$item['data'].'</strong></td>
		  </tr>		
		  ');	
		}else{
			if (isset($item['bot1']))
			echo('
			  <tr class="child-d'.ereg_replace("[^A-Za-z0-9 _]", "", $item['dat']).'">
				<td></td>
				<td><strong>'.$item['bot'].' '.$item['version'].'</strong></td>
				<td>'.$item['IP'].'</td>
				<td>'.$item['dat_time'].'</td>
				<td>'.$item['nr'].'</td>
				<td></td>
			  </tr>	
			  ');
		  	else{
			echo('
			  <tr class="child-d'.ereg_replace("[^A-Za-z0-9 _]", "", $item['dat']).'">
				<td></td>
				<td>|</td>
				<td>'.$item['IP'].'</td>
				<td>'.$item['dat_time'].'</td>
				<td>'.$item['nr'].'</td>
				<td></td>
			  </tr>	
			  ');			
			}
		}
	}
	if ($i==0){
			echo('
			  <tr>
				<td colspan="6" align="center"> No data available</td>
			  </tr>	
			  ');			
	}
}
?>
</table>
<script type="text/javascript">
	var sw=true;
	jQuery(function() {
		  jQuery('tr.parent')
				.css("cursor","pointer")
				.attr("title","Click to expand/collapse")
				.click(function(){
					  jQuery(this).siblings('.child-'+this.id).toggle();
				});
	});
</script>
<?php
exit;
?>