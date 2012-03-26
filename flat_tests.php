<?php

// ##########################
// # Expectation
// ##########################

// #  expect() returns an Expectation
// #......................................................................................

// if( get_class(expect(1)) !== 'Expectation' ) {
// 	throw new Exception ('expect() returns an Expectation');
// }

// #  Expectation::toBe compare two values and throws an Exception if they are different
// #......................................................................................

// $ok = false;
// try{
//   expect(1)->toBe(2);
// }catch(FailedExpectationException $e){
//   $ok = true;
// }

// if(!$ok) throw new Exception ('expect() should throw an Expectation');

// #  Expectation::toBe compare two values and do not throw an Exception if they are equal
// #......................................................................................

// $ok = true;
// try{
//   expect(1)->toBe(1);
// }catch(Exception $e){
//   $ok = false;
// }

// if(!$ok) throw new Exception ('expect() should not throw an Expectation');

// #  Expectation::toBe does an strict comparation
// #..............................................

// $ok = false;
// try{
//   expect(1)->toBe("1");
// }catch(FailedExpectationException $e){
//   $ok = true;
// }

// if(!$ok) throw new Exception ('expect() should throw an Expectation');

// #######
// # Spec
// #######

// #  is initialized with a name
// #........................

// $s = new Spec('ball');
// expect($s->get_name())->toBe('ball');

// #  add_sub_spec adds child specs
// #...............................

// $s = new Spec('ball bounces');
// $s_1 = new Spec('ball bounces fast');
// $s_2 = new Spec('ball bounces slow');
// $s->add_sub_spec($s_1);
// $s->add_sub_spec($s_2);
// expect($s->get_sub_specs())->toBe(Array($s_1,$s_2));

// #  set_code sets the code to be run
// #..................................
// $s = new Spec('ball bounces');
// $s->set_code("\$a=2;\n\$a = \$a + 1;\n");
// expect($s->get_code())->toBe("\$a=2;\n\$a = \$a + 1;\n");

// #  get_result tells if a spec has succeded or failed or not run (null)
// #.....................................................................
// $s = new Spec('ball bounces');
// expect($s->get_result())->toBe(null);
// $s->set_result(true);
// expect($s->get_result())->toBe(true);
// $s->set_result(false);
// expect($s->get_result())->toBe(false);

	

// ##############
// # Spec Runner
// ##############

// # runs the code inside a spec and sets a positive result if no error is found
// #............................................................................
// $s = new Spec('do something');
// $s->set_code("\$a=2;\n\$a = \$a + 1;\n");
// SpecRunner::run($s);
// expect( $s->get_result() )->toBe(true);

// # sets a negative result if expectation fails
// #............................................
// $s = new Spec('one should be two');
// $s->set_code("\expect(1)->toBe(2);");
// SpecRunner::run($s);
// expect( $s->get_result() )->toBe(false);

// # sets a negative result if exception is thrown and not catched inside the spec
// #..............................................................................
// $s = new Spec('throw exception');
// $s->set_code("throw new Exception();");
// SpecRunner::run($s);
// expect( $s->get_result() )->toBe(false);

// # sets a negative result if error is found inside the spec
// #.........................................................
// $s = new Spec('undeclared vars');
// $s->set_code("\$a = \$b + \$c;");
// SpecRunner::run($s);
// expect( $s->get_result() )->toBe(false);

// # if thw spec has sub_specs, runs them also
// #........................
// $s = new Spec('two things');
// $s_1 = new Spec('fail');
// $s_1->set_code("\$a = \$b + \$c;");
// $s_2 = new Spec('ok');
// $s_2->set_code("\$a = 2;");
// $s->add_sub_spec($s_1);
// $s->add_sub_spec($s_2);

// SpecRunner::run($s);
// $sub_specs = $s->get_sub_specs();
// expect($sub_specs[0]->get_result())->toBe(false);
// expect($sub_specs[1]->get_result())->toBe(true);

// # if one sub_spec fail the parent fails
// #........................................
// $s = new Spec('two things');
// $s_1 = new Spec('fail');
// $s_1->set_code("expect(2)->toBe(1);");
// $s_2 = new Spec('ok');
// $s_2->set_code("expect(1)->toBe(1);");
// $s->add_sub_spec($s_1);
// $s->add_sub_spec($s_2);
// SpecRunner::run($s);
// expect($s->get_result())->toBe(false);

// # if all sub_spec succed the parent succed
// #........................................
// $s = new Spec('two things');
// $s_1 = new Spec('ok 1 ');
// $s_1->set_code("expect(1)->toBe(1);");
// $s_2 = new Spec('ok 2 ');
// $s_2->set_code("expect(2)->toBe(2);");
// $s->add_sub_spec($s_1);
// $s->add_sub_spec($s_2);
// SpecRunner::run($s);
// expect($s->get_result())->toBe(true);

// ####################
// #  SpecFormatter
// ####################

// #  shows a gray string if has not run
// #.........................................
// $s = new Spec('ball bounces');
// $f = SpecFormatter::format($s);
// expect($f)->toBe(Color::dark_gray('ball bounces')."\n");

// #  shows a green string if has succeded
// #.........................................
// $s = new Spec('ball bounces');
// $s->set_result(true);
// $f = SpecFormatter::format($s);
// expect($f)->toBe(Color::green('ball bounces')."\n");

// # shows a red string and a message preceded by [FAIL] if has failed
// # the message is set with a second optional parameter to set_result
// #.........................................
// $s = new Spec('ball bounces');
// $s->set_result(false,"messagexpto");
// $f = SpecFormatter::format($s);
// expect($f)->toBe(Color::red("ball bounces")."\n".Color::red("[FAIL] messagexpto")."\n");

// # shows a green group description if the group has succeded
// #..........................................................

// $s = new Spec('two things');
// $s_1 = new Spec('ok 1');
// $s_1->set_code("expect(1)->toBe(1);");
// $s_2 = new Spec('ok 2');
// $s_2->set_code("expect(2)->toBe(2);");
// $s->add_sub_spec($s_1);
// $s->add_sub_spec($s_2);
// SpecRunner::run($s);
// expect($s->get_result())->toBe(true);

// $f = SpecFormatter::format($s);
// $lines = explode("\n",$f);
// expect($lines[0])->toBe(Color::green("two things"));
// expect($lines[1])->toBe('  '.Color::green("ok 1"));
// expect($lines[2])->toBe('  '.Color::green("ok 2"));

// # shows a red group description if the group has failed
// #.......................................................
// $s = new Spec('two things');
// $s_1 = new Spec('fail 1');
// $s_1->set_code("expect(2)->toBe(1);");
// $s_2 = new Spec('ok 2');
// $s_2->set_code("expect(2)->toBe(2);");
// $s->add_sub_spec($s_1);
// $s->add_sub_spec($s_2);
// SpecRunner::run($s);

// expect($s->get_result())->toBe(false);

// $f = SpecFormatter::format($s);
// $lines = explode("\n",$f);
// expect($lines[0])->toBe(Color::red("two things"));
// expect($lines[1])->toBe('  '.Color::red("fail 1"));
// expect($lines[3])->toBe('  '.Color::green("ok 2"));

// ####################
// #  SpecParser
// ####################

//   #######################
// 	#  With a single spec
// 	#######################

// 	# group read specs inside a spec named "main"
// 	#............................................
// 	$str = <<<EOD
// ##############################
// # Tomorrow should be a new day
// ##############################
// expect('tomorrow')->toBe('a new day');
// EOD;
// 	$spec = SpecParser::parse($str);
// 	expect($spec->get_name())->toBe('main');

// 	#... parse one spec as a child of main, named "One should be equals to one"
// 	expect( count($spec->get_sub_specs()) )->toBe(1);
// 	expect( $spec->get_sub_spec(0)->get_name() )->toBe("Tomorrow should be a new day");

// 	#... parse the spec's code
// 	expect( $spec->get_sub_spec(0)->get_code() )->toBe("expect('tomorrow')->toBe('a new day');");

// 	#######################
// 	#  With two specs
// 	#######################

	$str = <<<EOD
############################
# Tomorrow should be a new day
############################
expect('tomorrow')->toBe('a new day');

############################
# Tomorrow is friday
############################
expect('tomorrow')->toBe('friday');
EOD;

	$spec = SpecParser::parse($str);    
    #... extracts both specs
	expect( count($spec->get_sub_specs()) )->toBe(2);
	expect( $spec->get_sub_spec(0)->get_name() )->toBe("Tomorrow should be a new day");
	expect( $spec->get_sub_spec(1)->get_name() )->toBe("Tomorrow is friday");

	#... parse both spec's codes
	expect( $spec->get_sub_spec(0)->get_code() )->toBe("expect('tomorrow')->toBe('a new day');");
	expect( $spec->get_sub_spec(1)->get_code() )->toBe("expect('tomorrow')->toBe('friday');");


	#######################
	#  Complex Structure
	#######################

	$str = <<<EOD
############################
# Tomorrow should be a new day
############################
expect('tomorrow')->toBe('a new day');

  ############################
  # Tomorrow should be cold
  ############################
  expect('tomorrow')->toBe('cold');

###############
# 1 should be 1
###############
expect(1)->toBe(1);

  ##################
  # 1 should not be 2
  ##################
  expect(1)->notToBe(2);
EOD;

	$spec = SpecParser::parse($str);    
    #... extracts both specs
	expect( count($spec->get_sub_specs()) )->toBe(2);
	expect( count($spec->get_sub_spec(0)->get_sub_specs()) )->toBe(1);
	expect( count($spec->get_sub_spec(1)->get_sub_specs()) )->toBe(1);

	expect( $spec->get_sub_spec(1)->get_name() )->toBe("1 should be 1");
  expect( $spec->get_sub_spec(0)->get_sub_spec(0)->get_name() )->toBe("Tomorrow should be cold");
  expect( $spec->get_sub_spec(0)->get_sub_spec(0)->get_code() )->toBe("expect('tomorrow')->toBe('cold');");
  expect( $spec->get_sub_spec(1)->get_sub_spec(0)->get_name() )->toBe("1 should not be 2");
	expect( $spec->get_sub_spec(1)->get_sub_spec(0)->get_code() )->toBe("expect(1)->notToBe(2);");

# throws an exception with line number if a tab char is found before any text
#............................................................................
$str =<<<EOD
##############################
# Tomorrow should be a new day
##############################
expect('tomorrow')->toBe('a new day');

	##############################
	# Tomorrow should be a new day
	##############################
	expect('tomorrow')->toBe('a new day');
EOD;


$thrown = false;
try {
	$spec = SpecParser::parse($str);	
}catch(ParseException $e) {
	$thrown = true;
	expect($e->getMessage())->toBe('Please use only blank spaces. Tab chars found on line 6');
}
expect($thrown)->toBe(true);

# throws an exception with line number if a title is found with a wrong identation
# the only allowed identations are 0,2,4,6,8 ...
#..................................................................................

$str =<<<EOD
##############################
# Tomorrow should be a new day
##############################
expect('tomorrow')->toBe('a new day');

   ##############################
   # Tomorrow should be a new day
   ##############################
   expect('tomorrow')->toBe('a new day');
EOD;

$thrown = false;
try {
  $spec = SpecParser::parse($str);  
}catch(ParseException $e) {
  $thrown = true;
  expect($e->getMessage())->toBe('Wrong identation on line 7');
}
expect($thrown)->toBe(true);

# throws an exception with line number if a ==== before the title is found with a wrong identation
#...................................................................................
$str =<<<EOD
##############################
# Tomorrow should be a new day
##############################
expect('tomorrow')->toBe('a new day');

   ##############################
  # Tomorrow should be a new day
  ##############################
  expect('tomorrow')->toBe('a new day');
EOD;

$thrown = false;
try {
  $spec = SpecParser::parse($str);  
}catch(ParseException $e) {
  $thrown = true;
  expect($e->getMessage())->toBe('Wrong identation on line 6');
}
expect($thrown)->toBe(true);

# throws an exception with line number if a ==== after the title is found with a wrong identation
#..................................................................................................

$str =<<<EOD
##############################
# Tomorrow should be a new day
##############################
expect('tomorrow')->toBe('a new day');

  ##############################
  # Tomorrow should be a new day
    ##############################
  expect('tomorrow')->toBe('a new day');
EOD;

$thrown = false;
try {
  $spec = SpecParser::parse($str);  
}catch(ParseException $e) {
  $thrown = true;
  expect($e->getMessage())->toBe('Wrong identation on line 8');
}
expect($thrown)->toBe(true);

# parses a deep nested spec followed by a next one in the first level
#....................................................................
$str =<<<EOD
############
# grandpa
############
expect(1)->toBe(1);

  ############
  # father
  ############
  expect(2)->toBe(2);

    ############
    # child
    ############
    expect(3)->toBe(3);

      ############
      # grandchild
      ############
      expect(4)->toBe(4);

###################
# grandpa's brother
###################
expect(5)->toBe(5);
EOD;

$spec = SpecParser::parse($str);  
expect($spec->get_sub_spec(0)->get_name())->toBe('grandpa');
expect($spec->get_sub_spec(0)->get_code())->toBe('expect(1)->toBe(1);');
expect($spec->get_sub_spec(0)->get_sub_spec(0)->get_name())->toBe('father');
expect($spec->get_sub_spec(0)->get_sub_spec(0)->get_code())->toBe('expect(2)->toBe(2);');
expect($spec->get_sub_spec(0)->get_sub_spec(0)->get_sub_spec(0)->get_name())->toBe('child');
expect($spec->get_sub_spec(0)->get_sub_spec(0)->get_sub_spec(0)->get_code())->toBe('expect(3)->toBe(3);');
expect($spec->get_sub_spec(0)->get_sub_spec(0)->get_sub_spec(0)->get_sub_spec(0)->get_name())->toBe('grandchild');
expect($spec->get_sub_spec(0)->get_sub_spec(0)->get_sub_spec(0)->get_sub_spec(0)->get_code())->toBe('expect(4)->toBe(4);');
expect($spec->get_sub_spec(1)->get_name())->toBe("grandpa's brother");
expect($spec->get_sub_spec(1)->get_code())->toBe("expect(5)->toBe(5);");


# Spec allows array access
#..........................
