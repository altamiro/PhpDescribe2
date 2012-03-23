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

    function get_message() {
      return $this->message;
    }

    function set_code($code) {
      $this->code = $code;
    }

    function get_sub_specs() {
      return $this->sub_specs;
    }

    function get_sub_spec($index) {
      return $this->sub_specs[$index];
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

    
}