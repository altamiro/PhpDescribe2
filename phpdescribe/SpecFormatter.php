<?php 

class SpecFormatter {

    function format(Spec $spec){
      $text = $this->format_text($spec);
      $text .= "\n";

      $failed_specs = $spec->get_failed_leaves();
      if(count($failed_specs)) {
        $text .= Color::red("Failed Specs:")."\n\n";  
        foreach($failed_specs as $spec) {
          $text .= Color::red($spec->get_name()) . "\n";
          $text .= Color::red('---------------------------------------------------') . "\n";
          $text .= Color::red($spec->get_message()) . "\n\n";
        }
      }
      
      
      return $text;
    }

    protected function format_text(Spec $s,$level = 0) {
      if( count($s->get_sub_specs()) ) {
        $txt = $this->format_group($s, $level);
      }
      else {
        $txt = $this->format_spec($s, $level);
      }      

      foreach($s->get_sub_specs() as $sub) {
        $txt .= $this->format_text($sub,$level + 2);
      }

      return $txt;
    }

    private function format_group(Spec $s, $level) {
      $padding  = str_repeat(' ', $level);
      $result = $s->get_result();
      if(null === $result) {
        $t = $padding . Color::dark_gray($s->get_name())  . "\n";
      } elseif(true === $result) {
        $t = $padding . Color::green($s->get_name())  . "\n";  
      } else {
        $t = $padding . Color::red($s->get_name())  . "\n";  
      }
      return $t;
    }

    private function format_spec(Spec $s, $level) {
      
      $padding  = str_repeat(' ', $level);
      $result = $s->get_result();

      if(null === $result) {
        $t = $padding . Color::dark_gray($s->get_name())  . "\n";
      } elseif(true === $result) {
        $t = $padding . Color::green($s->get_name())  . "\n";  
      } else {
        $t = $padding . Color::red($s->get_name())  . "\n";  
        #$t .= $padding . Color::red( '[FAIL] ' . $s->get_message()) . "\n";
      }
      
      #$t .= Color::light_gray( substr($this->code, 0, 40) . '...' ) . "\n";
      return $t;
    }

}