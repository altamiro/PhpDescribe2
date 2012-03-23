<?php
  require 'phpdescribe/Color.php';
  require 'phpdescribe/SpecParser.php';
  require 'phpdescribe/SpecRunner.php';
  require 'phpdescribe/Spec.php';
  require 'phpdescribe/SpecFormatter.php';
  require 'phpdescribe/Expectation.php';

  require 'flat_tests.php';

$s = new Spec('two things');
$s_1 = new Spec('fail');
$s_1->set_code("expect(1)->toBe(1);");
$s_2 = new Spec('ok');
$s_2->set_code("expect(2)->toBe(2);");
$s->add_sub_spec($s_1);
$s->add_sub_spec($s_2);
SpecRunner::run($s);
echo SpecFormatter::format($s);

  // $filename = 'specs/phpdescribe.phpd';
  // $spec = SpecParser::parse(file_get_contents($filename));
  
  // SpecRunner::run($spec);
  // var_dump($spec);
  #echo $spec;

  