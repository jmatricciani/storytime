<?php
use Core\FH;
?>
<?php $this->setSiteTitle('Artist Login'); ?>


<?php $this->start('body'); ?>
<span class="br-row"></span>
<div class="row align-items-center justify-content-center w-100">
    <div class="col-md-6 bg-light p-3">
    <h3 class="text-center kanit-medium dark sub-heading">Log In</h3>
    <form class="form" action="<?=PROOT?>login" method="post">
      <?= FH::csrfInput() ?>
      <?= FH::displayErrors($this->displayErrors) ?>
      <?= FH::inputBlock('text','Username','username',$this->login->username,['class'=>'form-control'],['class'=>'form-group'],$this->displayErrors) ?>
      <?= FH::inputBlock('password','Password','password',$this->login->password,['class'=>'form-control'],['class'=>'form-group'],$this->displayErrors) ?>
      <?= FH::checkboxBlock('Remember Me','remember_me',$this->login->getRememberMeChecked(),[],['class'=>'form-group'],$this->displayErrors) ?>
      <div class="d-flex justify-content-end">
        <?= FH::submitTag('Login',['class'=>'btn btn-primary']) ?>
      </div>
    </form>
  </div>
</div>
<span class="br-qspacer"></span>
<?php $this->end(); ?>
