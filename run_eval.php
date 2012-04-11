<?php
require('phpdescribe/Expectation.php'); 
require('phpdescribe/SpecRunner.php'); 
require('phpdescribe/Spec.php'); 
require('temp/serialized_spec.php'); 

register_shutdown_function(function() {
	$error = error_get_last();
    if(($error['type'] === E_ERROR) || ($error['type'] === E_USER_ERROR))
    {
    	file_put_contents(
        	ERROR_FILE, 
        	"<?php\n"
        	." \$___error_ = unserialize(stripslashes(\"" . addslashes(serialize($error)) . "\")); \n"
      	);
    }

});
if(file_exists(ERROR_FILE)) unlink(ERROR_FILE);
SingleSpecRunner::run_eval($___spec_); 
