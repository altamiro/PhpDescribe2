<?php
class Spec {
    
    private $name;
    private $code;
    private $sub_specs = array();
    private $result = null;
    private $message;
    private $error_line;
    #private $parent_spec;

    function __construct($name) {
      $this->name = $name;
    }

    function get_failed_leaves() {
      $failed_leaves = array();

      if($this->is_leaf()) {
        if($this->result === false) {
          $failed_leaves[] = $this;
        }
      }
      else {
        foreach($this->sub_specs as $s) {
          $failed_leaves = array_merge($s->get_failed_leaves(), $failed_leaves);
        }
      }
      return $failed_leaves;
    }

    function is_leaf() {
      return (count($this->sub_specs) == 0);
    }

    function add_sub_spec($sub_spec) {
      #$sub_spec->set_parent_spec($this);
      $this->sub_specs[] = $sub_spec;
    }

    // function set_parent_spec(Spec $spec) {
    //   $this->parent_spec = $spec;
    // }

    function is_root() {
      return is_null($this->parent_spec);
    }

    function set_error_line($error_line) {
      $this->error_line = $error_line;
    }

    function get_error_line() {
      return $this->error_line;
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
    
    function copy_properties($spec) {
      $this->name       = $spec->get_name();
      $this->code       = $spec->get_code();
      #$this->sub_specs  = $spec->get_sub_specs();
      $this->result     = $spec->get_result();
      $this->message    = $spec->get_message();
      $this->error_line = $spec->get_error_line();
    }    

    function get_name() {
      return $this->name;
    }

    
}