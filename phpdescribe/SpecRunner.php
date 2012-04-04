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

            $result = static::discover_error_line($code);
            $spec->set_result( false, $result );
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
      $result = passthru('php -l '.$file);
      $result = ob_get_contents();
      ob_end_clean();
      #echo "++++++++++";
      echo "result:" . $result;
      echo "\n";
      // echo "------------";
      return $result;
    }

    static function check_syntax($code) {
      return @eval('return true;' . $code);
    }
  }