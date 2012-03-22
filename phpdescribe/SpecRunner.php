<?php 
  class SpecRunner {

    static function run(Spec $spec) {
      $code = $spec->get_code();
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
      foreach($spec->get_sub_specs() as $sub) {
        static::run($sub);
      } 
    }
  }