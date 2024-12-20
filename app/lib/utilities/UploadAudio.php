<?php
  namespace App\Lib\Utilities;

  use Core\H;

  class UploadAudio {

    private $_errors = [], $_files=[], $_maxAllowedSize = 1073741824;

    public function __construct($files){
      $this->_files = self::restructureFiles($files);
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

    public function getFiles(){
      return $this->_files;
    }

    protected function validateAudioType(){
      foreach($this->_files as $file){
        switch($file["type"]){

          case 'audio/mpeg':
          case 'audio/wav':
          case 'audio/mp4':
          case 'audio/x-wav':
          case '':
            break;
          default:
            $name = $file['name'];
            $msg = $name . " is not an allowed file type. Please use a mp3, wav, or mp4.";
            $this->addErrorMessage($name,$msg);
          break;

        }
      }
    }

    protected function validateSize(){
      foreach($this->_files as $file){
        $name = $file['name'];
        if($file['size'] > $this->_maxAllowedSize){
          $msg = $name . " is over the max allowed size of 1gb.";
          $this->addErrorMessage($name,$msg);
        }
      }
    }

    protected function addErrorMessage($name,$message){
      if(array_key_exists($name,$this->_errors)){
        $this->_errors[$name] .= $this->_errors[$name] . " " . $message;
      } else {
        $this->_errors[$name] = $message;
      }
    }

    public static function restructureFiles($files){
      $structured = [];
      foreach($files['tmp_name'] as $key => $val){
        $structured[] = [
          'tmp_name'=>$files['tmp_name'][$key],'name'=>$files['name'][$key],
          'size'=>$files['size'][$key],'error'=>$files['error'][$key],'type'=>$files['type'][$key]
        ];
      }
      return $structured;
    }


  }
