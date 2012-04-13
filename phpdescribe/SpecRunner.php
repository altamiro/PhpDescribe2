<?php 
  const ERROR_FILE = 'temp/error';
  class SpecRunner {

    static function run(Spec $spec) {
      #echo "### running " . $spec->get_name() . "\n";
      $code = $spec->get_code();
      $sub_specs = $spec->get_sub_specs();
      if( count($sub_specs) == 0 ) {
          SingleSpecRunner::run($spec, $code);
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