<?php
  namespace App\Models;
  use Core\{Model, DB, H};
  use Core\Validators\{RequiredValidator,NumericValidator,MaxValidator};
  use App\Models\{MediaImages};

  class Media extends Model {

    public $id, $created_at, $updated_at, $user_id, $stack_id, $name, $type, $label=0, $body, $sort=0;
    public $deleted=0;
    const blackList = ['id','deleted'];
    protected static $_table = 'media';
    protected static $_softDelete = false;

    public function beforeSave(){
      $this->timeStamps();
    }

    public function validator(){
      $requiredFields = ['name'=>"Name"];
      foreach($requiredFields as $field => $display){
        $this->runValidation(new RequiredValidator($this,['field'=>$field,'msg'=>$display." is required."]));
      }
      $this->runValidation(new MaxValidator($this,['field'=>'name','rule'=>150,'msg'=>'Name must be less than 150 characters.']));
    }

    public static function getX($limit=0){
      $db = DB::getInstance();
      $where = "media.deleted = 0 AND media.stack_id IS NULL";


      if($limit == 0)
        return self::find();
      else{
        $sql = "
          SELECT media.*, mi.url as url FROM media
                JOIN media_images as mi
                ON media.id = mi.media_id

                WHERE {$where}
                LIMIT {$limit}

        ";

        return $db->query($sql)->results();
      }
    }

    public static function getXWithArtistName($limit=0){
      $db = DB::getInstance();
      $where = "media.deleted = 0 AND media.stack_id IS NULL";


      if($limit == 0)
        return self::find();
      else{
        $sql = "
          SELECT media.*, mi.url as url,users.artist_name as aName FROM media
                JOIN media_images as mi
                ON media.id = mi.media_id
                JOIN users
                ON media.user_id = users.id

                WHERE {$where}
                LIMIT {$limit}

        ";

        return $db->query($sql)->results();
      }
    }

    public static function getXByType($type,$limit){
      $db = DB::getInstance();
      $where = "media.deleted = 0 AND media.type = ?";

      $sql = "
        SELECT media.*, mi.url as url FROM media
              JOIN media_images as mi
              ON media.id = mi.media_id

              WHERE {$where}
              LIMIT {$limit}

      ";

      return $db->query($sql,[(string)$type])->results();

    }

    public static function getAllByType($type,$params=[]){
      $conditions = [
        'conditions' => "type = ?",
        'bind' => [(string)$type],
        'order_desc' => 'updated_at'
      ];
      $params = array_merge($conditions, $params);
      return self::find($params);
    }

    public static function findByUserId($user_id,$params=[]){
      $conditions = [
        'conditions' => "user_id = ?",
        'bind' => [(int)$user_id],
        'order' => 'name'
      ];
      $params = array_merge($conditions, $params);
      return self::find($params);
    }

    public static function findByAlbumId($album_id,$params=[]){
      $conditions = [
        'conditions' => "stack_id = ?",
        'bind' => [(int)$album_id]
      ];
      $params = array_merge($conditions, $params);
      return self::find($params);
    }

    public static function findById($id){
      $conditions = [
        'conditions' => "id = ?",
        'bind' => [(int)$id]
      ];
      return self::findFirst($conditions);
    }

    public static function findByIdAndUserId($id, $user_id){
      $conditions = [
        'conditions' => "id = ? AND user_id = ?",
        'bind' => [(int)$id, (int)$user_id]
      ];
      return self::findFirst($conditions);
    }

    public static function findAllByStackIdWithData($id){

      $sql = "SELECT media.*, mi.url as imageUrl, ma.url as audioUrl FROM media
              JOIN media_images as mi
              ON media.id = mi.media_id
              JOIN media_audio as ma
              ON media.id = ma.media_id
              WHERE media.stack_id = ?";

      return DB::getInstance()->query($sql,[(int)$id])->results();
    }

    public static function findByMediaIdWithData($id){

      $sql = "SELECT media.*, mi.url as imageUrl, ma.url as audioUrl FROM media
              JOIN media_images as mi
              ON media.id = mi.media_id
              JOIN media_audio as ma
              ON media.id = ma.media_id
              WHERE media.id = ?";


      return DB::getInstance()->query($sql,[(int)$id])->results()[0];
    }

    public static function findByUserIdWithData($id){

      $sql = "SELECT media.*, mi.url as imageUrl, ma.url as audioUrl FROM media
              JOIN media_images as mi
              ON media.id = mi.media_id
              JOIN media_audio as ma
              ON media.id = ma.media_id
              WHERE media.user_id = ?";

      return DB::getInstance()->query($sql,[(int)$id])->results();
    }

    public static function findByUserIdTypeWithData($id,$type){

      $sql = "SELECT media.*, mi.url as imageUrl, ma.url as audioUrl FROM media
              JOIN media_images as mi
              ON media.id = mi.media_id
              JOIN media_audio as ma
              ON media.id = ma.media_id
              WHERE media.user_id = ? AND media.type = ?";

      return DB::getInstance()->query($sql,[(int)$id,(string)$type])->results();
    }

    public static function findByNameTypeWithData($name,$type){

      $sql = "SELECT media.*, mi.url as imageUrl, ma.url as audioUrl FROM media
              JOIN media_images as mi
              ON media.id = mi.media_id
              JOIN media_audio as ma
              ON media.id = ma.media_id
              WHERE replace(replace(replace(replace(replace(media.name,',', ''),'/',''),'!',''),'?',''),'.',' ') LIKE ? AND media.type = ?";

      return DB::getInstance()->query($sql,[(string)$name,(string)$type])->results()[0];
    }
  }
