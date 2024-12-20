<?php
  namespace App\Controllers;
  use Core\Controller;
  use App\Models\{Products, Users, Media, MediaImages, MediaAudio, MediaStack, MediaStackImages, Inventory};
  use Core\H;

  class MusicController extends Controller {

    public function indexAction() {
      $media = Media::getXWithArtistName(4);
      $stacks = MediaStack::getXWithArtistName(4);
      $combine = array_merge($media,$stacks);
      $updated_at  = array_column($combine, 'updated_at');
      array_multisort($updated_at, SORT_DESC, $combine);
      $this->view->media = $combine;
      $this->view->render('music/index');
    }

    public function trackAction($name = ''){
      if($name != ''){
        $name =  str_replace('-', ' ', $name);
        $media = Media::findByNameTypeWithData($name,'track');
        $this->view->artist_name = Users::getArtistNameById($media->user_id);
        $this->view->track = $media;
        $this->view->render('music/track');
      } else {
        // Load Track Menu
      }

    }
    public function mixAction($name = ''){
      if($name != ''){
        $name =  str_replace('-', ' ', $name);
        $media = Media::findByNameTypeWithData($name,'mix');
        $this->view->artist_name = Users::getArtistNameById($media->user_id);
        $this->view->mix = $media;
        $this->view->render('music/mix');
      } else {
        // Load Track Menu
      }
    }
    public function albumAction($name = ''){
      if($name != ''){
        $name =  str_replace('-', ' ', $name);
        $stack = MediaStack::findByNameTypeWithData($name,'album');
        $media = Media::findAllByStackIdWithData($stack->id);
        $inv = Inventory::findByKey($stack->inventory_key);

        $this->view->inventory = $inv[0];
        $this->view->media = $media;
        $this->view->artist_name = Users::getArtistNameById($stack->user_id);
        $this->view->album = $stack;
        $this->view->render('music/album');
      } else {
        // Load Album Menu
      }

    }
    public function tapeAction($name = ''){
      if($name != ''){
        $name =  str_replace('-', ' ', $name);
        $stack = MediaStack::findByNameTypeWithData($name,'tape');
        $media = Media::findAllByStackIdWithData($stack->id);
        $inv = Inventory::findByKey($stack->inventory_key);
        $this->view->inventory = $inv[0];
        $this->view->media = $media;
        $this->view->artist_name = Users::getArtistNameById($stack->user_id);
        $this->view->tape = $stack;
        $this->view->render('music/tape');
      } else {
        // Load Album Menu
      }
    }
    public function vinylAction($name = ''){
      $this->view->render('music/vinyl');
    }
  }
