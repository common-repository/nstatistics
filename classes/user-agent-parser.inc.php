<?php
class UserAgentParser {
	var $options;
	
	public function user_agent_parser(){
		$this->options = array();
	}
	
	public function setValue($name, $value){
		$this->options[$name] = $value;
	}
	
	public function getValue($name){
		if (isset($this->options[$name]))
			return $this->options[$name];
			
		return '';
	}
	
	public function checkUserAgent($us_string){
		$sw = false;	
		$this->options['User_Agent'] = $us_string;
		
		if ((!isset($this->options['regexp_browser'])) || (!is_array($this->options['regexp_browser']))) return false;
		if ((!isset($this->options['regexp_bot'])) || (!is_array($this->options['regexp_bot']))) return false;		

		if ($this->checkBrowserAgent())
			return true;
			
		if ($this->checkBotAgent())
			return true;
			
		$this->options['agent-type'] = 0;	// unknown identity
		return false;
	}
	
	private function checkBrowserAgent(){
		$sw = false;	
		
		foreach ($this->options['regexp_browser'] as $regexp_browser){
			$regexp = $regexp_browser;
			if (ereg($regexp, $this->options['User_Agent'], $browser)) { 
				$browser_version = $browser[2]; 
				$browser_name = $browser[1]; 
				$sw = true;
				break;
			}
		}			
		if ($sw){
			$this->options['browser_name'] = $browser_name;
			$this->options['browser_version'] = $browser_version;
			$this->options['agent-type'] = 1;	// browser
			return true;	
		}	
		return false;
	}
	
	private function checkBotAgent(){
		$sw = false;
		foreach ($this->options['regexp_bot'] as $regexp_bot){
			$regexp = $regexp_bot;
			if (ereg($regexp, $this->options['User_Agent'], $bot)) { 
				$bot_version = $bot[2]; 
				$bot_name = $bot[1]; 
				$sw = true;
				
				// update for safari
				if (strtolower($bot_name) == 'safari') 
					$bot_version = $this->getSafariVersion();
					
				break;
			}
		}
		
		if ($this->options['User_Agent'] == ''){
			$bot_name = 'empty User Agent';
			$bot_version =''; $sw = true; 
		}		
		if ($sw){
			$this->options['bot_name'] = $bot_name;
			$this->options['bot_version'] = $bot_version;
			$this->options['agent-type'] = 2;	// bot
			
			return true;	
		}

			
		return false;	
	}
	
	private function getSafariVersion(){
		if (ereg('(Version)/([0-9].[0-9])', $this->options['User_Agent'], $version)) { 
			return $version[2]; 
		}
		return '';
	}
}
?>