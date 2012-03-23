<?php

##########################
# Expectation
##########################

#  expect() returns an Expectation
#......................................................................................

if( get_class(expect(1)) !== 'Expectation' ) {
	throw new Exception ('expect() returns an Expectation');
}

#  Expectation::toBe compare two values and throws an Exception if they are different
#......................................................................................

$ok = false;
try{
  expect(1)->toBe(2);
}catch(FailedExpectationException $e){
  $ok = true;
}

if(!$ok) throw new Exception ('expect() should throw an Expectation');

#  Expectation::toBe compare two values and do not throw an Exception if they are equal
#......................................................................................

$ok = true;
try{
  expect(1)->toBe(1);
}catch(Exception $e){
  $ok = false;
}

if(!$ok) throw new Exception ('expect() should not throw an Expectation');

#  Expectation::toBe does an strict comparation
#..............................................

$ok = false;
try{
  expect(1)->toBe("1");
}catch(FailedExpectationException $e){
  $ok = true;
}

if(!$ok) throw new Exception ('expect() should throw an Expectation');

#######
# Spec
#######

#  is initialized with a name
#........................

$s = new Spec('ball');
expect($s->get_name())->toBe('ball');

#  add_sub_spec adds child specs
#...............................

$s = new Spec('ball bounces');
$s_1 = new Spec('ball bounces fast');
$s_2 = new Spec('ball bounces slow');
$s->add_sub_spec($s_1);
$s->add_sub_spec($s_2);
expect($s->get_sub_specs())->toBe(Array($s_1,$s_2));

#  set_code sets the code to be run
#..................................
$s = new Spec('ball bounces');
$s->set_code("\$a=2;\n\$a = \$a + 1;\n");
expect($s->get_code())->toBe("\$a=2;\n\$a = \$a + 1;\n");

#  get_result tells if a spec has succeded or failed or not run (null)
#.....................................................................
$s = new Spec('ball bounces');
expect($s->get_result())->toBe(null);
$s->set_result(true);
expect($s->get_result())->toBe(true);
$s->set_result(false);
expect($s->get_result())->toBe(false);

	

##############
# Spec Runner
##############

# runs the code inside a spec and sets a positive result if no error is found
#............................................................................
$s = new Spec('do something');
$s->set_code("\$a=2;\n\$a = \$a + 1;\n");
SpecRunner::run($s);
expect( $s->get_result() )->toBe(true);

# sets a negative result if expectation fails
#............................................
$s = new Spec('one should be two');
$s->set_code("\expect(1)->toBe(2);");
SpecRunner::run($s);
expect( $s->get_result() )->toBe(false);

# sets a negative result if exception is thrown and not catched inside the spec
#..............................................................................
$s = new Spec('throw exception');
$s->set_code("throw new Exception();");
SpecRunner::run($s);
expect( $s->get_result() )->toBe(false);

# sets a negative result if error is found inside the spec
#.........................................................
$s = new Spec('undeclared vars');
$s->set_code("\$a = \$b + \$c;");
SpecRunner::run($s);
expect( $s->get_result() )->toBe(false);

# if thw spec has sub_specs, runs them also
#........................
$s = new Spec('two things');
$s_1 = new Spec('fail');
$s_1->set_code("\$a = \$b + \$c;");
$s_2 = new Spec('ok');
$s_2->set_code("\$a = 2;");
$s->add_sub_spec($s_1);
$s->add_sub_spec($s_2);

SpecRunner::run($s);
$sub_specs = $s->get_sub_specs();
expect($sub_specs[0]->get_result())->toBe(false);
expect($sub_specs[1]->get_result())->toBe(true);

# if one sub_spec fail the parent fails
#........................................
$s = new Spec('two things');
$s_1 = new Spec('fail');
$s_1->set_code("expect(2)->toBe(1);");
$s_2 = new Spec('ok');
$s_2->set_code("expect(1)->toBe(1);");
$s->add_sub_spec($s_1);
$s->add_sub_spec($s_2);
SpecRunner::run($s);
expect($s->get_result())->toBe(false);

# if all sub_spec succed the parent succed
#........................................
$s = new Spec('two things');
$s_1 = new Spec('ok 1 ');
$s_1->set_code("expect(1)->toBe(1);");
$s_2 = new Spec('ok 2 ');
$s_2->set_code("expect(2)->toBe(2);");
$s->add_sub_spec($s_1);
$s->add_sub_spec($s_2);
SpecRunner::run($s);
expect($s->get_result())->toBe(true);

####################
#  SpecFormatter
####################

#  shows a gray string if has not run
#.........................................
$s = new Spec('ball bounces');
$f = SpecFormatter::format($s);
expect($f)->toBe(Color::dark_gray('ball bounces')."\n");

#  shows a green string if has succeded
#.........................................
$s = new Spec('ball bounces');
$s->set_result(true);
$f = SpecFormatter::format($s);
expect($f)->toBe(Color::green('ball bounces')."\n");

# shows a red string and a message preceded by [FAIL] if has failed
# the message is set with a second optional parameter to set_result
#.........................................
$s = new Spec('ball bounces');
$s->set_result(false,"messagexpto");
$f = SpecFormatter::format($s);
expect($f)->toBe(Color::red("ball bounces")."\n".Color::red("[FAIL] messagexpto")."\n");




