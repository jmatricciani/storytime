<?php

  namespace App\Lib\Elements;

  use Core\{FH,H};

  class BackEnd{

    public static function displayApparelOptions($option,$stocks,$view){

      $value = '';
      $label = explode('_',$option);
      $label = strtoupper($label[1]);

      foreach($stocks as $stock){
        if($stock[0] == $option)
          $value = $stock[1];
      }
      $html = '
        <div class="col-2">'
          .FH::changableInputBlock('text',$label,$option,'update_total()',$value,['class'=>'form-control input-sm'],['class'=>'form-group col-md-6'],$view->displayErrors).
        '</div>

      ';
      return $html;
    }

}
