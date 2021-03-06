<?php
#############
# phpdescribe
#############

  ###############################################
  # run a simple expectation
  ###############################################
  expect(2)->toBe(2);

  ###############################################
  # should run a test inside a spec
  ###############################################
  // if you use $spec will conflict becouse tests are not isolated.
  $s = new Spec('test');
  $s->set_code('expect(1)->toBe(1);');
  SpecRunner::run($s);
  expect($s->get_result())->toBe(true);

  ####################################################
  #  Should keep the beggining code line of each spec
  ####################################################

  #############
  #  Error Line
  #############

    #########################################################
    # should show the line of the parser error on the message
    #########################################################

      ############################
      # no blank lines in the code
      ############################
      $s = new Spec('test');
      $s->set_code('
        expect(1)->toBe(1)
        expect(1)->toBe(1);
      ');
      SpecRunner::run($s);
      expect($s->get_message())->toBe('Syntax error on line 3 of the code.');

      ############################
      # error
      ############################
      expect(1)->toBe(2);

      ############################
      # code with blank lines
      ############################
      $s = new Spec('test');
      $s->set_code('

        expect(1)->toBe(1)
        expect(1)->toBe(1);
      ');
      SpecRunner::run($s);
      expect($s->get_message())->toBe('Syntax error on line 4 of the code.');

    ##########################################
    # should keep the line of the parser error
    ##########################################
    $s = new Spec('test');
    $s->set_code('
      expect(1)->toBe(1)
      expect(1)->toBe(1);
    ');
    SpecRunner::run($s);
    expect($s->get_error_line())->toBe(3);

    ###############################################
    # should keep the line of the failing test
    ###############################################
    $s = new Spec('test');
    $s->set_code('expect(1)->toBe(2);');
    SpecRunner::run($s);
    expect($s->get_error_line())->toBe(1);
    $s->set_code('expect(1)->toBe(1);
    expect(1)->toBe(1);
    expect(1)->toBe(2);
    ');
    SpecRunner::run($s);
    expect($s->get_error_line())->toBe(3);


    

  ###############################################
  # one test should run in isolation
  ###############################################

    ######################################################
    # variables in one test cannot be seen on another one
    ######################################################

      ###################################
      # declare something
      ###################################
      $a = 10;

      #######################################################
      # verify the var declared on the other test is not set
      #######################################################
      expect(isset($a))->toBe(false);
      
    #######################################################
    # no vars are set on each spec
    #######################################################
    $v = get_defined_vars();
    expect(count($v))->toBe(1);
    $vars = array_keys($v);
    expect($vars[0])->toBe('______code');

    #############################################################################
    # should recover from a fatal error running each test in a isolated proccess
    #############################################################################


    ####################################
    # should recover from a fatal error
    ####################################
    //$o = new StdClass();
    //$o->inexistent_method_throws_error();





  
