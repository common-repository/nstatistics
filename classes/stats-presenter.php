<?php
include('log.inc.php');
include('crawlers.inc.php');

class StatsPresenter{
	private $logc;
	public $crawlers;
	public $today;
	public $alltime;
	
	public function StatsPresenter(){
		$this->logc = new nSLogClass();
		$this->crawlers = new CCrawlers();
		$this->today = array();
	}
	
	private function logError($strError, $ftname){
		if (strlen($strError)>0)
			$this->logc->logMessage($strError, 'StatsPresenter::'.$ftname);
	}
	
	public function getTodayStats(){
		global $wpdb;
		//$wpdb->prefix . ‘capabilities’;

		$this->today['vizits']=0;
		$this->today['views']=0;
		$this->today['bots']=0;
		$this->today['bots_views']=0;
		$this->today['browsers'] = array();
		
		$SQL = 'SELECT COUNT(IP) as visits, sum(count) as views
				FROM '.TB_nstatistics.'
				WHERE date(tstamp) = date(now()) 
				GROUP BY date(tstamp) 
				LIMIT 1';
		$rez = $wpdb->get_results($SQL);		
		if (isset($rez[0])){
			$this->today['vizits'] = $rez[0]->visits;
			$this->today['views']  = $rez[0]->views;
			//$this->today['bots']   = $rez->visits;
		}
		
		$SQL = 'SELECT browser, COUNT(browser) as nr, version
				FROM '.TB_nstatistics.'
				WHERE date(tstamp) = date(now()) 
				GROUP BY browser, version
				ORDER BY nr DESC, browser ASC, version DESC 
				LIMIT 10';
		$rez = $wpdb->get_results($SQL);
		foreach($rez as $item){
			$this->today['browsers'][] = array($item->browser, $item->nr, $item->version);
		}
		
		$SQL = 'SELECT count(distinct(bot)) as visits, sum(count) as views 
				FROM '.TB_bots.' WHERE date(tstamp) = date(now()) 
				GROUP BY date(tstamp) 
				LIMIT 1';
		$rez = $wpdb->get_results($SQL);
		//['error'] = mysql_error();
		//$this->today['sql'] = $SQL;		
		
		if (isset($rez[0])){
			$this->today['bots'] = $rez[0]->visits;
			$this->today['bots_views']  = $rez[0]->views;
		}		
	}
	
	public function getAlltimeStats(){
		$this->alltime['visits']=0;		
		$this->alltime['views']=0;
		$this->alltime['bots']=0;		
		$this->alltime['browsers'] = array();	
	}

	public function getBlowserLogo($browser, $version){
		if (strtolower($browser) == 'firefox')
			return '<img src="../wp-content/plugins/nstatistics/images/browser/firefox.png" alt="firefox"/>';
		if ((strtolower($browser) == 'ie') || ($browser == 'MSIE')){
			if (((float)$version) <= 6 )
				return '<img src="../wp-content/plugins/nstatistics/images/browser/ie.png" alt="internet explorer"/>';
			else
				return '<img src="../wp-content/plugins/nstatistics/images/browser/ie7.png" alt="internet explorer"/>';
		}
		if (strtolower($browser) == 'mozilla')
			return '<img src="../wp-content/plugins/nstatistics/images/browser/mozilla.png" alt="mozilla"/>';
		if (strtolower($browser) == 'chrome')
			return '<img src="../wp-content/plugins/nstatistics/images/browser/chrome.png" alt="chrome"/>';
		if (strtolower($browser) == 'opera')
			return '<img src="../wp-content/plugins/nstatistics/images/browser/opera.png" alt="opera"/>';
		if (strtolower($browser) == 'netscape')
			return '<img src="../wp-content/plugins/nstatistics/images/browser/netscape.png" alt="netscape"/>';

		if (strtolower($browser) == 'flock')
			return '<img src="../wp-content/plugins/nstatistics/images/browser/flock.png" alt="flock"/>';
		if (strtolower($browser) == 'safari')
			return '<img src="../wp-content/plugins/nstatistics/images/browser/safari.png" alt="safari"/>';

		
		// return unknown browser
		return '<img src="../wp-content/plugins/nstatistics/images/browser/unknown.png" alt="unknown browser"/>';
	}
	
	public function generateLastDaysStats($nrdays){
		$stats = array();
		$SQL = 'SELECT COUNT(IP) as visits, sum(count) as pageviews, date(tstamp) as reg_date
					FROM '.TB_nstatistics.' 
					GROUP BY date(tstamp) 
					ORDER BY date(tstamp) desc 
					LIMIT '.$nrdays;
		$rasp = mysql_query($SQL);
		
		$this->logError(mysql_error(), 'generateLastDaysStats');
		$rowCount = 0;
		while ($row = mysql_fetch_array($rasp)){
			$stats[] = $row;
			$rowCount++;
		}
		
		if (($nrdays-$rowCount)<=0)
			return $stats;
		$SQL = 'SELECT visits, pageviews, reg_date
					FROM '.TB_arhive.'
				 	ORDER BY reg_date DESC 
					LIMIT '.($nrdays-$rowCount);
		$rasp = mysql_query($SQL);
		$this->logError(mysql_error(), 'generateLastDaysStats');
		
		while ($row = mysql_fetch_array($rasp)){
			$stats[] = $row;
		}
		return $stats;
	}
		
	public function generateAllTimeStats(){
		$sw = false;
		$SQL = 'SELECT COUNT(IP) as visits, sum(count) as pagevizits, date(tstamp) as data
				FROM '.TB_nstatistics.'
				group by nstat_id >0 
				LIMIT 1';
		$rasp = mysql_query($SQL);
		if ($row = mysql_fetch_array($rasp)){
			$sw = true;
		}
		
		$SQL = 'SELECT SUM(visits) as visits1, sum(pageviews) as pagevizits
				FROM '.TB_arhive.'
				LIMIT 1';
		$rasp = mysql_query($SQL);
		if ($row1 = mysql_fetch_array($rasp)){
			if ($sw){
				$row['visits'] += $row1['visits1'];
				$row['pagevizits'] += $row1['pagevizits'];
			}else{
				$row = array('visits'=>$row1['visits1'], 'pagevizits'=>$row1['pagevizits']);
			}
			return $row;
		}else{
			if ($sw)
				return $row;
		}
		return false;
	}
	
	public function getVisitorsGraph($start, $end){
		if (($start == '') || ($end == '')){
			$start = date('Y-m-d', strtotime("-45 days"));
			$end = date('Y-m-d', strtotime("now"));
		}	
	
		$stats = array();
		$SQL = 'SELECT COUNT(IP) as visits, sum(count) as pageviews, date(tstamp) as reg_date
					FROM '.TB_nstatistics.' 
					WHERE date(tstamp) >= "'.$start.'" AND date(tstamp) <= "'.$end.'"
					GROUP BY date(tstamp) 
					ORDER BY date(tstamp) desc';
		$rasp = mysql_query($SQL);
		//echo $SQL;
		$this->logError(mysql_error(), 'getVisitorsGraph');
		$rowCount = 0;
		while ($row = mysql_fetch_array($rasp)){
			$stats[] = $row;
			$rowCount++;
		}
		
		$SQL = 'SELECT visits, pageviews, reg_date
					FROM '.TB_arhive.'
					WHERE reg_date >= "'.$start.'" AND reg_date <= "'.$end.'"
				 	ORDER BY reg_date DESC ';
		//echo $SQL;	
		$rasp = mysql_query($SQL);
		$this->logError(mysql_error(), 'getVisitorsGraph');
		
		while ($row = mysql_fetch_array($rasp)){
			$stats[] = $row;
		}
		return $stats;
	}				
	
	public function getRowData($lastEntriesNr, $start, $end){
		if (($start == '') || ($end == '')){
			$start = date('Y-m-d', strtotime("now"));
			$end = date('Y-m-d', strtotime("now"));
		}	
		if (!isset($lastEntriesNr))
			$lastEntriesNr = 25;
		$SQL = 'SELECT `IP`, date(t1.tstamp) as dat, time(t1.tstamp) as dat_time, t1.count as nr, browser, version, domain, keyword
				FROM '.TB_nstatistics.' as t1
				JOIN '.TB_refer.' as t2 ON t1.ref_id = t2.ref_id 
				WHERE date(t1.tstamp) >= "'.$start.'" AND date(t1.tstamp) <= "'.$end.'"
				ORDER BY t1.tstamp DESC
				LIMIT '.$lastEntriesNr;
		$rasp = mysql_query($SQL);
		if (!$rasp){
			//echo(mysql_error().'<br/>'.$SQL);
			return array();
		}
		$list = array();
		$oldData = '';
		while ($row = mysql_fetch_array($rasp)){
			if ($oldData != $row['dat']){
				$oldData = $row['dat'];
				$list[] = array('data'=>$row['dat']);
			}
			$list[] = $row;
		}
		return $list;	
	}

	public function getTodayTraficSources($items=false){
		if (!is_int($items))
			$items = 10;
			
		return $this->getTraficSources($items, '', '', 0);
	}	
	public function getTraficSources($items=false, $start, $end, $from=0){
		$limit = '';
		if (is_int($items))
			$limit = 'LIMIT '.$from.', '.$items;
			
		if (($start == '') || ($end == '')){
			$start = date('Y-m-d', strtotime("now"));
			$end = date('Y-m-d', strtotime("now"));
		}	

		$SQL = 'SELECT domain, sum(count) as nr FROM '.TB_refer.'
		        WHERE date(tstamp) >= "'.$start.'" AND date(tstamp) <= "'.$end.'"
				GROUP by domain
				ORDER by nr DESC '.$limit;
				
		$rasp = mysql_query($SQL); $sw2 = false;
		$sw = false;
		while ($row = mysql_fetch_array($rasp)){
			$temp = $this->getBackground($sw2); $sw2 = $temp['sw'];
			if ($row['domain']=="")
				$code .= "<div class='nstats_row' style='".$temp['bg']."'>[".$row['nr']."] Direct Traffic </div>";
			else
				$code .= "<div class='nstats_row' style='".$temp['bg']."'>[".$row['nr']."] <a href='".$row['domain']."' target='_blanc'>".$row['domain']."</a></div>";
			$sw = true;
		}
		if (!$sw)
			$code = $code."<center>Stats unavailble</center>";
		return $code;	
	}
	
	private function getBackground($sw){
		$temp = array();
		if ($sw){
			$temp['sw'] = false;
			$temp['bg'] = 'background-color: #F3F3F3;';
		}else{
			$temp['sw'] = true;
			$temp['bg'] = 'background-color: #FFFFFF;';
		}	
		return $temp;
	}
		
	public function getTodayAccessedPages($items=false){
		if (!is_int($items))
			$items = 10;
			
		return $this->getAccessedPages($items, '', '');
	}
			
	public function getAccessedPages($items=false, $start, $end){
		$limit = '';
		if (is_int($items))
			$limit = 'LIMIT '.$items;
			
		if (($start == '') || ($end == '')){
			$start = date('Y-m-d', strtotime("now"));
			$end = date('Y-m-d', strtotime("now"));
		}	
			
		$SQL = 'SELECT page_name, count FROM '.TB_pages.'
		        WHERE date(tstamp) >= "'.$start.'" AND date(tstamp) <= "'.$end.'"
				ORDER by count DESC '.$limit;
		$rasp = mysql_query($SQL);
		$sw = false; $sw2=false;
		while ($row = mysql_fetch_array($rasp)){
			$temp = $this->getBackground($sw2); $sw2 = $temp['sw'];
			$code = $code."<div class='nstats_row' style='".$temp['bg']."'>[".$row['count']."] <a href='".$row['page_name']."' target='_blanc'>".$row['page_name']."</a></div>";
			$sw = true;
		}
		if (!$sw)
			$code = $code."<center>Stats unavailble</center>";
		return $code;	
	}
	
	public function getKewords($limit, $start, $end){		
		if (($start == '') || ($end == '')){
			$start = date('Y-m-d', strtotime("now"));
			$end = date('Y-m-d', strtotime("now"));
		}
		if ($limit == '')
			$limit = 15;		
		$SQL = 'SELECT keyword, count(keyword) as nr FROM '.TB_refer.'
				WHERE date(tstamp) >= "'.$start.'" AND date(tstamp) <= "'.$end.'" and keyword not like ""
				GROUP BY keyword 
				ORDER by nr desc
				LIMIT '.$limit;

		$rasp = mysql_query($SQL);
		$sw = false; $sw2=true;
		//$code = 'View '.$this->selectBox('nstats_tk', $range).'<br />';
		while ($row = mysql_fetch_array($rasp)){
			$temp = $this->getBackground($sw2); $sw2 = $temp['sw'];
			$code = $code.'<div class="nstats_row" style="'.$temp['bg'].'">['.$row['nr'].'] '.$row['keyword'].'</div>';
			$sw = true;
		}
		if (!$sw)
			$code = $code.'<center>Stats unavailble</center>';
		return $code;	
	}
	
	public function getVisitorsByHour($start, $end){
		if (($start == '') || ($end == '')){
			$start = date('Y-m-d', strtotime("now"));
			$end = date('Y-m-d', strtotime("now"));
		}
		$SQL = "SELECT distinct(DATE_FORMAT(tstamp, '%H')) as dat, COUNT(IP) as visits, sum(count) as pageviews
				FROM ".TB_nstatistics." 
				WHERE date(tstamp) >= '".$start."' AND date(tstamp) <= '".$end."'
				group by DATE_FORMAT(tstamp, '%H')
				order by time(tstamp) asc";
		$rasp = mysql_query($SQL);
		$this->logError(mysql_error(), 'getVisitorsByHour');
		$stats = array();
		while ($row = mysql_fetch_array($rasp)){
			$stats[] = $row;
		}
		return $stats;
	}
}

?>