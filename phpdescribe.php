<?php

require 'phpdescribe/Color.php';
require 'phpdescribe/SpecParser.php';
require 'phpdescribe/SpecRunner.php';
require 'phpdescribe/Spec.php';
require 'phpdescribe/SpecFormatter.php';
require 'phpdescribe/Expectation.php';
#require 'flat_tests.php';
$filename = 'specs/phpdescribe2.phpd';
$text = file_get_contents($filename);
$spec = SpecParser::parse($text);
SpecRunner::run($spec);
echo SpecFormatter::format($spec);