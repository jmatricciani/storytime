<?php
  namespace App\Lib\Utilities;

  use Core\H;

  class UploadOneAudio {

    private $_errors = [], $_file, $_maxAllowedSize = 1073741824;

    public function __construct($file){
      $this->_file = self::restructureFile($file);
    }

    public function runValidation(){
      $this->validateSize();
      $this->validateAudioType();
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

    protected function validateAudioType(){
      switch($this->_file["type"]){
        case 'audio/mpeg':
        case 'audio/wav':
        case 'audio/mp4':
        case 'audio/x-wav':
        case '':
          break;
        default:
          $name = $this->_file['name'];
          $msg = $name . " is not an allowed file type. Please use a mp3, wav, or mp4.";
          $this->addErrorMessage($name,$msg);
        break;

      }
    }

    protected function validateSize(){
      $name = $this->_file['name'];
      if($this->_file['size'] > $this->_maxAllowedSize){
        $msg = $name . " is over the max allowed size of 1gb.";
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
