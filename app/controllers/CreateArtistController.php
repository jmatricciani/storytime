<?php
namespace App\Controllers;
use Core\Controller;
use Core\Router;
use App\Models\Users;
use App\Models\Login;
use Core\H;
use Core\Session;

class CreateArtistController extends Controller {

  public function indexAction() {
    $newUser = new Users();
    if($this->request->isPost()) {
      $this->request->csrfCheck();
      $newUser->assign($this->request->get(),Users::blackListedFormKeys);
      $newUser->acl = '["Artist"]';
      // $newUser->acl = "[" . json_encode("Artist") . "]";
      $newUser->confirm =$this->request->get('confirm');
      if($newUser->save()){
        Router::redirect('login');
      }
    }
    $this->view->newUser = $newUser;
    $this->view->displayErrors = $newUser->getErrorMessages();
    $this->view->render('createArtist/index');
  }

}
