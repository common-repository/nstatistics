<?php

class CCrawlers{
	public function CCrawlers(){
	
	}
	
	public function generateCrawlerGraph($start, $end){
		if (($start == '') || ($end == '')){
			$start = date('Y-m-d', strtotime("-30 days"));
			$end = date('Y-m-d', strtotime("now"));
		}
		$SQL = 'SELECT date(tstamp) as dat, count(distinct(bot)) botnr, sum(`count`) as visits 
		        FROM '.TB_bots.' WHERE date(tstamp) >= "'.$start.'" AND date(tstamp) <= "'.$end.'" GROUP BY date(tstamp)';
		$rasp = mysql_query($SQL);
		if (!$rasp){
			return array();
		}
		$list = array();
		while ($row = mysql_fetch_array($rasp)){
			$list[] = $row;
		}
		return $list;
	}
	
	public function getRawCrawlersData($start, $end){
		if (($start == '') || ($end == '')){
			$start = date('Y-m-d', strtotime("-3 days"));
			$end = date('Y-m-d', strtotime("now"));
		}
 
 		$SQL = 'SELECT `IP`, date(tstamp) as dat, time(tstamp) as dat_time, count as nr, bot, version
				FROM '.TB_bots.' 
				WHERE date(tstamp) >= "'.$start.'" AND date(tstamp) <= "'.$end.'"
				ORDER BY date( tstamp ) DESC , bot ASC , ip ASC, time( tstamp ) ASC';
		$rasp = mysql_query($SQL);
		if (!$rasp){
			return array();
		}
		$list = array();
		$oldData = '';
		$oldBot = '';
		while ($row = mysql_fetch_array($rasp)){
			if ($oldData != $row['dat']){
				$oldData = $row['dat'];
				$list[] = array('data'=>$row['dat']);
			}
			if ($oldBot != $row['bot']){
				$oldBot = $row['bot'];
				$row['bot1'] = $row['bot'];
			}
			$list[] = $row;
		}
		return $list;			
	}
	
	public function getCrawlerList(){
	
	}
}
?>