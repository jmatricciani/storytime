<?php
  namespace App\Controllers;
  use Core\{Controller,Cookie,H, Session, Router};
  use App\Models\{Products,Carts,CartItems, Transactions, Inventory};
  use App\Lib\Gateways\Gateway;

  class CartController extends Controller {

    public function indexAction() {
      $cart_id = (Cookie::exists(CART_COOKIE_NAME))? Cookie::get(CART_COOKIE_NAME): false;
      $itemCount = 0;
      $subTotal = 0.00;
      $shippingTotal = 0.00;
      $products = Carts::findAllProductsByCartId((int)$cart_id);
      foreach($products as $product){
        $product->type= 'merch';
      }
      $stacks = Carts::findAllStacksByCartId((int)$cart_id);
      $items = array_merge($products,$stacks);

      foreach($items as $item){
        $itemCount += $item->qty;
        $shippingTotal += ($item->qty * $item->shipping);
        $subTotal += ($item->qty * $item->price);
      }
      $this->view->subTotal = number_format($subTotal,2);
      $this->view->shippingTotal = number_format($shippingTotal, 2);
      $this->view->grandTotal = number_format($subTotal + $shippingTotal, 2);
      $this->view->itemCount = $itemCount;
      $this->view->items = $items;
      $this->view->cartId = $cart_id;
      $this->view->render('cart/index');
    }

    public function addToCartAction(){
      if($this->request->isPost()){
        $this->request->csrfCheck();
        $cart = Carts::findCurrentCartOrCreateNew();
        // Get Option Value
        $inventory_id = '';
        foreach($this->request->get() as $input => $value){
          if($value == 'on')
            $inventory_id = $input;
        }
        $item = CartItems::addToCart($cart->id,(int)$inventory_id);
        $inv = Inventory::findById($item->inventory_id);
        if($item){
          // Do not allow cart qty to be above stocks
          if($item->qty < $inv->stock){
            $item->qty = $item->qty + 1;
            if($this->request->get('digital') == 'true'){
              $item->qty = 1;
            }
            $item->save();
          }
        }
        Session::addMsg('info',"Item Added!");
        Router::redirect('cart');
      }
    }

    public function changeQtyAction($direction,$item_id){
      $item = CartItems::findById((int)$item_id);
      if($direction == 'down'){
        $item->qty -= 1;
      } else {
        $item->qty += 1;
      }

      if($item->qty > 0){
        $item->save();
      }
      Session::addMsg('info',"Cart Updated");
      Router::redirect('cart');
    }

    public function updateItemQuantityAction($cart_item_id,$stock,$value){
      $new_value;
      if($value > $stock){
        Session::addMsg('danger',"Not enough in stock.");
        $new_value = $stock;
      }
      else if($value < 1){
        Session::addMsg('danger',"Cannot be less than one.");
        $new_value = 1;
      } else {
        Session::addMsg('info',"Cart Updated");
        $new_value = $value;
      }

      // update cart item quantity
      CartItems::updateQuantity($cart_item_id,$new_value);
      Router::redirect('cart');
    }

    public function removeItemAction($item_id){
      $item = CartItems::findById((int)$item_id);
      $item->delete();
      Session::addMsg('info',"Cart Updated");
      Router::redirect('cart');
    }

    public function checkoutAction($cart_id){
      $gw = Gateway::build();
      $gw->populateItems((int)$cart_id);
      $tx = new Transactions();



      if($this->request->isPost()){
        $whiteList = ['name','shipping_address1','shipping_address2','shipping_city','shipping_state','shipping_zip'];
        $this->request->csrfCheck();
        $tx->assign($this->request->get(),$whiteList,false);
        $tx->validateShipping();
        $step = $this->request->get('step');
        if($step == '2'){
          $resp = $gw->processForm($this->request->get());
          $tx = $resp['tx'];
          if($resp['success'] != true){
            $tx->addErrorMessage('card-element',$resp['msg']);
          } else {
            $cart_items = CartItems::findByCartId((int)$cart_id);
            //Update Inventory
            Inventory::updateAfterSuccess($cart_items);
            Router::redirect('cart/thankYou/'.$tx->id);
          }
        }
      }

      $this->view->gatewayToken = $gw->getToken();
      $this->view->formErrors = $tx->getErrorMessages();
      $this->view->tx = $tx;
      $this->view->grandTotal = $gw->grandTotal;
      $this->view->items = $gw->items;
      $this->view->cartId = $cart_id;
      if(!$this->request->isPost() || !$tx->validationPassed()){
        $this->view->render('cart/shipping_address_form');
      } else {
        $this->view->render($gw->getView());
      }
    }

    public function thankYouAction($tx_id){
      $tx = Transactions::findById((int)$tx_id);
      $this->view->tx = $tx;
      $this->view->render('cart/thankYou');
    }
  }
