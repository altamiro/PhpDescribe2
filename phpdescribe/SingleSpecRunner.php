<?php  
class SingleSpecRunner {

  static function run_eval() {
    $serialize_random = $_SERVER['argv'][1];
    $spec = self::deserialize_spec($serialize_random);
    try {
      #eval($spec->get_code());
      static::run_isolated_code($spec->get_code());
      $spec->set_result( true );
    }
    catch(FailedExpectationException $e) {
      $trace = $e->getTrace();
      $spec->set_error_line($trace[0]['line']);
      $spec->set_result( false, $e->getMessage() );
    }
    catch(Exception $e) {
      $spec->set_result( false, $e->getMessage() );
    }
    static::serialize_spec($spec, $serialize_random);
  }

  static function run_isolated_code($______code) {
    eval($______code);
  }

  static function serialize_spec($spec, $serialize_random) {
    $serial = var_export(serialize($spec),1);
    file_put_contents(
      "temp/serialized_spec_$serialize_random.php",   
      "<?php\n"
      ." \$___spec_ = unserialize(" . $serial . "); \n"
    );
  }


  static function deserialize_spec($serialize_random, $delete = false) {
    $file = "temp/serialized_spec_$serialize_random.php";
    require $file;
    if($delete) {
      unlink($file);
    }
    return $___spec_;
  }

  static function run($spec, $code) {
    if($code !== 'null' && $code !== '') {
      $code = $spec->get_code();
      if(self::check_syntax($code)) {
          $serialize_random = time() . '_' . rand(0,99999);
          static::serialize_spec($spec, $serialize_random);
          $retorno_shell = shell_exec('php run_eval.php '.$serialize_random);
          echo $retorno_shell;
          $unserialized_spec = static::deserialize_spec($serialize_random, true);
          $spec->copy_properties($unserialized_spec);
          if($spec->get_result() !== true && $spec->get_result() !== false) {
            if(file_exists(ERROR_FILE)) {
              require(ERROR_FILE);
              $spec->set_error_line($___error_['line']);
              $spec->set_result( false, $___error_['message'] );
            }
          }
      }
      else {
        $line = static::discover_error_line($code);
        $spec->set_error_line($line);
        $spec->set_result( false, 'Syntax error on line ' . $line . ' of the code.' );
      }
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