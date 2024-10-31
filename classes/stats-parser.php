<?php
/*
  options->regexp_browser 	array
         ->regexp_bot		array
		 ->server			array ($_SERVER)
		 ->visit	->ip
		 			->referrer
					->agent
					->pagina
					->this_url
					->agent_a	->agent-type
								->browser_name
								->browser_version
								->bot_name
								->bot_version
					
*/
if ( !defined( 'WP_CONTENT_DIR' ) )
	define( 'WP_CONTENT_DIR', ABSPATH . 'wp-content' );
if ( !defined( 'WP_PLUGIN_DIR' ) )
	define( 'WP_PLUGIN_DIR', WP_CONTENT_DIR . '/plugins' );

class StatsParser{
	var $options;

	public function StatsParser(){
		
	}
		
	public function setValue($name, $value){
		$this->options[$name] = $value;
	}
	
	public function getData(){
	
		if (!isset($this->options['server'])) return false;
		$this->options['visit'] = $this->setServerData($this->options['server']);
				
		include_once(WP_PLUGIN_DIR.'/nstatistics/data/regexp_browsers.data.php');
		include_once(WP_PLUGIN_DIR.'/nstatistics/data/regexp_bots.data.php');
		
		include_once('user-agent-parser.inc.php');
		if (!is_array($browser_user_agents_regexp))
			$this->saveOnLog('$browser_user_agents_regexp', 'Error');
		
		$uap = new UserAgentParser();
		$uap->setValue('regexp_browser', $browser_user_agents_regexp);
		$uap->setValue('regexp_bot', $bots_user_agents_regexp);
		
		$agent_array = array();
		if($uap->checkUserAgent($this->options['visit']['agent'])){
			$agent_array['agent-type'] = $uap->getValue('agent-type');
			
			if ($uap->getValue('browser_name')=='MSIE')
				$uap->setValue('browser_name', 'IE');
			$agent_array['browser_name'] = $uap->getValue('browser_name');
			$agent_array['browser_version'] = $uap->getValue('browser_version');
			$agent_array['bot_name'] = $uap->getValue('bot_name');
			$agent_array['bot_version'] = $uap->getValue('bot_version');	
		}else{
			// unknown agent
			$agent_array['agent-type'] = 0;
			$agent_array['browser_name'] = 'unknown';
			$agent_array['browser_version'] = '';
		}
		$this->options['visit']['agent_a'] = $agent_array;
		unset($uap);
	}
	
	public function saveData(){
		switch ($this->options['visit']['agent_a']['agent-type']){
			case 0: // unknown agent save for later 
				if (nStat_DEBUG)
					$this->saveOnLog($this->options['visit']['agent'], 'Unknown UserAgent');
			case 1: // found a browser
				$this->insertVisit();
				$this->insertPage();
				break;
			case 2: // this is a bot and it is saved in other table
				$saveVar = get_option('nStats_crawler_log');
				if ($saveVar != '0')
					$this->saveBot();
				break;
		}
	}
	
	private function saveOnLog($str, $source){
		global $wpdb;	
		$SQL = 'INSERT INTO '.TB_log.' SET `record` = "'.$str.'", `source` = "'.$source.'"';
		$wpdb->query($SQL);
	}
	
	private function saveBot(){
		global $wpdb;
		$visit = $this->options['visit'];
		
		/* Save Bot Statistics */	
		$SQL = 'SELECT id
				FROM '.TB_bots.' 
				WHERE (ip="'.$visit['ip'].'") and (DATE(tstamp) = DATE(CURRENT_TIMESTAMP))';
		$stat = $wpdb->get_results($SQL, ARRAY_A);
		
		if (isset($stat[0])){
			// update old visitor
			$SQL = 'UPDATE '.TB_bots.'
						SET count=count+1 
						WHERE id="'.$stat[0]['id'].'"';
			$wpdb->query($SQL);
		}else{
			// insert new visitor
			$SQL = 'INSERT INTO '.TB_bots.' 
					SET IP = "'.$visit['ip'].'", 
						bot = "'.$visit['agent_a']['bot_name'].'", 
						version = "'.$visit['agent_a']['bot_version'].'"';
			$wpdb->query($SQL);		
		}
		//echo(mysql_error());
		unset($visit);	
	}
	
	private function setServerData(){
		$output = array();
		
		$server = $this->options['server'];
		$output['ip'] 		= $server['REMOTE_ADDR'];		
		$output['referrer'] = '';
		if (isset($server['HTTP_REFERER']))
			$output['referrer'] = $server['HTTP_REFERER'];
			
		$output['agent']    = $server['HTTP_USER_AGENT'];
		$output['pagina']	= $server['REQUEST_URI']; //$_SERVER["PHP_SELF"];	
		$output['this_url'] = 'http://'.$server['HTTP_HOST'].$output['pagina'];
		
		// checks 
		$output['pagina'] = str_replace('%20', ' ', $output['pagina']);
		
		return $output;
	}	
	
	private function insertVisit(){
		global $wpdb;
		$visit = $this->options['visit'];
		
		/* Save Visitors Statistics */	
		$SQL = 'SELECT nstat_id
				FROM '.TB_nstatistics.' 
				WHERE (IP="'.$visit['ip'].'") and (DATE(tstamp) = DATE(CURRENT_TIMESTAMP))';
		$stat = $wpdb->get_results($SQL, ARRAY_A);
		
		if (isset($stat[0])){
			// update old visitor
			$SQL = 'UPDATE '.TB_nstatistics.'
						SET count=count+1 
						WHERE nstat_id="'.$stat[0]['nstat_id'].'"';
			$wpdb->query($SQL);
		}else{
			// get referral id / if not exist insert it
			$ref_id = $this->getReferalId($visit['referrer']);
			// insert new visitor
			$SQL = 'INSERT INTO '.TB_nstatistics.' 
						SET IP = "'.$visit['ip'].'", 
							browser = "'.$visit['agent_a']['browser_name'].'", 
							version = "'.$visit['agent_a']['browser_version'].'", 
							ref_id = "'.$ref_id.'"';
			$wpdb->query($SQL);		
		}
		//echo(mysql_error());
		unset($visit);
	}
	
	// count referals per day
	private function getReferalId($referal){
		global $wpdb;

		$SQL = 'SELECT ref_id
				FROM '.TB_refer.'
				WHERE (referal like "'.$referal.'") and (DATE(tstamp) = DATE(CURRENT_TIMESTAMP))';
		$stat = $wpdb->get_results($SQL, ARRAY_A);
		
		if (isset($stat[0])){
		//if ($row=mysql_fetch_array($stat)){	
			$SQL = 'UPDATE '.TB_refer.'
					SET count=count+1 
					WHERE ref_id="'.$stat[0]['ref_id'].'"';	
			$wpdb->query($SQL);	
			return $stat[0]['ref_id'];
		}else{
			$data = parse_url($referal);
			$domain = $data['scheme'].'://'.$data['host'].$data['path'];
			if (($domain == '://') || ($domain == ':///'))
				$domain = '';
			$SQL = 'INSERT INTO '.TB_refer.' 
					SET referal = "'.$referal.'", 
						domain = "'.$domain.'", 
						keyword = "'.$this->extractKeywords($data['query'], $referal).'"'; 
			$wpdb->query($SQL);	
			return mysql_insert_id();
		}	
	}
	
	public function extractKeywords($query, $referal){
		include_once(WP_PLUGIN_DIR.'/nstatistics/data/search_engine.data.php');
		
		foreach($search_engine as $item){
			if(eregi($item[0], $referal)){
				parse_str($query, $output);
				$list = $this->splitQuery($query);
				return $list[$item[1]];
			}
		}
		//return $query; IMP0001
		return '';
	}
	
	private function splitQuery($query){
		$ret = array();
		$temp = split('&', $query);
		foreach($temp as $item){
			$temp1 = split('=', $item);
			$ret[$temp1[0]] = $temp1[1];
		}
		// clean string
		$ret = str_replace('+',' ',$ret);
		$ret = str_replace('%20',' ',$ret);
		$ret = str_replace('%22','"',$ret);		
		$ret = str_replace('%23','#',$ret);		
		$ret = str_replace('%26','&',$ret);
		$ret = str_replace('%28','(',$ret);
		$ret = str_replace('%29',')',$ret);
		$ret = str_replace('%2A','*',$ret);
		$ret = str_replace('%2B','+',$ret);
		$ret = str_replace('%2b','+',$ret);
		$ret = str_replace('%2C',',',$ret);
		$ret = str_replace('%2F','/',$ret);


		$ret = str_replace('%3A',':',$ret);
		$ret = str_replace('%3B',';',$ret);
		$ret = str_replace('%3C','<',$ret);
		$ret = str_replace('%3D','=',$ret);
		$ret = str_replace('%3d','=',$ret);
		$ret = str_replace('%3E','>',$ret);
		$ret = str_replace('%3e','>',$ret);
		$ret = str_replace('%40','@',$ret);		

		$ret = str_replace('%5B','[',$ret);
		$ret = str_replace('%5D',']',$ret);
		$ret = str_replace('%5b','[',$ret);
		$ret = str_replace('%5d',']',$ret);

		$ret = str_replace('%7B','{',$ret);		
		$ret = str_replace('%7C','|',$ret);		
		$ret = str_replace('%7D','}',$ret);		
			
		return $ret;
	}

	private function insertPage(){
		global $wpdb;
		$visit = $this->options['visit'];
		
		/* Page statistics */
		$SQL = 'SELECT `page_id`
				FROM '.TB_pages.' 
				WHERE (`page_name` like "'.$visit['pagina'].'") and (DATE(tstamp) = DATE(CURRENT_TIMESTAMP))';
		$stat = mysql_query($SQL);
		
		if ($row=mysql_fetch_array($stat)){	
			mysql_query('UPDATE '.TB_pages.' 
						SET count = count + 1  
						WHERE page_id="'.$row['page_id'].'"');	
		}else{
			mysql_query('INSERT INTO '.TB_pages.'
						SET page_name = "'.$visit['pagina'].'"');	
		}	
		unset($visit);				
	}	
}

/**

*/
?>