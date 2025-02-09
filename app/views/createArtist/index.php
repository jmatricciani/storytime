<?php
use Core\FH;
?>
<?php $this->start('head'); ?>
<?php $this->end(); ?>
<?php $this->start('body'); ?>
<div class="row align-items-center justify-content-center">
  <div class="col-md-6 bg-light p-3">
    <h3 class="text-center">Create Artist Profile!</h3><hr>
    <form class="form" action="" method="post">
      <?= FH::csrfInput() ?>
      <?= FH::inputBlock('text','First Name','fname',$this->newUser->fname,['class'=>'form-control input-sm'],['class'=>'form-group'],$this->displayErrors) ?>
      <?= FH::inputBlock('text','Last Name','lname',$this->newUser->lname,['class'=>'form-control input-sm'],['class'=>'form-group'],$this->displayErrors) ?>
      <?= FH::inputBlock('text','Artist Name','artist_name',$this->newUser->artist_name,['class'=>'form-control input-sm'],['class'=>'form-group'],$this->displayErrors) ?>
      <?= FH::inputBlock('text','Email','email',$this->newUser->email,['class'=>'form-control input-sm'],['class'=>'form-group'],$this->displayErrors) ?>
      <?= FH::inputBlock('text','Username','username',$this->newUser->username,['class'=>'form-control input-sm'],['class'=>'form-group'],$this->displayErrors) ?>
      <?= FH::inputBlock('password','Password','password',$this->newUser->password,['class'=>'form-control input-sm'],['class'=>'form-group'],$this->displayErrors) ?>
      <?= FH::inputBlock('password','Confirm Password','confirm',$this->newUser->confirm,['class'=>'form-control input-sm'],['class'=>'form-group'],$this->displayErrors) ?>
      <div class="d-flex justify-content-end">
        <div class="text-dk flex-grow-1">Already have an account? <a href="<?=PROOT?>register/login">Log In</a></div>
        <?= FH::submitTag('Register',['class'=>'btn btn-primary']) ?>
      </div>
    </form>
  </div>
</div>
<?php $this->end(); ?>
