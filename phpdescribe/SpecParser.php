<?php

class SpecParser {

	static function parse($text) {
		
		$lines = explode("\n",$text);

		$mainSpec     = new Spec('main');
		$specs[0] = $mainSpec;
		$newSpec = false;
		$openNewSpec = false;

		foreach($lines as $line) {
			$type = self::type($line);
			if($type !== ' ') {
				if($type === '=' && !$openNewSpec) {
					$openNewSpec = true;
				}
				else if($type === '=' && $openNewSpec) {
					$openNewSpec = false;
					$content = '';
				}
				
				if($type === 's') {
				  if($openNewSpec) {
				  	if($newSpec) {
				  		$newSpec->set_code($content);
				  	}
					
					$newSpec = new Spec(trim($line));
					$ident = self::ident($line);
					$specs[$ident]->add_sub_spec($newSpec);
					$specs[$ident+2] = $newSpec;
					$content = '';
				  }
				  else {
				  	$content .= $line;
				  }
				}
			}
		}
		if($newSpec) {
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