<?php
namespace App\Models;
use Core\{Model,Session,Cookie,DB,H};

class Carts extends Model {

  public $id,$created_at,$updated_at,$purchased=0,$deleted=0;
  protected static $_table = 'carts';
  protected static $_softDelete = true;

  public function beforeSave(){
    $this->timeStamps();
  }

  public static function findCurrentCartOrCreateNew(){
    if(!Cookie::exists(CART_COOKIE_NAME)){
      $cart = new Carts();
      $cart->save();
    } else {
      $cart_id = Cookie::get(CART_COOKIE_NAME);
      $cart = self::findById((int)$cart_id);
    }
    Cookie::set(CART_COOKIE_NAME,$cart->id,CART_COOKIE_EXPIRY);
    return $cart;
  }

  public static function findAllProductsByCartId($cart_id){
    $sql = "SELECT items.*, inv.inventory_key, inv.option, inv.stock, p.id as item_id, p.name as name, p.price as price, p.shipping as shipping, pi.url as url FROM cart_items as items
      JOIN inventory as inv ON inv.id = items.inventory_id
      JOIN products as p ON inv.inventory_key = p.inventory_key
      JOIN product_images as pi on p.id = pi.product_id
      WHERE items.cart_id = ? AND items.deleted = 0 AND pi.sort = 0";

    $db = DB::getInstance();
    return $db->query($sql,[(int)$cart_id])->results();
  }

  public static function findAllStacksByCartId($cart_id){
    $sql = "SELECT items.*, inv.inventory_key, inv.option, inv.stock, ms.id as item_id,ms.name as name, ms.price as price, ms.shipping as shipping,  msi.url as url, ms.type as type FROM cart_items as items
      JOIN inventory as inv ON inv.id = items.inventory_id
      JOIN media_stack as ms ON inv.inventory_key = ms.inventory_key
      JOIN media_stack_images as msi on ms.id = msi.stack_id
      WHERE items.cart_id = ? AND items.deleted = 0";

    $db = DB::getInstance();
    return $db->query($sql,[(int)$cart_id])->results();
  }

  public static function purchaseCart($cart_id){
    $cart = self::findById($cart_id);
    $cart->purchased = 1;
    $cart->save();
    Cookie::delete(CART_COOKIE_NAME);
    return $cart;
  }

  public static function itemCountCurrentCart(){
    if(! Cookie::exists(CART_COOKIE_NAME)){
      return 0;
    }
    $cart_id = Cookie::get(CART_COOKIE_NAME);
    $db = DB::getInstance();
    $sql = "SELECT SUM(qty) as qty FROM cart_items WHERE cart_id = ? AND deleted = 0";
    $result = $db->query($sql,[(int)$cart_id])->first();
    return $result->qty;
  }

}
