<?php
  namespace App\Lib\Gateways;
  use App\Models\Carts;

  abstract class AbstractGateway{
    public $cart_id, $items, $itemCount=0, $subTotal=0, $shippingTotal=0, $grandTotal=0;
    public $chargeSuccess=false, $msgToUser='';

    public function populateItems($cart_id){
      $this->cart_id = $cart_id;
      $products = Carts::findAllProductsByCartId((int)$cart_id);
      $stacks = Carts::findAllStacksByCartId((int)$cart_id);
      $this->items = array_merge($products,$stacks);
      foreach($this->items as $item){
        $this->itemCount += $item->qty;
        $this->subTotal += ($item->price * $item->qty);
        $this->shippingTotal += ($item->shipping * $item->qty);
      }
      $this->grandTotal = $this->subTotal + $this->shippingTotal;
    }

    abstract public function getView();
    abstract public function processForm($post);
    abstract public function charge($data);
    abstract public function handleChargeResp($ch);
    abstract public function createTransaction($ch);
    abstract public function getToken();
  }
