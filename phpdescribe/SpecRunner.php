<?php 
  class SpecRunner {

    static function run(Spec $spec) {
      $code = $spec->get_code();
      $sub_specs = $spec->get_sub_specs();
      if( count($sub_specs) == 0 ) {
        if($code !== 'null' && $code !== '') {
          $code = $spec->get_code();
          if(self::check_syntax($code)) {
            try {
              eval($code);
              $spec->set_result( true );
            }
            catch(FailedExpectationException $e) {
              $spec->set_result( false, $e->getMessage() );
            }
            catch(Exception $e) {
              $spec->set_result( false, $e->getMessage() );
            }
          }
          else {
            $line = static::discover_error_line($code);
            $spec->set_result( false, 'Systax error on line ' . $line . ' of the code.' );
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

    static function discover_error_line($code) {
      $file = 'temp/code_with_error.php';
      file_put_contents($file, '<?php'.$code);  
      ob_start();
      passthru('php -l '.$file);
      ob_end_clean();
      $error = error_get_last();
      return $error['line'];
    }

    static function check_syntax($code) {
      return @eval('return true;' . $code);
    }
  }