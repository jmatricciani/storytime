<?php
  namespace App\Models;
  use Core\{Model, DB, H};
  use Core\Validators\{RequiredValidator,NumericValidator,MaxValidator};
  use App\Models\{MediaImages,Media};

  class MediaStack extends Model {

    public $id, $created_at, $updated_at, $user_id, $name;
    public $type, $catalog, $price, $shipping, $body, $inventory_key, $deleted=0;
    const blackList = ['id','deleted'];
    protected static $_table = 'media_stack';
    protected static $_softDelete = false;

    public function beforeSave(){
      $this->timeStamps();
    }

    public function validator(){
      $requiredFields = ['name'=>"Name",'body'=>"Body",'price'=> "Price"];
      foreach($requiredFields as $field => $display){
        $this->runValidation(new RequiredValidator($this,['field'=>$field,'msg'=>$display." is required."]));
      }
      $this->runValidation(new MaxValidator($this,['field'=>'name','rule'=>150,'msg'=>'Name must be less than 150 characters.']));
      $this->runValidation(new MaxValidator($this,['field'=>'body','rule'=>65000,'msg'=>'Body must be less than 65k characters.']));
    }

    public static function getX($limit=0){
      $db = DB::getInstance();
      $where = "media_stack.deleted = 0 AND msi.sort = '0'";


      if($limit == 0)
        return self::find();
      else{
        $sql = "
          SELECT media_stack.*, msi.url as url FROM media_stack
                JOIN media_stack_images as msi
                ON media_stack.id = msi.stack_id

                WHERE {$where}
                LIMIT {$limit}

        ";

        return $db->query($sql)->results();
      }
    }

    public static function getXWithArtistName($limit=0){
      $db = DB::getInstance();
      $where = "media_stack.deleted = 0 AND msi.sort = '0'";


      if($limit == 0)
        return self::find();
      else{
        $sql = "
          SELECT media_stack.*, msi.url as url, users.artist_name as aName FROM media_stack
                JOIN media_stack_images as msi
                ON media_stack.id = msi.stack_id
                JOIN users
                ON media_stack.user_id = users.id

                WHERE {$where}
                LIMIT {$limit}

        ";

        return $db->query($sql)->results();
      }
    }

    public static function findByNameTypeWithData($name,$type){

      //H::dnd($name);
      $sql = "SELECT media_stack.*, msi.url as imageUrl FROM media_stack
              JOIN media_stack_images as msi
              ON media_stack.id = msi.stack_id
              WHERE replace(replace(replace(replace(replace(media_stack.name,',', ''),'/',''),'!',''),'?',''),'.',' ') LIKE ? AND media_stack.type = ?";

      return DB::getInstance()->query($sql,[(string)$name,(string)$type])->results()[0];
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

    public static function findById($id){
      $conditions = [
        'conditions' => "id = ?",
        'bind' => [(int)$id]
      ];
      return self::findFirst($conditions);
    }

    public static function findByUserIdType($user_id,$type){
      $conditions = [
        'conditions' => "user_id = ? AND type = ?",
        'bind' => [(int)$user_id,(string)$type],
        'sort' => "updated_at"
      ];
      return self::find($conditions);
    }

    public static function findByIdAndUserId($id, $user_id){
      $conditions = [
        'conditions' => "id = ? AND user_id = ?",
        'bind' => [(int)$id, (int)$user_id]
      ];
      return self::findFirst($conditions);
    }

    public static function getDashboard($id){
      $sql = "SELECT album.*, ai.url as url FROM album
              JOIN album_images as ai
              ON album.id = ai.album_id
              WHERE album.user_id = ?
              ORDER BY album.updated_at DESC
              LIMIT 5";

      return DB::getInstance()->query($sql,[(int)$id])->results();
    }

    public static function getDetails($id){
      $sql = "SELECT album.*, ai.url as url FROM album
              JOIN album_images as ai
              ON album.id = ai.album_id
              WHERE album.id = ?";

      return DB::getInstance()->query($sql,[(int)$id])->results()[0];
    }

    public static function getIndex(){
      $sql = "SELECT album.*, ai.url as url FROM album
              JOIN album_images as ai
              ON album.id = ai.album_id";

      return DB::getInstance()->query($sql,[])->results();
    }

    public static function download($id){
      //Access files and album info
      $album = self::getDetails($id);
      $tracks = Media::findAllByAlbumIdWithData($id);

      // Delete All files in download folder
      $files = glob('downloads/*'); // get all file names
      foreach($files as $file){ // iterate files
        if(is_file($file)) {
          unlink($file); // delete file
        }
      }

      // Form zip file download
      if(extension_loaded('zip')){
        $zip = new \ZipArchive;
        $name = explode(' ', $album->name, 2);
        $zip_name = $name[0].".zip";
        $path = "downloads/".$zip_name;
        if($zip->open($path,\ZipArchive::CREATE|\ZipArchive::OVERWRITE)!== TRUE){
          H::dnd("Zip creation failed at this time");
        }
        //add files into zip
        //Add Directory
        if(!$zip->addEmptyDir("track_images"))
          H::dnd($zip);
        //Add album art
        $ext = explode('.',$album->url)[1];

        if(file_exists($album->url))
          $zip->addFile(ROOT.'/'.$album->url,'cover.'.$ext);
        else
            H::dnd("Album Art Not Successful.");

        // H::dnd($tracks);
        //Loop through each media object
        foreach($tracks as $track){
          // H::dnd($track);
          //setup variables
          $num = sprintf("%02d",$track->sort);
          $name = $num.'_'.$track->name;
          $audioExt = explode('.',$track->audioUrl)[1];
          $imageExt = explode('.',$track->imageUrl)[1];
          //Add tracks

          if(file_exists($track->audioUrl))
            $zip->addFile(ROOT.'/'.$track->audioUrl,$name.'.'.$audioExt);
          else
              H::dnd("Track Download Not Successful.");

          //Add images

          if(file_exists($track->imageUrl))
            $zip->addFile(ROOT.'/'.$track->imageUrl,'track_images/'.$name.'.'.$imageExt);
          else
              H::dnd("Track Download Not Successful.");
        }
        $zip->close();

        if(file_exists($path)){
          //push to download the Zip
          header('Content-type: application/zip');
          header('Content-Disposition: attachment; filename="'.$zip_name.'"');
          ob_get_clean();
          readfile(ROOT.DS.$path);
          ob_end_flush();
          unlink(ROOT.DS.$path);

        }
        else{
          H::dnd("error");
        }
      } else {
        H::dnd("Extension is not loaded");
      }
    }
  }
