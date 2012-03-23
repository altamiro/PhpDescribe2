<?php

class SpecParser {

	static function parse($text) {
		$debug = false;

		$lines = explode("\n",$text);

		$mainSpec     = new Spec('main');
		$specs[0] = $mainSpec;
		$newSpec = false;
		$openNewSpec = false;
		$ident = null;
		if($debug) echo "----------------\n";
		foreach($lines as $i=>$line) {
			$type = self::type($line);

			if($ident !== null) $last_identation = $ident;
			$ident = self::ident($line);

			if($type == 'error:tab') {
				throw new ParseException( 'Please use only blank spaces. Tab chars found on line ' . ($i+1) );
			}
			if($debug) echo "($type):".$line."\n";
			if($type !== ' ') {
				if($debug) echo "@1";
				if($type === '=' && !$openNewSpec) {
					if($debug) echo "@2";
					$openNewSpec = true;
				}
				else if($type === '=' && $openNewSpec) {
					if($debug) echo "@3";
					if($ident != $last_identation) {
						throw new ParseException( 'Wrong identation on line ' . ($i+1) );
					}
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
					
					if(!array_key_exists($ident, $specs)) {
						throw new ParseException( 'Wrong identation on line ' . ($i+1) );
					}
					if($ident != $last_identation) {
						throw new ParseException( 'Wrong identation on line ' . ($i) );
					}

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
	  if( preg_match('/^ *\t.*$/', $line) ) return 'error:tab';
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

class ParseException extends Exception{}