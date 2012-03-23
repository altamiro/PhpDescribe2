<?php

  function expect($subject) {
    return new Expectation($subject);
  }

  class Expectation {
    private $subject;

    function __construct($subject) {
      $this->subject = $subject;
    }

    function toBe($expected_value) {
      if($this->subject !== $expected_value) {
        throw new FailedExpectationException(
          'Expecting ' . var_export($this->subject,1) . ' toBe ' . var_export($expected_value,1) 
        );
      }
    }

  }

  class FailedExpectationException extends Exception {}


  ### Errors as Exceptions....
  
  set_error_handler("exception_error_handler");

  function exception_error_handler($errno, $errstr, $errfile, $errline ) {
      throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
  }