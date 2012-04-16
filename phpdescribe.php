<?php

require 'phpdescribe/Color.php';
require 'phpdescribe/SpecParser.php';
require 'phpdescribe/SingleSpecRunner.php';
require 'phpdescribe/SpecRunner.php';
require 'phpdescribe/Spec.php';
require 'phpdescribe/SpecFormatter.php';
require 'phpdescribe/Expectation.php';

try {
  require 'flat_tests.php';  # flat tests serve for testing phpdescribe without phpdescribe 
}
catch(Exception $e){
  echo Color::red("!!!!!!!!!!! ERROR: EXCEPTION THROWN ON FLAT TESTS \n message: " . $e->getMessage());
  echo Color::red("\n trace: \n" . $e->getTraceAsString());
}

 $filename = 'specs/phpdescribe.spec';
 $text = file_get_contents($filename);
 $spec = SpecParser::parse($text);

 SpecRunner::run($spec);
 $formatter = new SpecFormatter();
 echo $formatter->format($spec);