<?php 

class SpecFormatter {

    static function format(Spec $s){
      return self::format_text($s);
    }

    static protected function format_text(Spec $s,$level = 0) {

      if( count($s->get_sub_specs()) ) {
        $txt = self::format_group($s, $level);
      }
      else {
        $txt = self::format_spec($s, $level);
      }      

      foreach($s->get_sub_specs() as $sub) {
        $txt .= self::format_text($sub,$level + 2);
      }

      return $txt;
    }

    static private function format_group(Spec $s, $level) {
      $padding  = str_repeat(' ', $level);
      return $padding . Color::dark_gray($s->get_name()) . "\n";
    }

    static private function format_spec(Spec $s, $level) {
      $padding  = str_repeat(' ', $level);
      if($s->get_result() === null) {
        $t = $padding . Color::dark_gray($s->get_name())  . "\n";
      }
      else {
        if($s->get_result()) {
          $t = $padding . Color::green($s->get_name())  . "\n";  
        }
        else {
          $t = $padding . Color::red($s->get_name())  . "\n";  
          $t .= $padding . Color::red( '[FAIL] ' . $s->get_message()) . "\n";
        }  
      }
      
      
      #$t .= Color::light_gray( substr($this->code, 0, 40) . '...' ) . "\n";
      return $t;
    }

}