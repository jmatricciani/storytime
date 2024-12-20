<?php
namespace App\Models;
use Core\Model;
use Core\H;

class MediaImages extends Model{
  public $id, $media_id, $name, $url, $deleted=0;
  protected static $_table = 'media_images';
  protected static $_softDelete = false;

  public static function uploadImage($media_id,$uploads,$index = 0){
    $upload = $uploads->getFiles()[$index];
    $path = 'uploads'.DS.'media_images'.DS.'media_'.$media_id.DS;
    $parts = explode('.',$upload['name']);
    $ext = end($parts);
    $hash = sha1(time().$media_id.$upload['tmp_name']);
    $uploadName = $hash . '.' . $ext;
    $image = new self();
    $image->url = $path . $uploadName;
    $image->name = $uploadName;
    $image->media_id = $media_id;
    if($image->save()){
      $uploads->upload($path,$uploadName,$upload['tmp_name']);
    }
  }

  public static function uploadMediaImage($media_id,$uploads){
    $lastImage = self::findFirst([
      'conditions' =>  "media_id = ?",
      'bind' => [$media_id]
    ]);

    $path = 'uploads'.DS.'media_images'.DS.'media_'.$media_id.DS;
    $file = $uploads->getFile();
    $parts = explode('.',$file['name']);
    $ext = end($parts);
    $hash = sha1(time().$media_id.$file['tmp_name']);
    $uploadName = $hash . '.' . $ext;
    $image = new self();
    $image->url = $path . $uploadName;
    $image->name = $uploadName;
    $image->media_id = $media_id;
    if($image->save()){
      $uploads->upload($path,$uploadName,$file['tmp_name']);
    }
  }

  public static function deleteImages($media_id,$unlink = false){
    $images = self::find([
      'conditions' => "media_id = ?",
      'bind' => [$media_id]
    ]);
    foreach($images as $image){
      $image->delete();
    }
    if($unlink){
      $dirname = ROOT.DS.'uploads' . DS . 'media_images' . DS . 'media_' . $media_id;
      array_map('unlink', glob("$dirname/*.*"));
      rmdir($dirname);
    }
  }

  // public static function deleteById($id){
  //   $image = self::findById($id);
  //   $sort = $image->sort;
  //   $afterImages = self::find([
  //     'conditions' => "media_id = ? and sort > ?",
  //     'bind' => [$image->media_id, $sort]
  //   ]);
  //   foreach($afterImages as $af){
  //     $af->sort = $af->sort -1;
  //     $af->save();
  //   }
  //   unlink(ROOT.DS.'uploads'.DS. 'media_images'.DS.'media_'.$image->media_id.DS. $image->name);
  //   return $image->delete();
  // }

  public static function findByMediaId($media_id){
    return self::findFirst([
      'conditions' => "media_id = ?",
      'bind' => ['media_id'=>$media_id]
    ]);
  }

  // public static function updateSortByMediaId($media_id,$sortOrder=[]){
  //   $images = self::findByMediaId($media_id);
  //   $i = 0;
  //   foreach($images as $image){
  //     $val = 'image_'.$image->id;
  //     $sort = (in_array($val,$sortOrder))? array_search($val,$sortOrder) : $i;
  //     $image->sort = $sort;
  //     $image->save();
  //     $i++;
  //   }
  // }

}
