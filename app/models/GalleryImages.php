<?php
namespace App\Models;
use Core\{DB,H,Model};

class GalleryImages extends Model{
  public $id, $user_id, $name, $body, $url, $deleted=0;
  protected static $_table = 'gallery_images';
  protected static $_softDelete = false;

  public function validator(){

  }

  public function beforeSave(){
    $this->timeStamps();
  }

  public static function uploadGalleryImages($image,$uploads){
    $path = 'uploads'.DS.'gallery_images'.DS.'image_'.$image->id.DS;
    foreach($uploads->getFiles() as $file){
      $parts = explode('.',$file['name']);
      $ext = end($parts);
      $hash = sha1(time().$image->id.$file['tmp_name']);
      $uploadName = $hash . '.' . $ext;
      $image->url = $path . $uploadName;
      if($image->save()){
        $uploads->upload($path,$uploadName,$file['tmp_name']);
      }
    }

    return $image;
  }

  public static function getX($limit=0){
    $images = self::find([
      'order_desc' => 'updated_at',
      'limit' => $limit
    ]);
    return $images;
  }

  public static function getXWIthArtistName($limit=0){
    $db = DB::getInstance();
    $where = "gallery_images.deleted = 0";


    if($limit == 0)
      return self::find();
    else{
      $sql = "
        SELECT gallery_images.*, users.artist_name as aName FROM gallery_images
              JOIN users
              ON gallery_images.user_id = users.id

              WHERE {$where}
              LIMIT {$limit}

      ";

      return $db->query($sql)->results();
    }
  }

  public static function findByUserId($user_id){
    return self::find([
      'conditions' => "user_id = ?",
      'bind' => [(int)$user_id],
      'order_desc' => 'updated_at'
    ]);
  }

  public static function findByIdAndUserId($id, $user_id){
    $conditions = [
      'conditions' => "id = ? AND user_id = ?",
      'bind' => [(int)$id, (int)$user_id]
    ];
    return self::findFirst($conditions);
  }

  public static function deleteImage($image_id,$unlink = false){
    $image = self::findFirst([
      'conditions' => "id = ?",
      'bind' => [(int)$image_id]
    ]);
    $image->delete();

    if($unlink){
      $dirname = ROOT.DS.'uploads' . DS . 'gallery_images' . DS . 'image_' . $image_id;
      array_map('unlink', glob("$dirname/*.*"));
      rmdir($dirname);
    }
  }

  public static function getDashboard($id){
    $images = self::find([
      'conditions' => "user_id = ?",
      'bind' => [(int)$id],
      'order_desc' => 'updated_at',
      'limit' => '5'
    ]);
    return $images;
  }

}
