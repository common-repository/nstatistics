<?php
function getTodayDate($days = 0){
	if ($days!=0)
		return date('Y-m-d', strtotime("$days days"));
	else
		return date('Y-m-d', strtotime("now"));
}
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
	bindDayPiker("#nstat_d1");
	bindDayPiker("#nstat_d2");
});	
</script>
<div id="wpbody-content">
<table style="width: 590px; margin-top: 25px;" border="0" cellspacing="0" cellpadding="0" class="widefat post fixed">
  <thead>
  	<tr>
	  <th>From 
	    <input type="text" class="nstat_d1" name="nstat_d1" id="nstat_d1" value="<?php echo(getTodayDate(-3)) ?>" /> 
	    to 
	    <input type="text" class="nstat_d2" name="nstat_d2" id="nstat_d2" value="<?php echo(getTodayDate()) ?>" />
	    Limit 
	    <input type="text" class="limit" name="limit" id="limit" value="100" style=" width: 36px;"/>
	    &nbsp;
        <input type="button" name="button" id="button" value="  Display Report  " 
        	   onclick="jQuery('#rowData').load('<?php echo(get_option('siteurl')); ?>/wp-content/plugins/nstatistics/row-data.php?items='+jQuery('#limit').val()+'&start='+jQuery('#nstat_d1').val()+'&end='+jQuery('#nstat_d2').val());" /></th>
    </tr>
  </thead>
</table>

<table width="98%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td>&nbsp;
		<div id="rowData" style="width:800px;"></div>
    </td>
  </tr>
</table>


<script type="text/javascript">
	jQuery('#rowData').load('<?php echo(get_option('siteurl')); ?>/wp-content/plugins/nstatistics/row-data.php?items=100');
</script>

</div>