<?php
namespace App\Controllers;

use Core\{Controller,H,Session,Router};
use App\Models\{Products,ProductImages,Users,Inventory};
use App\Lib\Utilities\Uploads;

class AdminMerchController extends Controller {

  public function onConstruct(){
    $this->view->setLayout('admin');
    $this->currentUser = Users::currentUser();
  }

  public function indexAction(){
    $products = Products::findByUserId($this->currentUser->id);

    $split = Products::splitByType($products);

    $this->view->products = $products;
    $this->view->apparel = $split['apparel'];
    $this->view->items = $split['item'];
    $this->view->render('adminmerch/index');
  }

  public function uploadItemAction(){
    $product = new Products();
    $productImage = new ProductImages();
    $stock = '';
    if($this->request->isPost()){
      $this->request->csrfCheck();
      $files = $_FILES['productImages'];

      //Validate Inventory

      $stock = $this->request->get('stock');

      if(!$stock)
        $product->addErrorMessage('stock','You must enter some quantity to be sold.');
      else if(!ctype_digit($stock)){
        $product->addErrorMessage('stock','Stock must be a number');
      }

      if($files['tmp_name'][0] == ''){
        $product->addErrorMessage('productImages[]','You must choose an image.');
      } else {
        $uploads = new Uploads($files);
        $uploads->runValidation();
        $imagesErrors = $uploads->validates();
        if(is_array($imagesErrors)){
          $msg = "";
          foreach($imagesErrors as $name => $message){
            $msg .= $message . " ";
          }
          $product->addErrorMessage('productImages[]',trim($msg));
        }

      }
      $product->assign($this->request->get(),Products::blackList);

      $product->user_id = $this->currentUser->id;
      $product->save();
      if($product->validationPassed()){
        $product->inventory_key = md5($product->name.uniqid().$product->body);
        $product->save();
        //upload images
        ProductImages::uploadProductImages($product->id,$uploads);

        //upload inventory
        Inventory::buildInventory(0,$stock,$product->inventory_key);

        //redirect
        Session::addMsg('success','Item Added!');
        Router::redirect('adminMerch');
      }
    }
    $this->view->product = $product;
    $this->view->stock = $stock;
    $this->view->formAction = PROOT.'adminMerch/uploadItem';
    $this->view->displayErrors = $product->getErrorMessages();
    $this->view->render('adminmerch/uploaditem');
  }
  public function uploadApparelAction(){

    $product = new Products();
    $productImage = new ProductImages();
    $stocks = array();
    if($this->request->isPost()){
      $this->request->csrfCheck();

      //validate inventory
      $options = Router::getOptions('options')['apparel'];

      foreach($options['womens'] as $option){
        $stock = $this->request->get($option);
        if($stock){
          if(ctype_digit($stock)){
            array_push($stocks,array($option, $stock));
          }
          else{
            $product->addErrorMessage($option,$option.' must be a number');
          }
        }
      }
      foreach($options['mens'] as $option){
        $stock = $this->request->get($option);
        if($stock){
          if(ctype_digit($stock)){
            array_push($stocks,array($option, $stock));
          }
          else{
            $product->addErrorMessage($option,$option.' must be a number');
          }
        }

      }
      if(!$stocks){
        $product->addErrorMessage('total_stock','You must enter some quantity to be sold.');
      }

      $files = $_FILES['productImages'];
      if($files['tmp_name'][0] == ''){
        $product->addErrorMessage('productImages[]','You must choose an image.');
      } else {
        $uploads = new Uploads($files);
        $uploads->runValidation();
        $imagesErrors = $uploads->validates();
        if(is_array($imagesErrors)){
          $msg = "";
          foreach($imagesErrors as $name => $message){
            $msg .= $message . " ";
          }
          $product->addErrorMessage('productImages[]',trim($msg));
        }

      }
      $product->assign($this->request->get(),Products::blackList);
      $product->user_id = $this->currentUser->id;
      $product->save();
      if($product->validationPassed()){
        $product->inventory_key = md5($product->name.uniqid().$product->body);
        $product->save();
        //upload images
        ProductImages::uploadProductImages($product->id,$uploads);
        //create inventories
        Inventory::buildInventories($stocks,$product->inventory_key);
        //redirect
        Session::addMsg('success','Item Added!');
        Router::redirect('adminMerch');
      }
    }
    $this->view->product = $product;
    $this->view->stocks = $stocks;
    $this->view->total_stock = '';
    $this->view->formAction = PROOT.'adminMerch/uploadApparel';
    $this->view->displayErrors = $product->getErrorMessages();
    $this->view->render('adminmerch/uploadapparel');
  }

  public function editItemAction($product_id){
    $product = Products::findByIdAndUserId($product_id,$this->currentUser->id);
    if(!$product){
      H::dnd('fuck');
    }
    $inventory = Inventory::findByKey($product->inventory_key);
    $images = ProductImages::findByProductId($product->id);

    $inventory = $inventory[0];
    $stock = $inventory->stock;
    if($this->request->isPost()){
      $this->request->csrfCheck();
      $files = $_FILES['productImages'];

      //Validate Inventory

      $stock = $this->request->get('stock');

      if(!$stock)
        $product->addErrorMessage('stock','You must enter some quantity to be sold.');
      else if(!ctype_digit($stock)){
        $product->addErrorMessage('stock','Stock must be a number');
      }

      $isFiles = $files['tmp_name'][0] != '';
      if($isFiles){
        // $productImage = new ProductImages();
        $uploads = new Uploads($files);
        $uploads->runValidation();
        $imagesErrors = $uploads->validates();
        if(is_array($imagesErrors)){
          $msg = "";
          foreach($imagesErrors as $name => $message){
            $msg .= $message . " ";
          }
          $product->addErrorMessage('productImages',trim($msg));
        }
      }
      $product->assign($this->request->get(),Products::blackList);

      $product->user_id = $this->currentUser->id;
      $product->save();
      if($product->validationPassed()){
        if($isFiles){
          //upload images
          ProductImages::uploadProductImages($product->id,$uploads);
        }
        $sortOrder = json_decode($_POST['images_sorted']);
        ProductImages::updateSortByProductId($product->id,$sortOrder);

        //edit inventory
        $inventory->stock = $stock;
        $inventory->save();

        //redirect
        Session::addMsg('success','Item Edited!');
        Router::redirect('adminMerch');
      }
    }

    $this->view->product = $product;
    $this->view->stock = $stock;
    $this->view->images = $images;
    $this->view->displayErrors = $product->getErrorMessages();
    $this->view->render('adminmerch/edititem');
  }
  public function editApparelAction($product_id){
    $product = Products::findByIdAndUserId($product_id,$this->currentUser->id);
    if(!$product){
      H::dnd('fuck');
    }
    $inventories = Inventory::findByKey($product->inventory_key);
    $stocks = Inventory::formatStocks($inventories);
    $pre_stocks = $stocks;
    $images = ProductImages::findByProductId($product->id);

    if($this->request->isPost()){
      $this->request->csrfCheck();
      $stocks = array();
      $files = $_FILES['productImages'];

      //validate inventory
      $options = Router::getOptions('options')['apparel'];

      foreach($options['womens'] as $option){
        $stock = $this->request->get($option);
        if($stock){
          if(ctype_digit($stock)){
            array_push($stocks,array($option, $stock));
          }
          else{
            $product->addErrorMessage($option,$option.' must be a number');
          }
        }
      }
      foreach($options['mens'] as $option){
        $stock = $this->request->get($option);
        if($stock){
          if(ctype_digit($stock)){
            array_push($stocks,array($option, $stock));
          }
          else{
            $product->addErrorMessage($option,$option.' must be a number');
          }
        }

      }
      if(!$stocks){
        $product->addErrorMessage('total_stock','You must enter some quantity to be sold.');
      }

      $isFiles = $files['tmp_name'][0] != '';
      if($isFiles){
        // $productImage = new ProductImages();
        $uploads = new Uploads($files);
        $uploads->runValidation();
        $imagesErrors = $uploads->validates();
        if(is_array($imagesErrors)){
          $msg = "";
          foreach($imagesErrors as $name => $message){
            $msg .= $message . " ";
          }
          $product->addErrorMessage('productImages',trim($msg));
        }
      }
      $product->assign($this->request->get(),Products::blackList);

      $product->user_id = $this->currentUser->id;
      $product->save();
      if($product->validationPassed()){
        if($isFiles){
          //upload images
          ProductImages::uploadProductImages($product->id,$uploads);
        }
        $sortOrder = json_decode($_POST['images_sorted']);
        ProductImages::updateSortByProductId($product->id,$sortOrder);

        //edit inventory
        Inventory::updateInventory($stocks,$product->inventory_key);

        //redirect
        Session::addMsg('success','Apparel Edited!');
        Router::redirect('adminMerch');
      }
    }

    $this->view->product = $product;
    $this->view->stocks = $stocks;
    $this->view->images = $images;
    $this->view->total_stock = '';
    $this->view->displayErrors = $product->getErrorMessages();
    $this->view->render('adminmerch/editapparel');
  }

  public function deleteAction(){
    $resp = ['success'=>false,'msg'=>'Something went wrong...'];
    if($this->request->isPost()){
      $id = $this->request->get('id');
      $product = Products::findByIdAndUserId($id, $this->currentUser->id);
      if($product){
        ProductImages::deleteImages($id,true);
        Inventory::deleteInventories($product->inventory_key);
        $product->delete();
        $resp = ['success' => true, 'msg' => 'Product Deleted.','model_id' => $id];
      }
    }
    $this->jsonResponse($resp);
  }

}
