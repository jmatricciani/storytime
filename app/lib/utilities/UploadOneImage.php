<?php
  namespace App\Lib\Utilities;

  use Core\H;

  class UploadOneImage {

    private $_errors = [], $_file, $_maxAllowedSize = 20971520;
    private $_allowedImageTypes = [IMAGETYPE_GIF,IMAGETYPE_JPEG,IMAGETYPE_PNG];

    public function __construct($file){
      $this->_file = self::restructureFile($file);
    }

    public function runValidation(){
      $this->validateSize();
      $this->validateImageType();
    }

    public function validates(){
      return (empty($this->_errors))? true : $this->_errors;
    }

    public function upload($bucket,$name,$tmp){
      if (!file_exists($bucket)) {
          mkdir($bucket);
        }
      $resp = move_uploaded_file($tmp,ROOT.DS.$bucket.$name);
    }

    public function getFile(){
      return $this->_file;
    }

    protected function validateImageType(){
      if(!in_array(exif_imagetype($this->_file['tmp_name']),$this->_allowedImageTypes)){
        $name = $this->_file['name'];
        $msg = $name . " is not an allowed file type. Please use a jpeg, gif, or png.";
        $this->addErrorMessage($name,$msg);
      }
    }

    protected function validateSize(){
      $name = $this->_file['name'];
      if($this->_file['size'] > $this->_maxAllowedSize){
        $msg = $name . " is over the max allowed size of 20mb.";
        $this->addErrorMessage($name,$msg);
      }

    }

    protected function addErrorMessage($name,$message){
      if(array_key_exists($name,$this->_errors)){
        $this->_errors[$name] .= $this->_errors[$name] . " " . $message;
      } else {
        $this->_errors[$name] = $message;
      }
    }

    public static function restructureFile($file){
      $structured = [];
        if($file['tmp_name'] != ""){
          $structured[] = [
            'tmp_name'=>$file['tmp_name'],'name'=>$file['name'],
            'size'=>$file['size'],'error'=>$file['error'],'type'=>$file['type']
          ];
        }
      return $structured[0];
    }


  }
