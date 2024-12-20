<?php
  namespace App\Controllers;
  use Core\Controller;
  use App\Models\{Products, Brands};
  use Core\H;

  class HomeController extends Controller {

    public function indexAction() {
      $this->view->render('home/index');
    }
  }
