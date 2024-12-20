<?php
namespace App\Models;
use Core\Model;
use Core\H;

class MediaStackImages extends Model{
  public $id, $url, $stack_id, $name, $deleted=0;
  protected static $_table = 'media_stack_images';
  protected static $_softDelete = false;



  public static function findByAlbumId($album_id){
    return self::findFirst([
      'conditions' => "stack_id = ?",
      'bind' => ['stack_id'=>$album_id],
      'order' => 'sort'
    ]);
  }


  public static function uploadAlbumImages($album_id,$uploads){
    $lastImage = self::findFirst([
      'conditions' =>  "stack_id = ?",
      'bind' => [$album_id],
      'order' => 'sort DESC'
    ]);
    $lastSort = (!$lastImage)? 0 : $lastImage->sort;
    $path = 'uploads'.DS.'media_stack_images'.DS.'album_'.$album_id.DS;
    foreach($uploads->getFiles() as $file){
      $parts = explode('.',$file['name']);
      $ext = end($parts);
      $hash = sha1(time().$album_id.$file['tmp_name']);
      $uploadName = $hash . '.' . $ext;
      $image = new self();
      $image->url = $path . $uploadName;
      $image->name = $uploadName;
      $image->stack_id = $album_id;
      $image->sort = $lastSort;
      if($image->save()){
        $uploads->upload($path,$uploadName,$file['tmp_name']);
        $lastSort++;
      }
    }
    return $image->url;
  }

  public static function deleteImages($album_id,$unlink = false){
    $images = self::find([
      'conditions' => "stack_id = ?",
      'bind' => [$album_id]
    ]);
    foreach($images as $image){
      $image->delete();
    }
    if($unlink){
      $dirname = ROOT.DS.'uploads' . DS . 'media_stack_images' . DS . 'album_' . $album_id;
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
