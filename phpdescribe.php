<?php
  require 'phpdescribe/Color.php';
  require 'phpdescribe/SpecParser.php';

  $filename = 'specs/especificacao_modelo_pequena.phpd';
  $spec = SpecParser::parse(file_get_contents($filename));
  
  SpecRunner::run($spec);
  echo $spec;

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

  class SpecRunner {
    static function run(Spec $spec) {
      if($spec->has_code()) {
        try {
          eval($spec->get_code());
          $spec->set_success( true );
        }
        catch(FailedExpectationException $e) {
          $spec->set_success( false, $e->getMessage() );
        }
        catch(Exception $e) {
          $spec->set_success( false, $e->getMessage() );
        }
      }
      
      foreach($spec->get_sub_specs() as $sub) {
        static::run($sub);
      } 
    }
  }
  

  class Spec {
    
    private $name;
    private $code;
    private $sub_specs = array();
    private $success;
    private $message;

    function __construct($name) {
      $this->name = $name;
    }

    function add_sub_spec($sub_spec) {
      $this->sub_specs[] = $sub_spec;
    }

    function set_success($success, $message=null) {
      $this->success = $success;
      $this->message = $message;
    }

    function set_code($code) {
      $this->code = $code;
    }

    function get_sub_specs() {
      return $this->sub_specs;
    }

    function has_code() {
      return $this->code !== null;
    }

    function get_code() {
      return $this->code;
    }    

    function __toString(){
      return $this->format_text();
    }

    protected function format_text($level = 0) {

      if(count($this->sub_specs)) {
        $txt = $this->format_group($level);
      }
      else {
        $txt = $this->format_spec($level);
      }      

      foreach($this->sub_specs as $sub) {
        $txt .= $sub->format_text($level + 2);
      }

      return $txt;
    }

    private function format_group($level) {
      $padding  = str_repeat(' ', $level);

      return $padding . Color::dark_gray($this->name) . "\n";
    }

    private function format_spec($level) {
      $padding  = str_repeat(' ', $level);
      if($this->success === null) {
        $t = $padding . Color::dark_gray($this->name)  . "\n";
      }
      else {
        if($this->success) {
          $t = $padding . Color::green($this->name)  . "\n";  
        }
        else {
          $t = $padding . Color::red($this->name)  . "\n";  
          $t .= $padding . Color::red( ' [FAIL] ' . $this->message) . "\n";
        }  
      }
      
      
      #$t .= Color::light_gray( substr($this->code, 0, 40) . '...' ) . "\n";
      return $t;
    }


  }