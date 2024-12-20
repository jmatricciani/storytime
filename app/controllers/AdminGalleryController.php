<?php
namespace App\Controllers;

use Core\{Controller,H,Session,Router};
use App\Models\{GalleryImages,Users};
use App\Lib\Utilities\{Uploads,UploadImages,UploadAudio,UploadOneImage,UploadOneAudio};

class AdminGalleryController extends Controller {

  public function onConstruct(){
    $this->view->setLayout('admin');
    $this->currentUser = Users::currentUser();
  }

  public function indexAction(){

    $images = GalleryImages::findByUserId($this->currentUser->id);
    if(!$images){
      Session::addMsg('info',"No Images, use the upload menu to add one!");
    }
    $this->view->images = $images;
    $this->view->render('admingallery/index');

  }

  public function uploadAction(){
    $image = new GalleryImages();
    if($this->request->isPost()){
      $this->request->csrfCheck();

      //Validate File Upload
      $files = $_FILES['galleryImage'];
      if($files['tmp_name'][0] == ''){
        $image->addErrorMessage('galleryImage[]','You must choose an image.');
      } else {
        $uploads = new Uploads($files);
        $uploads->runValidation();
        $imagesErrors = $uploads->validates();
        if(is_array($imagesErrors)){
          $msg = "";
          foreach($imagesErrors as $name => $message){
            $msg .= $message . " ";
          }
          $image->addErrorMessage('galleryImage[]',trim($msg));
        }
      }
      $image->assign($this->request->get());
      $image->user_id = $this->currentUser->id;
      if($image->validationPassed()){
        $image->save();
        //Upload Image and Store in DB
        $image = GalleryImages::uploadGalleryImages($image,$uploads);
        //redirect//redirect
        Session::addMsg('success','Image Added!');
        Router::redirect('adminGallery');
      }
    }
    $this->view->image = $image;
    $this->view->displayErrors = $image->getErrorMessages();
    $this->view->formAction = PROOT.'adminGallery/upload';
    $this->view->render('admingallery/upload');
  }

  public function editAction($image_id){
    $image = GalleryImages::findById($image_id);
    if($this->request->isPost()){
      $this->request->csrfCheck();
      $image->assign($this->request->get());
      if($image->save()){
        Session::addMsg('success','Image Edited!');
        Router::redirect('adminGallery');
      }
    }
    $this->view->image = $image;
    $this->view->displayErrors = $image->getErrorMessages();
    $this->view->formAction = PROOT.'adminGallery/edit';
    $this->view->render('admingallery/edit');
  }

  public function deleteAction(){
    $resp = ['success'=>false,'msg'=>'Something went wrong...'];
    if($this->request->isPost()){
      $id = $this->request->get('id');
      $image = GalleryImages::findByIdAndUserId($id, $this->currentUser->id);
      if($image){
        GalleryImages::deleteImage($id,true);
        $resp = ['success' => true, 'msg' => 'Image Deleted.','model_id' => $id];
      }
    }
    $this->jsonResponse($resp);
  }

}
