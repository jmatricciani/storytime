<?php
  namespace App\Controllers;
  use Core\Controller;
  use App\Models\{Products, Brands};
  use Core\H;

  class DonateController extends Controller {

    public function indexAction() {
      $this->view->render('donate/index');
    }
  }
