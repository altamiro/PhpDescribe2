<?php 
  class SpecRunner {

    static function run(Spec $spec) {
      $code = $spec->get_code();
      $sub_specs = $spec->get_sub_specs();
      if( count($sub_specs) == 0 ) {
        if($code !== 'null' && $code !== '') {
          try {
            eval($spec->get_code());
            $spec->set_result( true );
          }
          catch(FailedExpectationException $e) {
            $spec->set_result( false, $e->getMessage() );
          }
          catch(Exception $e) {
            $spec->set_result( false, $e->getMessage() );
          }
        }  
      }
      else {
        $result = true;

        foreach($spec->get_sub_specs() as $sub) {
          static::run($sub);
          if($sub->get_result() === false) {
            $result = false;
          }
        }

        $spec->set_result($result);
      }
      
    }
  }