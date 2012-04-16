<?php

  ### Errors as Exceptions....
  
  set_error_handler("exception_error_handler");

  function exception_error_handler($errno, $errstr, $errfile, $errline ) {
      throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
  }