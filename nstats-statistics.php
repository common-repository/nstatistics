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
	
	bindDayPiker("#ts_date");
	jQuery('#table1').load('<?php echo(get_option('siteurl')); ?>/wp-content/plugins/nstatistics/table1.php?date='+jQuery('#ts_date').val());
});	
	
	function changeGraph(){
    	var g30 = new Dygraph(
              document.getElementById("div_g30"),
              "<?php echo(get_option('siteurl')); ?>/wp-content/plugins/nstatistics/graph1.php?start="
			         + jQuery('#nstat_star').val() + "&end=" + jQuery('#nstat_end').val(), 
              {
                rollPeriod: 1,
                showRoller: true,
                fillGraph: true
              }
            );
	}
	function changeContent1(){
		jQuery('#table1').html = 'Hello';
		jQuery('#table1').load('<?php echo(get_option('siteurl')); ?>/wp-content/plugins/nstatistics/table1.php?date='+jQuery('#ts_date').val());
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
                  "<?php echo(get_option('siteurl')); ?>/wp-content/plugins/nstatistics/graph1.php", 
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

<table style="width: 891px; margin-top: 25px;" border="0" cellspacing="0" cellpadding="0" class="widefat post fixed">
  <thead>
  	<tr>
	  <th colspan="4">Trafic Sources</th>
    </tr>
  </thead>
  <tbody>
  <tr>
    <td>Date    </td>
    <td rowspan="5">
    	<div id="table1" style="width:700px; height:200px;">
        </div>    
    </td>
  </tr>
  <tr>
    <td valign="top">
    	<input type="text" class="ts_date" name="ts_date" id="ts_date" value="<?php echo(getTodayDate()) ?>" onchange="changeContent1(); alert('ok');" />
    </td>
  </tr>
  <tr>
    <td></td>
  </tr>
  <tr>
    <td></td>
  </tr>
  <tr>
    <td></td>
  </tr>
  <tr>
    <td colspan="2"><div align="right">&laquo; previous - next &raquo;</div></td>
    </tr>
  </tbody>
</table>
</div>

