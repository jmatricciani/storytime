<?php
  namespace App\Controllers;
  use Core\{Router,H,Controller};
  use App\Models\{Products,Users,Inventory};

  class MerchController extends Controller {

    public function indexAction() {
      $products = Products::getXWithArtistName(8);
      $this->view->products = $products;
      $this->view->render('merch/index');
    }

    public function detailsAction($product_id) {
      $product = Products::findById((int)$product_id);
      if(!$product){
        Session::addMsg('danger',"Oops...that product isn't available.");
        Router::redirect('/home');
      }
      $this->view->product = $product;
      $this->view->artist_name = Users::getArtistNameById($product->user_id);
      $this->view->inventories = Inventory::findByKey($product->inventory_key);
      $this->view->images = $product->getImages();
      $this->view->render('merch/details');
    }
  }
