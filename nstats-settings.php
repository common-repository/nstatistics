<?php
	if (isset($_POST['bt_save'])){
		//if ($_POST['user_log'])
		//	echo 'true1';
		if ($_POST['crawler_log'])
			update_option('nStats_crawler_log', '1');
		else
			update_option('nStats_crawler_log', '0');
	}
	
	if (isset($_POST['bt_archive']))
		echo('arhivare');

	if (isset($_POST['bt_clean'])){
		global $wpdb;
		if ($_POST['del_arhive'])
			$wpdb->query('DELETE FROM '.TB_arhive.' ;');
			
		if ($_POST['del_bots'])
			$wpdb->query('DELETE FROM '.TB_bots.' ;');
		
		if ($_POST['del_visits']){
			$wpdb->query('DELETE FROM '.TB_nstatistics.' ;');
			$wpdb->query('DELETE FROM '.TB_pages.' ;');
			$wpdb->query('DELETE FROM '.TB_refer.' ;');
		}
	}
?>
<form action="" method="post" enctype="multipart/form-data" name="nstats_settings1" id="nstats_settings1" style="margin-top: 25px;">
<table border="0" cellspacing="0" cellpadding="0" class="widefat post fixed" style="width: 98%">
  <thead>
  	<tr>
  		<th style="width: 1px;">&nbsp;</th>
  		<th style="width: 100px;">Settings</th>
  		<th style="width: 420px;">&nbsp;</th>
    </tr>
  </thead>  
  <tbody>
  	<tr>
  	  <td>&nbsp;</td>
      <td>Log Visitor Data</td>
      <td><label>
<input name="user_log" type="checkbox" id="user_log" checked="checked" disabled="disabled"/>        
Check to enable visitors access log
      </label></td>
  	</tr>
    <!--
  	<tr>
  	  <td>&nbsp;</td>
      <td>Log Feeds access</td>
      <td><label>
        <input type="checkbox" name="feed_log" id="feed_log" />
        Check to enable feed access log ()</label></td>
  	</tr>
    -->
  	<tr>
  	  <td>&nbsp;</td>
      <td>Log <a href="http://en.wikipedia.org/wiki/Web_crawler" target="_blank">Crawlers</a> &amp; <a href="http://en.wikipedia.org/wiki/Internet_bot" target="_blank">Bots</a></td>
      <td><label>
        <input type="checkbox" name="crawler_log" id="crawler_log" />
        Check to enable bots access log</label></td>
  	</tr>
  	<tr>
  	  <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
  	</tr>
  	<tr>
  	  <td>&nbsp;</td>
      <td colspan="2">
        <input type="submit" name="bt_save" id="bt_save" value="  Save Settings  " />
        <!--<input type="submit" name="bt_archive" id="bt_archive" value="  Add to archive  " title="Compress data and save database space"/>-->
      </td>
      </tr>
  </tfoot>
</table>
</form>

<form id="form1" name="form1" method="post" action="" style="margin-top: 25px">
<table border="0" cellspacing="0" cellpadding="0" class="widefat post fixed" style="width: 98%">
  <thead>
    <tr>
      <th width="10" style="width: 1px;">&nbsp;</th>
      <th width="964" style="width: 520px;">Clean Database</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td>&nbsp;</td>
      <td>
        <label>
          <input type="checkbox" name="del_arhive" id="del_arhive" />
          Flush Arhive Data</label>
      </td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td><label>
        <input type="checkbox" name="del_bots" id="del_bots" />
      Flush Bots Log Table</label></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td><label>
        <input type="checkbox" name="del_visits" id="del_visits" />
      Flush Visitors Log Tables</label></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td><label>
        <input type="submit" name="bt_clean" id="bt_clean" value="  Clean  " />
        <em>(delete all data from selected tables)</em></label></td>
    </tr>
    <tr>
      <td></tfoot></td>
    </tr>
  </tbody>
</table>
</form>