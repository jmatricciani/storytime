<?php
namespace App\Models;
use Core\{Model,Session,Cookie,H};
use Core\Validators\RequiredValidator;
use App\Models\{Carts,Products};

class CartItems extends Model {

  public $id,$created_at,$updated_at,$cart_id,$inventory_id,$qty=0,$deleted=0;
  protected static $_table = 'cart_items';
  protected static $_softDelete = true;

  public function beforeSave(){
    $this->timeStamps();
  }

  public static function findByInventoryIdOrCreate($cart_id,$inventory_id){
    $item = self::findFirst([
      'conditions' => "inventory_id = ? AND cart_id = ?",
      'bind' => [(int)$inventory_id,(int)$cart_id]
    ]);
    if(!$item){
      $item = new self();
      $item->cart_id = $cart_id;
      $item->inventory_id = $inventory_id;
    }
    return $item;
  }

  public static function addToCart($cart_id,$inventory_id){
    $inventory = Inventory::findById((int)$inventory_id);
    if($inventory){
      return self::findByInventoryIdOrCreate($cart_id, $inventory->id);
    }
    return false;
  }

  public static function findByCartId($cart_id){
    return self::find([
      'conditions' => "cart_id = ?",
      'bind' => [(int)$cart_id]
    ]);
  }

  public static function updateQuantity($id,$quantity){
    $cart_item = self::findById((int)$id);
    $cart_item->qty = $quantity;
    $cart_item->save();
  }

}
