<?php
function getTodayDate($days = 0){
	if ($days!=0)
		return date('Y-m-d', strtotime("$days days"));
	else
		return date('Y-m-d', strtotime("now"));
}

include('classes/stats-presenter.php');

$sp = new StatsPresenter();
?>

<script type="text/javascript">

function bindDayPiker(idStr){
	jQuery(idStr).DatePicker({
		format:'Y-m-d',
		date: ['2010-02-15', '2010-03-27'],
		current: '2010-03-15',
		starts: 1,
		position: 'right',
		onChange: function(formated, dates){
			jQuery(idStr).val(formated);
			jQuery(idStr).DatePickerHide();
		},
		onBeforeShow: function(){
			jQuery(idStr).DatePickerSetDate(jQuery(idStr).val(), true);
		}			
	});
}

jQuery(function() {
	bindDayPiker("#nstat_end");
	bindDayPiker("#nstat_star");	
	
	bindDayPiker("#nstat_d1");
	bindDayPiker("#nstat_d2");
});	
	
	function changeGraph(){
    	var g30 = new Dygraph(
              document.getElementById("div_g30"),
              "<?php echo(get_option('siteurl')); ?>/wp-content/plugins/nstatistics/graph2.php?start="
			         + jQuery('#nstat_star').val() + "&end=" + jQuery('#nstat_end').val(), 
              {
                rollPeriod: 1,
                showRoller: true,
                fillGraph: true
              }
            );
	}
</script>

<div id="wpbody-content">
<table style="width: 891px; margin-top: 25px;" border="0" cellspacing="0" cellpadding="0" class="widefat post fixed">
  <thead>
  	<tr>
	  <th>Visits & View Graph</th>
    </tr>
  </thead>
  <tbody>
  <tr>
    <td>&nbsp;
		<table width="98%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td>From</td>
            <td rowspan="5">
		<div id="div_g30" style="width:700px; height:130px;"></div>
		<script type="text/javascript">
            g30 = new Dygraph(
                  document.getElementById("div_g30"),
                  "<?php echo(get_option('siteurl')); ?>/wp-content/plugins/nstatistics/graph2.php", 
                  {
                    rollPeriod: 1,
                    showRoller: true,
                    fillGraph: true
                  }
                );
        </script>            </td>
          </tr>
          <tr>
            <td><input type="text" class="nstat_star" name="nstat_star" id="nstat_star" value="<?php echo(getTodayDate()) ?>" /></td>
          </tr>
          <tr>
            <td>To</td>
          </tr>
          <tr>
            <td><input type="text" class="nstat_end" name="nstat_end" id="nstat_end" value="<?php echo(getTodayDate(-30)) ?>" /></td>
          </tr>
          <tr>
            <td><label>
              <div align="center">
                <input type="button" name="bt_graph" id="bt_graph" value="   Display Graph   " onclick="changeGraph()"/>
                </div>
            </label></td>
          </tr>
        </table>
    </td>
  </tr>
  </tbody>
</table>


<table style="width: 500px; margin-top: 25px;" border="0" cellspacing="0" cellpadding="0" class="widefat post fixed">
  <thead>
  	<tr>
	  <th>From 
	    <input type="text" class="nstat_d1" name="nstat_d1" id="nstat_d1" value="<?php echo(getTodayDate(-3)) ?>" /> 
	    to 
	    <input type="text" class="nstat_d2" name="nstat_d2" id="nstat_d2" value="<?php echo(getTodayDate()) ?>" />
	    &nbsp;
        <input type="button" name="button" id="button" value="  Display Report  " 
        	   onclick="jQuery('#rowData').load('<?php echo(get_option('siteurl')); ?>/wp-content/plugins/nstatistics/row-bots-data.php?start='+jQuery('#nstat_d1').val()+'&end='+jQuery('#nstat_d2').val());" /></th>
    </tr>
  </thead>
</table>
<div id="rowData" style="width:800px;"></div>

<script type="text/javascript">
	jQuery('#rowData').load('<?php echo(get_option('siteurl')); ?>/wp-content/plugins/nstatistics/row-bots-data.php');
</script>

</div>