<?php
namespace App\Models;
use Core\Model;
use Core\H;

class MediaAudio extends Model{
  public $id, $media_id, $name, $url, $deleted=0;
  protected static $_table = 'media_audio';
  protected static $_softDelete = false;

  public static function findByMediaId($media_id){
    return self::findFirst([
      'conditions' => "media_id = ?",
      'bind' => [$media_id]
    ]);
  }

  public static function uploadAudio($media_id,$uploads,$index = 0){
    $upload = $uploads->getFiles()[$index];
    $path = 'uploads'.DS.'media_audio'.DS.'media_'.$media_id.DS;
    $parts = explode('.',$upload['name']);
    $ext = end($parts);
    $hash = sha1(time().$media_id.$upload['tmp_name']);
    $uploadName = $hash . '.' . $ext;
    $audio = new self();
    $audio->url = $path . $uploadName;
    $audio->name = $uploadName;
    $audio->media_id = $media_id;
    if($audio->save()){
      $uploads->upload($path,$uploadName,$upload['tmp_name']);
    }
  }

  public static function uploadMediaAudio($media_id,$uploads){
    $path = 'uploads'.DS.'media_audio'.DS.'media_'.$media_id.DS;
    $file = $uploads->getFile();
    $parts = explode('.',$file['name']);
    $ext = end($parts);
    $hash = sha1(time().$media_id.$file['tmp_name']);
    $uploadName = $hash . '.' . $ext;
    $audio = new self();
    $audio->url = $path . $uploadName;
    $audio->name = $uploadName;
    $audio->media_id = $media_id;
    if($audio->save()){
      $uploads->upload($path,$uploadName,$file['tmp_name']);
    }
  }

  public static function deleteAudio($media_id,$unlink = false){
    $track = self::findbyMediaId($media_id);
    $track->delete();
    if($unlink){
      $dirname = ROOT.DS.'uploads' . DS . 'media_audio' . DS . 'media_' . $media_id;
      array_map('unlink', glob("$dirname/*.*"));
      rmdir($dirname);
    }
  }


}
