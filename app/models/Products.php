<?php
  namespace App\Models;
  use Core\{Model, DB, H};
  use Core\Validators\{RequiredValidator,NumericValidator};
  use App\Models\{Brands,ProductImages,Inventory};

  class Products extends Model {

    public $id, $created_at, $updated_at, $user_id, $name, $price, $shipping;
    public $body, $inventory_key, $deleted=0;
    const blackList = ['id','deleted','inventory_key'];
    protected static $_table = 'products';
    protected static $_softDelete = false;
    public $_stock;

    public function beforeSave(){
      $this->timeStamps();
    }

    public function validator(){
      $requiredFields = ['name'=>"Name",'price'=>'Price','shipping'=>'Shipping','body'=>'Body'];
      foreach($requiredFields as $field => $display){
        $this->runValidation(new RequiredValidator($this,['field'=>$field,'msg'=>$display." is required."]));
      }
      $this->runValidation(new NumericValidator($this,['field'=>'price','msg'=>'Price must be a number.']));
      $this->runValidation(new NumericValidator($this,['field'=>'shipping','msg'=>'Shipping must be a number.']));
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

    public static function getX($limit=0){
      $db = DB::getInstance();
      $where = "products.deleted = 0 AND pi.sort = '0'";


      if($limit == 0)
        return self::find();
      else{
        $sql = "
          SELECT products.*, pi.url as url FROM products
                JOIN product_images as pi
                ON products.id = pi.product_id

                WHERE {$where}
                LIMIT {$limit}

        ";

        return $db->query($sql)->results();
      }


    }

    public static function getXWithArtistName($limit=0){
      $db = DB::getInstance();
      $where = "products.deleted = 0 AND pi.sort = '0'";


      if($limit == 0)
        return self::find();
      else{
        $sql = "
          SELECT products.*, pi.url as url, users.artist_name as aName FROM products
                JOIN product_images as pi
                ON products.id = pi.product_id
                JOIN users
                ON products.user_id = users.id

                WHERE {$where}
                LIMIT {$limit}

        ";

        return $db->query($sql)->results();
      }


    }

    public static function splitByType($product_array){
      $split_array = array();
      $split_array['item'] = [];
      $split_array['apparel'] = [];

      foreach($product_array as $product){
        $inventory = Inventory::findByKey($product->inventory_key);
        $total_stock = 0;
        foreach($inventory as $inv){
          if($inv->option == 0){
            $product->_stock = $inv->stock;
            $split_array['item'][] = $product;
          } else {
            $total_stock += $inv->stock;
          }

        }

        if(!in_array($product,$split_array['item'])){
          $product->_stock = $total_stock;
          $split_array['apparel'][] = $product;
        }
      }

      return $split_array;


    }

    public static function findByIdAndUserId($id, $user_id){
      $conditions = [
        'conditions' => "id = ? AND user_id = ?",
        'bind' => [(int)$id, (int)$user_id]
      ];
      return self::findFirst($conditions);
    }


    public function displayShipping(){
      return ($this->shipping == 0)? "Free shipping" : "$" . $this->shipping;
    }

    public function getImages(){
      return ProductImages::find([
        'conditions' => "product_id = ?",
        'bind' => [$this->id],
        'order' => 'sort'
      ]);
    }
  }
