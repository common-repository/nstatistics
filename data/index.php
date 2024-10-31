<?php
if (!function_exists('add_action')){
    require_once('../../../../wp-config.php');
	
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo(get_option('blogname')); ?> - nStatistics 2</title>
</head>

<body>

<img src="../images/other/nStatistics_logo.jpg" alt="nStatistics - wordpress plugin" width="231" height="78" />
<div style="height: 7px; background-color: #7fbce8"></div>
<div style="margin:15px auto 15px auto; width: 800px">
&nbsp;<strong>nStatistics <?php echo('2'); ?></strong> for Wordpress blogs  generate the best statistics for  visitors and crawlers. Highly configurable and easy to use (<em>just install wait for visitors and watch collected statistics</em>). No programming or computer experience to use.
<br/>
Website statistics is the first step in SEO optimisation.</div>
<div style="margin:15px auto 15px auto; width: 800px">
  <p align="center"><a href="http://wiki.nisi.ro/my-wordpress-plugins/nstatistics-wordpress-plugin/" target="_self">nStatistics Plugin Home Page</a> (screenshots and informations)</p>
  <p align="center"><a href="http://wordpress.org/extend/plugins/nstatistics/" target="_self">Download nStatistics plugin for Wordpress</a></p>
  <p align="center"><strong>nStatistics 2</strong> plugin was installed on <?php echo(get_option('blogname')); ?>. <a href="<?php echo(get_option('siteurl')); ?>" title="Click here to visit <?php echo(get_option('blogname')); ?>" target="_self">Click here</a> to visit <?php echo(get_option('blogname')); ?>.</p>
</div>
<div style="margin:15px auto 15px auto; width: 800px; border-top:solid #7fbce8 2px;" align="right">
by Nisipeanu Mihai
</div>
</body>
</html>
