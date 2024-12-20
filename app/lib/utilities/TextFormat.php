<?php
  namespace App\Lib\Utilities;

  use Core\H;

  class TextFormat{

    public static function convertNameToUrl($name){
      $name_link = strtolower($name);
      $name_link =  str_replace([' ','.'], '-', $name_link);
      $name_link =  str_replace([',','?','!','/'], '', $name_link);
      return $name_link;
    }
  }
