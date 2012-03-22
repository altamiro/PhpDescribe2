<?php
class Spec {
    
    private $name;
    private $code;
    private $sub_specs = array();
    private $result = null;
    private $message;

    function __construct($name) {
      $this->name = $name;
    }

    function add_sub_spec($sub_spec) {
      $this->sub_specs[] = $sub_spec;
    }

    function set_result($result, $message=null) {
      $this->result = $result;
      $this->message = $message;
    }

    function set_code($code) {
      $this->code = $code;
    }

    function get_sub_specs() {
      return $this->sub_specs;
    }

    function get_result() {
      return $this->result;
    }

    function get_code() {
      return $this->code;
    }    

    function get_name() {
      return $this->name;
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
      return 'g' . $padding . Color::dark_gray($this->name) . "\n";
    }

    private function format_spec($level) {
      $padding  = str_repeat(' ', $level);
      if($this->result === null) {
        $t = $padding . Color::dark_gray($this->name)  . "\n";
      }
      else {
        if($this->result) {
          $t = $padding . Color::green($this->name)  . "\n";  
        }
        else {
          $t = $padding . Color::red($this->name)  . "\n";  
          $t .= $padding . Color::red( '[FAIL] ' . $this->message) . "\n";
        }  
      }
      
      
      #$t .= Color::light_gray( substr($this->code, 0, 40) . '...' ) . "\n";
      return $t;
    }
}