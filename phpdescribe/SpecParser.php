<?php

class SpecParser {

	static function parse($text) {
		$debug = false;
		#$debug = true;

		$lines = explode("\n",$text);

		$mainSpec     = new Spec('main');
		$specs[0] = $mainSpec;
		$newSpec = false;
		$openNewSpec = false;
		$ident = null;
		if($debug) echo "----------------\n";
		$content = '';
		foreach($lines as $i=>$line) {
			$type = self::type($line);

			if($ident !== null) $last_identation = $ident;
			$ident = self::ident($line);

			if($type == 'error:tab') {
				throw new ParseException( 'Please use only blank spaces. Tab chars found on line ' . ($i+1) );
			}
			if($debug) echo "\n($type):".$line."\n";
			
			if($type !== 'close php') {
				if($debug) echo "@1";
				if($type === '#' && !$openNewSpec) {
					if($debug) echo "@2";
					$openNewSpec = true;
				}
				else if($type === '#' && $openNewSpec) {
					if($debug) echo "@3";
					if($ident != $last_identation) {
						throw new ParseException( 'Wrong identation on line ' . ($i+1) );
					}
					$openNewSpec = false;
					$content = '';
				}
				
				if($openNewSpec && $type === '# TITLE') {
					if($debug) echo "@4";
				  	if(!array_key_exists($ident, $specs)) {
						throw new ParseException( 'Wrong identation on line ' . ($i+1) );
					}
					if($ident != $last_identation) {
						throw new ParseException( 'Wrong identation on line ' . ($i) );
					}
					if($newSpec) {
				  		if($debug) echo "@5 --- " . $content;
				  		$newSpec->set_code(trim($content));
				  	}
				  	if($debug) echo "@6";
					$newSpec = new Spec(trim(substr( trim($line),1)) );
					$content = '';
					$specs[$ident]->add_sub_spec($newSpec);
					$specs[$ident+2] = $newSpec;
					
				}
				if($type === 's' || $type === ' ') {
				  	$content .= trim($line)."\n";
					if($debug) echo "@7 --- " . $content;
				}
			}
		}
		if($debug) echo "@8";
		if($newSpec) {
			if($debug) echo "@9";
	  		$newSpec->set_code(trim($content));
	  	}
	  	return $specs[0];
	}

	static function type($line) {
	  if( preg_match('/^ *\t.*$/', $line) ) return 'error:tab';
      if( preg_match('/^ *#+ *$/', $line) ) return '#';
      if( preg_match('/^ *#+.*$/', $line) ) return '# TITLE';
      if( preg_match('/^^ *\?>/', $line) ) return 'close php';
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