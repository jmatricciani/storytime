<?php $this->setSiteTitle('Upload Track'); ?>

<?php
  use Core\FH;
?>
<?php $this->start('head') ?>
  <script src='<?=PROOT?>vendor/tinymce/tinymce/tinymce.min.js'></script>
  <script>
    tinymce.init({
      selector: '#body',
      branding: false
    });
  </script>
<?php $this->end() ?>

<?php $this->start('body'); ?>


  <h1 class="text-center">Upload Track</h1>
  <form action="<?=$this->formAction?>" method="POST" enctype="multipart/form-data">
    <?= FH::csrfInput();?>
    <?= FH::displayErrors($this->displayErrors)?>
    <div class="row">
      <?= FH::inputBlock('text','Name','name',$this->media->name,['class'=>'form-control input-sm'],['class'=>'form-group col-md-6'],$this->displayErrors) ?>
    </div>

    <div class="row">
      <?= FH::textareaBlock('Body','body',$this->media->body,['class'=>'form-control','rows'=>'6'],['class'=>'form-group col-md-12'],$this->displayErrors) ?>
    </div>

    <div class="row">
      <?= FH::inputBlockAccept('image',"Upload Track Image:",'mediaImage','',['class'=>'form-control'],['class'=>'form-group col-md-6'],$this->displayErrors) ?>
    </div>
    <div class="row">
      <?= FH::inputBlockAccept('audio',"Upload Track Audio:",'mediaAudio','',['class'=>'form-control'],['class'=>'form-group col-md-6'],$this->displayErrors) ?>
    </div>
    <div class="row">
      <?= FH::submitBlock('Save',['class'=>'btn btn-large btn-primary'],['class'=>'text-right col-md-12']); ?>
    </div>
  </form>

<?php $this->end(); ?>
