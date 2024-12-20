<?php
  namespace App\Controllers;
  use Core\{Router,H,Controller};
  use App\Models\{GalleryImages,Users};

  class GalleryController extends Controller {

    public function indexAction() {
      $gallery = GalleryImages::getXWithArtistName(8);
      $this->view->gallery = $gallery;
      $this->view->render('gallery/index');
    }

    public function detailsAction($image_id) {
      $image = GalleryImages::findById((int)$image_id);
      if(!$image){
        Session::addMsg('danger',"Oops...that image isn't available.");
        Router::redirect('/home');
      }
      $this->view->image = $image;
      $this->view->artist_name = Users::getArtistNameById($image->user_id);
      $this->view->render('gallery/details');
    }
  }
