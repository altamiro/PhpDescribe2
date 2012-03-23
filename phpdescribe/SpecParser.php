<?php

class SpecParser {

	static function parse($text) {
		$debug = false;

		$lines = explode("\n",$text);

		$mainSpec     = new Spec('main');
		$specs[0] = $mainSpec;
		$newSpec = false;
		$openNewSpec = false;
		if($debug) echo "----------------\n";
		foreach($lines as $line) {
			$type = self::type($line);
			if($debug) echo "($type):".$line."\n";
			if($type !== ' ') {
				if($debug) echo "@1";
				if($type === '=' && !$openNewSpec) {
					if($debug) echo "@2";
					$openNewSpec = true;
				}
				else if($type === '=' && $openNewSpec) {
					if($debug) echo "@3";
					$openNewSpec = false;
					$content = '';
				}
				
				if($type === 's') {
					if($debug) echo "@4";
				  if($openNewSpec) {
				  	if($debug) echo "@5";
				  	if($newSpec) {
				  		if($debug) echo "@6";
				  		$newSpec->set_code($content);
				  	}
					
					$newSpec = new Spec(trim($line));
					$ident = self::ident($line);
					$specs[$ident]->add_sub_spec($newSpec);
					$specs[$ident+2] = $newSpec;
					$content = '';
				  }
				  else {
				  	if($debug) echo "@7";
				  	$content .= trim($line);
				  }
				}
			}
		}
		if($debug) echo "@8";
		if($newSpec) {
			if($debug) echo "@9";
	  		$newSpec->set_code($content);
	  	}
	  	return $specs[0];
	}

	static function type($line) {
      if( preg_match('/^ *=+ *$/', $line) ) return '=';
      if( preg_match('/^ *$/', $line) ) return ' ';
      return 's';		
	}

	static function ident($line) {
	  $ident = 0;
      for($i = 0; $i < strlen($line); $i++) {
      	if($line[$i] === ' ') {
	      	$ident++;
	    }else {
	    	break;
	    }
      }
      return $ident;
	}
}