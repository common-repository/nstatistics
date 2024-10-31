<table border="0" cellspacing="0" cellpadding="0" class="widefat post fixed">
  <thead>
  	<tr>
  	<th style="width: 10px;"></th>
  	<th style="width: 120px;">IP</th>
  	<th style="width: 70px;">Time</th>
  	<th style="width: 55px;">Views</th>
  	<th style="width: 100px;">Browser</th>
  	<th style="width: 450px;">From</th>
    </tr>
  </thead>  
  <?php
if (!function_exists('add_action')){
    require_once('../../../wp-config.php');
	include('classes/stats-presenter.php');

	if (isset($_GET['items']))
		$items = $_GET['items'];
	else
		$items = 20;
	$sp = new StatsPresenter();
	
	$lastData = $sp->getRowData($items, $_GET['start'], $_GET['end']);
 
 	$i=0;
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
			$img = $sp->getBlowserLogo($item['browser'], $item['version']);
			if (strlen($item['domain'])>0)
				$from = $item['domain'];
			else
				$from = 'direct';
			
			echo('
		  <tr class="child-d'.ereg_replace("[^A-Za-z0-9 _]", "", $item['dat']).'">
		  	<td></td>
			<td><strong>'.$item['IP'].'</strong></td>
			<td>'.$item['dat_time'].'</td>
			<td>'.$item['nr'].'</td>
			<td>'.$img.' '.$item['browser'].' '.$item['version'].'</td>
			<td>'.$from.'</td>
		  </tr>	
		  ');
		  if (strlen($item['keyword'])>0){ 
		  	echo('
		  <tr class="child-d'.ereg_replace("[^A-Za-z0-9 _]", "", $item['dat']).'">
		    <td></td>
			<td><div style="margin-bottom: 5px; font-weight:400">&nbsp;&nbsp;&nbsp;Keywords:</div></td>
			<td colspan="5" align="left"><span style=" color:#000099">'.$item['keyword'].'<span></td>
		  </tr>  	
			');
		  }
		}
	}
	if ($i==0){
		echo('
		  <tr class="child-d'.ereg_replace("[^A-Za-z0-9 _]", "", $item['dat']).'">
			<td colspan="7" align="center">Data not available</td>
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
					  if (sw==true)
					  	sw=false;
					  else
					  	sw=true;
					  if (sw)
					  	jQuery('.img_d'+this.id).attr("src", "../wp-content/plugins/nstatistics/images/other/minus.png");
					  else
					  	jQuery('.img_d'+this.id).attr("src", "../wp-content/plugins/nstatistics/images/other/plus.png");
				});
	});
</script>
<?php
exit;
?>
