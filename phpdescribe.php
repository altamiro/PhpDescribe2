<?php

require 'phpdescribe/Color.php';
require 'phpdescribe/SpecParser.php';
require 'phpdescribe/SpecRunner.php';
require 'phpdescribe/Spec.php';
require 'phpdescribe/SpecFormatter.php';
require 'phpdescribe/Expectation.php';
require 'flat_tests.php';

#require ('temp/serialized_spec_1334322495_28158.php');

 $filename = 'specs/phpdescribe.phpd';
 $text = file_get_contents($filename);
 $spec = SpecParser::parse($text);

 SpecRunner::run($spec);
 echo SpecFormatter::format($spec);