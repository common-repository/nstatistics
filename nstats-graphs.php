<?php
	include('classes/stats-presenter.php');
	$sp = new StatsPresenter();
	$TodayTraficSources = $sp->getTodayTraficSources(15);
	$TodayAccessedPages = $sp->getTodayAccessedPages(15);
	$TodayData = $sp->getTodayStats();
	$AllTime = $sp->generateAllTimeStats();
	$TodayKeywords = $sp->getKewords(10, date('Y-m-d', strtotime("now")), date('Y-m-d', strtotime("now")));
?>
<table style="width: 891px; margin-top: 20px;" border="0" cellspacing="0" cellpadding="0" class="widefat post fixed">
  <thead>
  	<tr>
	  <th>Visits & View Graph</th>
    </tr>
  </thead>
  <tbody>
  <tr>
    <td>&nbsp;
		<div id="div_g30" style="width:885px; height:200px;"></div>
		<script type="text/javascript">
            g30 = new Dygraph(
                  document.getElementById("div_g30"),
                  "<?php echo(get_option('siteurl')); ?>/wp-content/plugins/nstatistics/graph1.php", 
                  {
                    rollPeriod: 1,
                    showRoller: true,
                    fillGraph: true
                  }
                );
        </script>          
    </td>
  </tr>
  </tbody>
</table>
<div id="GoogleAdsenseBlock"></div>
<table style="width: 891px; margin-top: 20px; overflow: hidden" border="0" cellspacing="0" cellpadding="0" class="widefat post fixed">
  <thead>
  	<tr>
  		<th>Statistics</th>
        <th>Top Keywords</th>
    </tr>
  </thead>
  <tbody>
    <tr>
        <td >
        	<table>
            	<tr>
            		<td style=" font-size:14px">Today</td>
           		    <td style=" font-size:14px">All Time</td>
            	</tr>
            	<tr>
            	  <td style="font-weight:bold">
                  	Unique visitors: <span style=" color:#000099"><?php echo($sp->today['vizits']) ?></span><br/>
					Pagevievs: <span style=" color:#000099"><?php echo($sp->today['views']) ?></span><br/>
					Robots: <span style=" color:#000099"><?php echo($sp->today['bots']) ?></span><br/>
					Robots views: <span style=" color:#000099"><?php echo($sp->today['bots_views']) ?></span>                  
                  </td>
          	      <td style="font-weight:bold">
                    Unique visitors: <span style=" color:#000099"><?php echo($AllTime['visits']); ?></span><br/>
					Total page views: <span style=" color:#000099"><?php echo($AllTime['pagevizits']); ?></span>
                  </td>
           	  </tr>
        </table>        </td>
        <td><?php echo($TodayKeywords); ?></td>
    </tr>
  </tbody>
</table>

<div style="width: 891px; margin-top: 20px; overflow: hidden">
<table border="0" cellspacing="0" cellpadding="0" class="widefat post fixed">
  <thead>
  	<tr>
  		<th style="width: 261px;">Browsers</th>
  	    <th style="width: 620px;">Visits &amp; View Graph by Hours</th>
  	</tr>
  </thead>
  <tbody>
    <tr>
        <td>
        <style media="all">
		 .brs{width: 130px; overflow: hidden; float: left;}
		</style>
        <?php
			if(is_array($sp->today['browsers'])){
				foreach($sp->today['browsers'] as $item)
					echo('<div class="brs">'.$sp->getBlowserLogo($item[0], $item[2]).' '.$item[0].' '.$item[2].': <span style=" color:#000099">'.$item[1].'</span></div>');
			}
		?>        </td>
        <td>
            <div id="div_stats_hours" style="width:600px; height:160px;"></div>
            <script type="text/javascript">
                g30 = new Dygraph(
                      document.getElementById("div_stats_hours"),
                      "<?php echo(get_option('siteurl')); ?>/wp-content/plugins/nstatistics/graph3.php", 
                      {
                        rollPeriod: 1,
                        showRoller: true,
                        fillGraph: true
                      }
                    );
            </script>            
        </td>
    </tr>
  </tbody>
</table>
</div>

<table style="width: 891px; margin-top: 20px;" border="0" cellspacing="0" cellpadding="0" class="widefat post fixed">
  <thead>
  	<tr>
  		<th>Trafic Sources</th>
  	</tr>
  </thead>
  <tbody>
    <tr>
        <td><?php echo($TodayTraficSources); ?></td>
    </tr>
  </tbody>
</table>

<table style="width: 891px; margin-top: 20px;" border="0" cellspacing="0" cellpadding="0" class="widefat post fixed">
  <thead>
  	<tr>
  		<th>Accesed Pages</th>
  	</tr>
  </thead>
  <tbody>
    <tr>
        <td>&nbsp;
        <?php echo($TodayAccessedPages); ?>
        </td>
    </tr>
  </tbody>
</table>

<!-- Start Row Data -->
<div id="rowData" style="width: 891px; margin-top: 20px; overflow: hidden"></div>
<script type="text/javascript">
	jQuery('#rowData').load('<?php echo(get_option('siteurl')); ?>/wp-content/plugins/nstatistics/row-data.php');
</script>
<!-- End Row Data -->

<script type="text/javascript">
	window.onload = function() {
		document.getElementById('GoogleAdsenseBlock').appendChild(document.getElementById('GoogleAdsenseCode'));
		document.getElementById('GoogleAdsenseCode').style.display = '';
	}
</script>
<div id="GoogleAdsenseCode" style="display: none;">
<?php include('graph-ad.php'); ?>
</div>