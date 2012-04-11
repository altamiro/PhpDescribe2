<?php 
  const ERROR_FILE = 'temp/error';

  class SingleSpecRunner {

    static function run_eval( $spec) {
      try {
        eval($spec->get_code());
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
      static::serialize_spec($spec);
    }

    static function serialize_spec($spec) {
      file_put_contents(
        'temp/serialized_spec.php', 
        "<?php\n"
        ." \$___spec_ = unserialize('" . serialize($spec) . "'); \n"
      );
    }


    static function deserialize_spec() {
      require 'temp/serialized_spec.php';
      return $___spec_;
    }

    static function run($spec, $code) {
      if($code !== 'null' && $code !== '') {
        $code = $spec->get_code();
        if(self::check_syntax($code)) {
            static::serialize_spec($spec);
            $retorno_shell = shell_exec('php run_eval.php');
            #print_r(preg_match(pattern, subject)$retorno_shell,'/PHP Fatal error:(.*) in .*:.*eval.*line (.*)/')
            $unserialized_spec = static::deserialize_spec();
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


  class SpecRunner {

    static function run(Spec $spec) {
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