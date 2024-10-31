<?php
	$nStatTables = array();

$nStatTables['TB_nstatistics'] = "	
CREATE TABLE `".TB_nstatistics."` (
  `nstat_id` int(11) NOT NULL auto_increment,
  `IP` varchar(15) character set utf8 default '000.000.000.000',
  `tstamp` timestamp NULL default CURRENT_TIMESTAMP,
  `count` smallint(6) default '1',
  `browser` varchar(10) character set utf8 default NULL,
  `version` varchar(3) character set utf8 default NULL,
  `ref_id` int(11) default NULL,
  `device` varchar(10) character set utf8 NOT NULL default 'PC',
  PRIMARY KEY  (`nstat_id`),
  KEY `ip_idx` (`IP`,`tstamp`),
  KEY `afisari` (`count`,`tstamp`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_romanian_ci COMMENT='file statistics';
";

$nStatTables['TB_arhive'] = "	
CREATE TABLE `".TB_arhive."` (
  `id` int(11) NOT NULL auto_increment,
  `visits` mediumint(7) NOT NULL default '0',
  `pageviews` int(11) NOT NULL default '0',
  `reg_date` date default NULL,
  `browser1` varchar(13) collate utf8_romanian_ci default NULL,
  `browser2` varchar(13) collate utf8_romanian_ci default NULL,
  `browser3` varchar(13) collate utf8_romanian_ci default NULL,
  `best_page1` varchar(255) collate utf8_romanian_ci default NULL,
  `best_page2` varchar(255) collate utf8_romanian_ci default NULL,
  `best_page3` varchar(255) collate utf8_romanian_ci default NULL,
  `best_domain1` varchar(255) collate utf8_romanian_ci default NULL,
  `best_domain2` varchar(255) collate utf8_romanian_ci default NULL,
  `best_domain3` varchar(255) collate utf8_romanian_ci default NULL,
  `keyword1` varchar(255) collate utf8_romanian_ci default NULL,
  `keyword2` varchar(255) collate utf8_romanian_ci default NULL,
  `keyword3` varchar(255) collate utf8_romanian_ci default NULL,
  PRIMARY KEY  (`id`),
  KEY `idx_date` (`reg_date`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_romanian_ci;
";

$nStatTables['TB_bots'] = "	
CREATE TABLE `".TB_bots."` (
  `id` int(11) NOT NULL auto_increment,
  `ip` varchar(15) character set utf8 default '000.000.000.000',
  `tstamp` timestamp NULL default CURRENT_TIMESTAMP,
  `count` smallint(6) default '1',
  `bot` varchar(30) character set utf8 default NULL,
  `version` varchar(3) character set utf8 default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_romanian_ci;
";

$nStatTables['TB_pages'] = "	
CREATE TABLE `".TB_pages."` (
  `page_id` int(11) NOT NULL auto_increment,
  `page_name` varchar(255) character set utf8 collate utf8_romanian_ci default NULL,
  `tstamp` timestamp NULL default CURRENT_TIMESTAMP,
  `count` mediumint(9) default '1',
  PRIMARY KEY  (`page_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
";

$nStatTables['TB_refer'] = "	
CREATE TABLE `".TB_refer."` (
  `ref_id` int(11) NOT NULL auto_increment,
  `referal` text character set utf8 collate utf8_romanian_ci,
  `domain` varchar(255) character set utf8 collate utf8_romanian_ci default NULL,
  `keyword` varchar(255) character set utf8 collate utf8_romanian_ci default NULL,
  `tstamp` timestamp NULL default CURRENT_TIMESTAMP,
  `count` mediumint(9) NOT NULL default '1',
  PRIMARY KEY  (`ref_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
";	
	
$nStatTables['TB_log'] = "	
CREATE TABLE `".TB_log."` (
  `id` int(11) NOT NULL auto_increment,
  `record` text,
  `source` varchar(255) default NULL COMMENT 'who record it',
  `tstamp` timestamp NULL default CURRENT_TIMESTAMP,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
";

$nStatTables['TB_pages_update1'] = "ALTER TABLE `".TB_pages."` CHANGE `page_name` `page_name` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL";

?>