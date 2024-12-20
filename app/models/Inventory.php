<?php
namespace App\Models;
use Core\{Model,Session,Cookie,DB,H};

class Inventory extends Model {

  public $id,$created_at,$updated_at,$inventory_key,$option=0,$stock;
  const blackList = ['id','inventory_key'];
  protected static $_table = 'inventory';

  public function beforeSave(){
    $this->timeStamps();
  }

  public static function buildInventory($option,$stock,$inventory_key){
    $inventory= new self();
    $inventory->inventory_key = $inventory_key;
    $inventory->option = $option;
    $inventory->stock = $stock;
    if(!$inventory->save()){
      H::dnd("something has gone wrong.. please notify admin");
    }
  }

  public static function buildInventories($available_stock,$inventory_key){
    foreach ($available_stock as $option) {
      self::buildInventory($option[0],$option[1],$inventory_key);
    }
  }

  public static function findByKey($key){
    $conditions = [
      'conditions' => "inventory_key = ?",
      'bind' => [(string)$key]
    ];
    return self::find($conditions);
  }

  public static function formatStocks($inventories){
    $stocks = array();
    foreach($inventories as $inv){
      array_push($stocks,array($inv->option, $inv->stock));
    }
    return $stocks;
  }

  public static function updateInventory($stocks,$key){
    self::deleteInventories($key);
    self::buildInventories($stocks,$key);
  }

  public static function deleteInventories($key){
    $inventories = self::findByKey($key);
    foreach($inventories as $inventory)
      $inventory->delete();
  }

  public function decrementInventory($qty){
    $this->stock -= $qty;
    if($this->stock < 0){
      $this->stock = 0;
    }
    $this->save();
  }

  public static function updateAfterSuccess($items){
    foreach($items as $item){
      $inv = self::findById($item->inventory_id);
      $inv->decrementInventory($item->qty);
    }
  }

}
