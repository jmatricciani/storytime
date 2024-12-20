<?php
namespace App\Controllers;

use Core\{Controller,H,Session,Router};
use App\Models\{Products,ProductImages,Media,MediaImages,MediaAudio,MediaStack,MediaStackImages,Users,Inventory};
use App\Lib\Utilities\{Uploads,UploadOneImage,UploadOneAudio,UploadImages,UploadAudio};

class AdminMusicController extends Controller {

  public function onConstruct(){
    $this->view->setLayout('admin');
    $this->currentUser = Users::currentUser();
  }

  public function indexAction(){
    $this->view->tracks = Media::findByUserIdTypeWithData($this->currentUser->id,'track');
    $this->view->mixes = Media::findByUserIdTypeWithData($this->currentUser->id,'mix');
    $this->view->albums = MediaStack::findByUserIdType($this->currentUser->id,'album');
    $this->view->tapes = MediaStack::findByUserIdType($this->currentUser->id,'tape');
    $this->view->render('adminmusic/index');
  }

  public function uploadTrackAction(){
    $media = new Media();
    if($this->request->isPost()){
      $this->request->csrfCheck();
      //Verify Media Images
      $files = $_FILES['mediaImage'];
      if($files['tmp_name'][0] == ''){
        $media->addErrorMessage('mediaImage','You must choose an image.');
      } else {
        $imageUpload = new UploadOneImage($files);
        $imageUpload->runValidation();
        $imagesErrors = $imageUpload->validates();
        if(is_array($imagesErrors)){
          $msg = "";
          foreach($imagesErrors as $name => $message){
            $msg .= $message . " ";
          }
          $media->addErrorMessage('mediaImage',trim($msg));
        }
      }
      //Verify Media Audio

      $files = $_FILES['mediaAudio'];
      if($files['tmp_name'][0] == ''){
        $media->addErrorMessage('mediaAudio','You must add an audio file.');
      } else {
        $audioUpload = new UploadOneAudio($files);
        $audioUpload->runValidation();
        $audioErrors = $audioUpload->validates();
        if(is_array($audioErrors)){
          $msg = "";
          foreach($audioErrors as $name => $message){
            $msg .= $message . " ";
          }
          $media->addErrorMessage('mediaAudio',trim($msg));
        }
      }

      $media->assign($this->request->get(),Media::blackList);
      $media->user_id = $this->currentUser->id;
      $media->save();
      if($media->validationPassed()){
        $media->type = 'track';
        $media->save();
        //upload images
        MediaImages::uploadMediaImage($media->id,$imageUpload);
        //upload audio
        MediaAudio::uploadMediaAudio($media->id,$audioUpload);
        //redirect
        Session::addMsg('success','Track Added!');
        Router::redirect('adminMusic');
      }
    }
    $this->view->media = $media;
    $this->view->formAction = PROOT.'adminMusic/uploadtrack';
    $this->view->displayErrors = $media->getErrorMessages();
    $this->view->render('adminmusic/uploadtrack');
  }

  public function editTrackAction($track_id){
    $user = Users::currentUser();
    $media = Media::findByIdAndUserId((int)$track_id,(int)$user->id);
    if(!$media){
      Session::addMsg('danger','You do not have permission to edit that track');
      Router::redirect('adminmedia');
    }
    $image = MediaImages::findByMediaId($media->id);
    $audio = MediaAudio::findByMediaId($media->id);
    if($this->request->isPost()){
      $this->request->csrfCheck();
      //Verify Media Images
      $files = $_FILES['mediaImage'];

      if($files['tmp_name'][0] == ''){

      } else {
        $imageUpload = new UploadOneImage($files);
        $imageUpload->runValidation();
        $imagesErrors = $imageUpload->validates();
        if(is_array($imagesErrors)){
          $msg = "";
          foreach($imagesErrors as $name => $message){
            $msg .= $message . " ";
          }
          $media->addErrorMessage('mediaImage',trim($msg));
        } else {
          // Image Validation passed... move file
          $newImage = $imageUpload->getFile();
          copy($newImage["tmp_name"],$image->url);
        }
      }
      //Verify Media Audio

      $files = $_FILES['mediaAudio'];
      if($files['tmp_name'][0] == ''){

      } else {
        $audioUpload = new UploadOneAudio($files);
        $audioUpload->runValidation();
        $audioErrors = $audioUpload->validates();
        if(is_array($audioErrors)){
          $msg = "";
          foreach($audioErrors as $name => $message){
            $msg .= $message . " ";
          }
          $media->addErrorMessage('mediaAudio',trim($msg));
        } else {
          // Audio Validation passed... move file
          $newAudio = $audioUpload->getFile();
          copy($newAudio["tmp_name"],$audio->url);
        }
      }

      $media->assign($this->request->get(),Media::blackList);
      $media->user_id = $this->currentUser->id;
      $media->save();
      if($media->validationPassed()){
        //redirect
        Session::addMsg('success',$media->name.' Edited!');
        Router::redirect('adminMusic');
      }
    }
    $this->view->media = $media;
    $this->view->audio = $audio;
    $this->view->image = $image;
    $this->view->displayErrors = $media->getErrorMessages();
    $this->view->render('adminmusic/edittrack');
  }

  public function uploadMixAction(){
    $media = new Media();
    if($this->request->isPost()){
      $this->request->csrfCheck();
      //Verify Media Images
      $files = $_FILES['mediaImage'];

      if($files['tmp_name'][0] == ''){
        $media->addErrorMessage('mediaImage','You must choose an image.');
      } else {
        $imageUpload = new UploadOneImage($files);
        $imageUpload->runValidation();
        $imagesErrors = $imageUpload->validates();
        if(is_array($imagesErrors)){
          $msg = "";
          foreach($imagesErrors as $name => $message){
            $msg .= $message . " ";
          }
          $media->addErrorMessage('mediaImage',trim($msg));
        }
      }
      //Verify Media Audio

      $files = $_FILES['mediaAudio'];
      if($files['tmp_name'][0] == ''){
        $media->addErrorMessage('mediaAudio','You must add an audio file.');
      } else {
        $audioUpload = new UploadOneAudio($files);
        $audioUpload->runValidation();
        $audioErrors = $audioUpload->validates();
        if(is_array($audioErrors)){
          $msg = "";
          foreach($audioErrors as $name => $message){
            $msg .= $message . " ";
          }
          $media->addErrorMessage('mediaAudio',trim($msg));
        }
      }

      $media->assign($this->request->get(),Media::blackList);
      $media->user_id = $this->currentUser->id;
      $media->save();
      if($media->validationPassed()){
        $media->type = 'mix';
        $media->save();
        //upload images
        MediaImages::uploadMediaImage($media->id,$imageUpload);
        //upload audio
        MediaAudio::uploadMediaAudio($media->id,$audioUpload);
        //redirect
        Session::addMsg('success','Mix Added!');
        Router::redirect('adminMusic');
      }
    }
    $this->view->media = $media;
    $this->view->formAction = PROOT.'adminMusic/uploadmix';
    $this->view->displayErrors = $media->getErrorMessages();
    $this->view->render('adminmusic/uploadmix');
  }

  public function editMixAction($mix_id){
    $user = Users::currentUser();
    $media = Media::findByIdAndUserId((int)$mix_id,(int)$user->id);
    if(!$media){
      Session::addMsg('danger','You do not have permission to edit that media');
      Router::redirect('adminmedia');
    }
    $image = MediaImages::findByMediaId($media->id);
    $audio = MediaAudio::findByMediaId($media->id);
    if($this->request->isPost()){
      $this->request->csrfCheck();
      //Verify Media Images
      $files = $_FILES['mediaImage'];

      if($files['tmp_name'][0] == ''){

      } else {
        $imageUpload = new UploadOneImage($files);
        $imageUpload->runValidation();
        $imagesErrors = $imageUpload->validates();
        if(is_array($imagesErrors)){
          $msg = "";
          foreach($imagesErrors as $name => $message){
            $msg .= $message . " ";
          }
          $media->addErrorMessage('mediaImage',trim($msg));
        } else {
          $newImage = $imageUpload->getFile();
          copy($newImage["tmp_name"],$image->url);
        }
      }
      //Verify Media Audio

      $files = $_FILES['mediaAudio'];
      if($files['tmp_name'][0] == ''){

      } else {
        $audioUpload = new UploadOneAudio($files);
        $audioUpload->runValidation();
        $audioErrors = $audioUpload->validates();
        if(is_array($audioErrors)){
          $msg = "";
          foreach($audioErrors as $name => $message){
            $msg .= $message . " ";
          }
          $media->addErrorMessage('mediaAudio',trim($msg));
        } else {
          $newAudio = $audioUpload->getFile();
          copy($newAudio["tmp_name"],$audio->url);
        }
      }

      $media->assign($this->request->get(),Media::blackList);
      $media->user_id = $this->currentUser->id;
      $media->save();
      if($media->validationPassed()){

        //redirect
        Session::addMsg('success',$media->name.' Edited!');
        Router::redirect('adminMusic');
      }
    }
    $this->view->media = $media;
    $this->view->audio = $audio;
    $this->view->image = $image;
    $this->view->displayErrors = $media->getErrorMessages();
    $this->view->render('adminmusic/editmix');
  }
  public function uploadAlbumAction(){
    $album = new MediaStack();
    if($this->request->isPost()){
      $this->request->csrfCheck();

      $numTracks = $this->request->get('numTracks');
      if($numTracks == 0){
        $album->addErrorMessage('numTracks','Album has no tracks');
      }
      else{
        //Verify Album Art
        $albumArtFiles = $_FILES['albumArt'];
        if($albumArtFiles['tmp_name'][0] == ''){
          $album->addErrorMessage('album','You must choose an image for the album art.');
        } else {
          $albumArtUpload = new Uploads($albumArtFiles);
          $albumArtUpload->runValidation();
          $albumArtErrors = $albumArtUpload->validates();
          if(is_array($albumArtErrors)){
            $msg = "";
            foreach($albumArtErrors as $name => $message){
              $msg .= $message . " ";
            }
            $album->addErrorMessage('albumArt',trim($msg));
          }
        }
        //Verify Track Images
        $mediaImageFiles = $_FILES['mediaImage'];
        $numMediaImageUploads = 0;
        foreach($mediaImageFiles["tmp_name"] as $key => $val){
          if($mediaImageFiles["tmp_name"][$key] != "")
            $numMediaImageUploads++;
        }
        if($numTracks != $numMediaImageUploads){
          $album->addErrorMessage('mediaImage','You must submit all track images.');
        } else {
          $imageUpload = new UploadImages($mediaImageFiles);
          $imageUpload->runValidation();
          $imagesErrors = $imageUpload->validates();
          if(is_array($imagesErrors)){
            $msg = "";
            foreach($imagesErrors as $name => $message){
              $msg .= $message . " ";
            }
            $album->addErrorMessage('mediaImage',trim($msg));
          }
        }

        //Verify Audio
        $audioFiles = $_FILES['mediaAudio'];
        $numAudioUploads = 0;
        foreach($audioFiles["tmp_name"] as $key => $val){
          if($audioFiles["tmp_name"][$key] != "")
            $numAudioUploads++;
        }
        if($numTracks != $numAudioUploads){
          $album->addErrorMessage('mediaAudio','You must submit all audio files.');
        } else {
          $audioUpload = new UploadAudio($audioFiles);
          $audioUpload->runValidation();
          $audioErrors = $audioUpload->validates();
          if(is_array($audioErrors)){
            $msg = "";
            foreach($audioErrors as $name => $message){
              $msg .= $message . " ";
            }
            $album->addErrorMessage('mediaAudio',trim($msg));
          }
        }
      }
      $album->assign($this->request->get());
      $album->user_id = $this->currentUser->id;
      $album->type = 'album';
      $album->save();
      if($album->validationPassed()){
        //upload Media objects with audio and image
        for($i = 1; $i<=$numTracks;$i++){
          $media = new Media();
          $media->name = ($_POST['mediaNames'][$i-1] != "") ? $_POST['mediaNames'][$i-1] :'untitled';
          $media->user_id = $this->currentUser->id;
          $media->stack_id = $album->id;
          $media->sort = $i;
          $media->label = $i;
          $media->save();

          if($media->validationPassed()){
            MediaImages::uploadImage($media->id,$imageUpload,$i-1);
            MediaAudio::uploadAudio($media->id,$audioUpload,$i-1);
          }
        }
        $album->inventory_key = md5($album->name.uniqid().$album->body);
        $album->save();
        Inventory::buildInventory(0,LARGEST_INT,$album->inventory_key);

        //upload album Art
        MediaStackImages::uploadAlbumImages($album->id,$albumArtUpload);
        //redirect
        Session::addMsg('success','Album Added!');
        Router::redirect('adminMusic/index');
      }

    }
    $this->view->album = $album;
    $this->view->formAction = PROOT.'adminMusic/uploadAlbum';
    $this->view->displayErrors = $album->getErrorMessages();
    $this->view->render('adminmusic/uploadalbum');
  }


  function changeTrackValueAction(){
    $resp = ['success'=>false,'msg'=>'Something went wrong...'];
    if($this->request->isPost()){
      $numTracks = $this->request->get('num');
      $type = $this->request->get('type');
      $msg = $type." has ".$numTracks." tracks.";
      $resp = ['success' => true, 'msg' => $msg];
    }
    $this->jsonResponse($resp);
  }


  public function editAlbumAction($stack_id){
    $user = Users::currentUser();
    $album = MediaStack::findByIdAndUserId((int)$stack_id,(int)$user->id);
    if(!$album){
      Session::addMsg('danger','You do not have permission to edit that album');
      Router::redirect('adminmusic');
    }
    $albumArt = MediaStackImages::findByAlbumId($album->id);
    $albumMedia = Media::findAllByStackIdWithData($album->id);

    $this->view->album = $album;
    $this->view->albumArt = $albumArt;
    $this->view->albumMedia = $albumMedia;

    $this->view->displayErrors = $album->getErrorMessages();
    $this->view->render('adminmusic/editalbum');


  }
  public function uploadTapeAction(){
    $tape = new MediaStack();
    $stock = '';
    if($this->request->isPost()){
      $this->request->csrfCheck();

      //Verify Album Art
      $tapeArtFiles = $_FILES['tapeArt'];
      if($tapeArtFiles['tmp_name'][0] == ''){
        $tape->addErrorMessage('tapeArt[]','You must choose an image for the tape.');
      } else {
        $tapeArtUpload = new Uploads($tapeArtFiles);
        $tapeArtUpload->runValidation();
        $tapeArtErrors = $tapeArtUpload->validates();
        if(is_array($tapeArtErrors)){
          $msg = "";
          foreach($tapeArtErrors as $name => $message){
            $msg .= $message . " ";
          }
          $tape->addErrorMessage('tapeArt[]',trim($msg));
        }
      }

      // Validate Inventory
      $stock = $this->request->get('stock');

      if(!$stock)
        $tape->addErrorMessage('stock','You must enter some quantity to be sold.');
      else if(!ctype_digit($stock)){
        $tape->addErrorMessage('stock','Stock must be a number');
      }

      $numTracks = $this->request->get('numTracks');
      if($numTracks == 0){
        $tape->addErrorMessage('numTracks','Tape has no tracks');
      }
      else{

        //Verify Track Images
        $mediaImageFiles = $_FILES['mediaImage'];
        $numMediaImageUploads = 0;
        foreach($mediaImageFiles["tmp_name"] as $key => $val){
          if($mediaImageFiles["tmp_name"][$key] != "")
            $numMediaImageUploads++;
        }
        if($numTracks != $numMediaImageUploads){
          $tape->addErrorMessage('mediaImage','You must submit all track images.');
        } else {
          $imageUpload = new UploadImages($mediaImageFiles);
          $imageUpload->runValidation();
          $imagesErrors = $imageUpload->validates();
          if(is_array($imagesErrors)){
            $msg = "";
            foreach($imagesErrors as $name => $message){
              $msg .= $message . " ";
            }
            $tape->addErrorMessage('mediaImage',trim($msg));
          }
        }

        //Verify Audio
        $audioFiles = $_FILES['mediaAudio'];
        $numAudioUploads = 0;
        foreach($audioFiles["tmp_name"] as $key => $val){
          if($audioFiles["tmp_name"][$key] != "")
            $numAudioUploads++;
        }
        if($numTracks != $numAudioUploads){
          $tape->addErrorMessage('mediaAudio','You must submit all audio files.');
        } else {
          $audioUpload = new UploadAudio($audioFiles);
          $audioUpload->runValidation();
          $audioErrors = $audioUpload->validates();
          if(is_array($audioErrors)){
            $msg = "";
            foreach($audioErrors as $name => $message){
              $msg .= $message . " ";
            }
            $tape->addErrorMessage('mediaAudio',trim($msg));
          }
        }
      }
      $tape->assign($this->request->get());
      $tape->user_id = $this->currentUser->id;
      $tape->type = 'tape';
      $tape->save();
      if($tape->validationPassed()){
        //upload Media objects with audio and image
        for($i = 1; $i<=$numTracks;$i++){
          $media = new Media();
          $media->name = ($_POST['mediaNames'][$i-1] != "") ? $_POST['mediaNames'][$i-1] :'untitled';
          $media->user_id = $this->currentUser->id;
          $media->stack_id = $tape->id;
          $media->sort = $i;
          $media->label = $this->request->get()['mediaLabels'][$i-1];
          $media->save();

          if($media->validationPassed()){
            MediaImages::uploadImage($media->id,$imageUpload,$i-1);
            MediaAudio::uploadAudio($media->id,$audioUpload,$i-1);
          }
        }
        $tape->inventory_key = md5($tape->name.uniqid().$tape->body);
        $tape->save();
        Inventory::buildInventory(0,$stock,$tape->inventory_key);

        //upload album Art
        MediaStackImages::uploadAlbumImages($tape->id,$tapeArtUpload);
        //redirect
        Session::addMsg('success','Tape Added!');
        Router::redirect('adminMusic/index');
      }

    }
    $this->view->stock = $stock;
    $this->view->tape = $tape;
    $this->view->formAction = PROOT.'adminMusic/uploadTape';
    $this->view->displayErrors = $tape->getErrorMessages();
    $this->view->render('adminmusic/uploadtape');
  }

  public function editTapeAction($stack_id){
    $user = Users::currentUser();
    $tape = MediaStack::findByIdAndUserId((int)$stack_id,(int)$user->id);
    if(!$tape){
      Session::addMsg('danger','You do not have permission to edit that tape');
      Router::redirect('adminmusic');
    }
    $tapeArt = MediaStackImages::findByAlbumId($tape->id);
    $tapeMedia = Media::findAllByStackIdWithData($tape->id);

    $this->view->tape = $tape;
    $this->view->tapeArt = $tapeArt;
    $this->view->tapeMedia = $tapeMedia;

    $this->view->displayErrors = $tape->getErrorMessages();
    $this->view->render('adminmusic/edittape');
  }

  public function deleteAction(){
    $resp = ['success'=>false,'msg'=>'Something went wrong...'];
    if($this->request->isPost()){
      $id = $this->request->get('id');
      $media = Media::findById($id);
      if($media){
        $type = $media->type;
        MediaImages::deleteImages($media->id,true);
        MediaAudio::deleteAudio($media->id,true);
        $media->delete();

        $resp = ['success' => true, 'msg' => $type.' deleted.' ,'model_id' => $id];
      }
    }
    $this->jsonResponse($resp);
  }

  public function editNameAction(){
    if($this->request->isPost()){
      $resp = ['success'=>false,'errors'=>'Error Occured.'];
      $stack_id = $this->request->get('stack_id');
      $stack = MediaStack::findByIdAndUserId($stack_id, $this->currentUser->id);
      $stack->name = $this->request->get('nameStack');
      if($stack->save()){
        $resp = ['success'=>true, 'stack'=>$stack->data(),'errors'=>''];
       } else {
        $resp = ['success'=>false,'errors'=>$stack->getErrorMessages()];
      }

      $this->jsonResponse($resp);
    }
  }

  public function editPriceAction(){
    if($this->request->isPost()){
      $resp = ['success'=>false,'errors'=>'Error Occured.'];
      $stack_id = $this->request->get('stack_id');
      $stack = MediaStack::findByIdAndUserId($stack_id, $this->currentUser->id);
      if($stack){
        $stack->price = $this->request->get('priceStack');
        if($stack->save()){
          $resp = ['success'=>true, 'stack'=>$stack->data(),'errors'=>''];
         } else {
          $resp = ['success'=>false,'errors'=>$stack->getErrorMessages()];
        }
      }
      $this->jsonResponse($resp);
    }
  }

  public function editShippingAction(){
    if($this->request->isPost()){
      $resp = ['success'=>false,'errors'=>'Error Occured.'];
      $stack_id = $this->request->get('stack_id');
      $stack = MediaStack::findByIdAndUserId($stack_id, $this->currentUser->id);
      if($stack){
        $stack->shipping = $this->request->get('shippingStack');
        if($stack->save()){
          $resp = ['success'=>true, 'stack'=>$stack->data(),'errors'=>''];
         } else {
          $resp = ['success'=>false,'errors'=>$stack->getErrorMessages()];
        }
      }
      $this->jsonResponse($resp);
    }
  }

  public function editStackImageAction(){
    if($this->request->isPost()){
        $resp = ['success'=>false];
        $formData = $this->request->get();
        $stack_id = $formData['stack_id'];
        $file = $_FILES['imageStack'];
        $resp['file'] = $file;
        $currentImage = MediaStackImages::findByAlbumId($stack_id);
        if($file['tmp_name'] != ''){
          // //validate file
          $imageUpload = new UploadOneImage($file);
          $imageUpload->runValidation();
          $imageErrors = $imageUpload->validates();
          if(is_array($imageErrors)){
            $msg = "";
            foreach($imageErrors as $name => $message){
              $msg .= $message . " ";
            }
            $resp = ['success'=>false, 'errors' => $msg];
          } else {
            $tmp = $file['tmp_name'];
            move_uploaded_file($tmp,ROOT.DS.$currentImage->url);
            $resp = ['success'=>true, 'file' => $currentImage, 'stack_id'=>$stack_id, 'proot'=>PROOT];
          }
        }
        else{
          $resp = ['success'=>false, 'errors'=>"Must Include File."];
        }
      }
      $this->jsonResponse($resp);
  }

    public function editBodyAction(){
      if($this->request->isPost()){
        $resp = ['success'=>false];
        $stack_id = $this->request->get('stack_id');
        $stack = MediaStack::findByIdAndUserId($stack_id, $this->currentUser->id);
        if($stack){
          $stack->body = $this->request->get('bodyStack');
          if($stack->save()){
            $resp = ['success'=>true, 'stack'=>$stack->data(), 'echo'=>html_entity_decode($stack->body)];
           } else {
            $resp = ['success'=>false,'errors'=>$stack->getErrorMessages()];
          }
        }
        $this->jsonResponse($resp);
      }
    }

    public function getTrackByIdAction(){
    if($this->request->isPost()){
      $id = (int)$this->request->get('id');
      $media = Media::findByIdAndUserId($id,$this->currentUser->id);
      $mediaImages = MediaImages::findByMediaId($id);
      $mediaAudio = MediaAudio::findByMediaId($id);
      $resp = ['success'=>false];
      if($media){
        $resp['success'] = true;
        $resp['media'] = $media->data();
        $resp['mediaImages'] = $mediaImages->data();
        $resp['mediaAudio'] = $mediaAudio->data();
        $resp['proot'] = PROOT;
      }
      $this->jsonResponse($resp);
    }
  }

  public function editTrackLabelAction(){
    if($this->request->isPost()){
      $resp = ['success'=>false];
      $media_id = $this->request->get('media_id_label');
      $media = Media::findByIdAndUserId($media_id, $this->currentUser->id);
      if($media){
        $media->label = $this->request->get('labelMedia');
        if($media->save()){
          $resp = ['success'=>true, 'media'=>$media->data()];
         } else {
          $resp = ['success'=>false,'errors'=>$media->getErrorMessages()];
        }
      }
      $this->jsonResponse($resp);
    }
  }

  public function editTrackNameAction(){
    if($this->request->isPost()){
      $resp = ['success'=>false];
      $media_id = $this->request->get('media_id_name');
      $media = Media::findByIdAndUserId($media_id, $this->currentUser->id);
      if($media){
        $media->name = $this->request->get('nameMedia');
        if($media->save()){
          $resp = ['success'=>true, 'media'=>$media->data()];
         } else {
          $resp = ['success'=>false,'errors'=>$media->getErrorMessages()];
        }
      }
      $this->jsonResponse($resp);
    }
  }

  public function editTrackImageAction(){
    if($this->request->isPost()){
        $resp = ['success'=>false];
        $formData = $this->request->get();
        $media_id = $formData['media_id'];
        $file = $_FILES['imageMedia'];
        $resp['file'] = $file;
        $currentImage = MediaImages::findByMediaId($media_id);
        if($file['tmp_name'] != ''){
          // //validate file
          $imageUpload = new UploadOneImage($file);
          $imageUpload->runValidation();
          $imageErrors = $imageUpload->validates();
          if(is_array($imageErrors)){
            $msg = "";
            foreach($imageErrors as $name => $message){
              $msg .= $message . " ";
            }
            $resp = ['success'=>false, 'errors' => $msg];
          } else {
              $tmp = $file['tmp_name'];
              move_uploaded_file($tmp,ROOT.DS.$currentImage->url);
              $resp = ['success'=>true, 'file' => $currentImage, 'media_id'=>$media_id, 'proot'=>PROOT];
            }
        }
        else{
          $resp = ['success'=>false, 'errors'=>"Must Include File."];
        }
      }
      $this->jsonResponse($resp);
  }



  public function editTrackAudioAction(){
    if($this->request->isPost()){
        $resp = ['success'=>false];
        $formData = $this->request->get();
        $media_id = $formData['media_id'];
        $file = $_FILES['audioMedia'];
        $resp['file'] = $file;
        $currentAudio = MediaAudio::findByMediaId($media_id);
        if($file['tmp_name'] != ''){
          // //validate file
          $audioUpload = new UploadOneAudio($file);
          $audioUpload->runValidation();
          $audioErrors = $audioUpload->validates();
          if(is_array($audioErrors)){
            $msg = "";
            foreach($audioErrors as $name => $message){
              $msg .= $message . " ";
            }
            $resp = ['success'=>false, 'errors' => $msg];
          } else {
              $tmp = $file['tmp_name'];
              move_uploaded_file($tmp,ROOT.DS.$currentAudio->url);
              $resp = ['success'=>true, 'file' => $currentAudio, 'media_id'=>$media_id, 'proot'=>PROOT];
            }
        }
        else{
          $resp = ['success'=>false, 'errors'=>"Must Include File."];
        }
      }
      $this->jsonResponse($resp);
  }

  public function deleteStackAction(){
    $resp = ['success'=>false,'msg'=>'Something went wrong...'];
    if($this->request->isPost()){
      $id = $this->request->get('id');

      $album = MediaStack::findByIdAndUserId($id, $this->currentUser->id);
      $tracks = Media::findByAlbumId($id);

      if($album && $tracks){
        foreach($tracks as $track){
          MediaImages::deleteImages($track->id,true);
          MediaAudio::deleteAudio($track->id,true);
          $track->delete();
        }
        MediaStackImages::deleteImages($id,true);
        $type = $album->type;
        $album->delete();
        $resp = ['success' => true, 'msg' => $type.' deleted.','model_id' => $id];
      }
    }
    $this->jsonResponse($resp);

  }

}
