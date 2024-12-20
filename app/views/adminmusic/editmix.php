<?php $this->setSiteTitle('Edit '.$this->media->name); ?>

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


  <h1 class="text-center">Edit <?=$this->media->name?></h1>
  <form action="" method="POST" enctype="multipart/form-data">
    <?= FH::csrfInput();?>

    <?= FH::displayErrors($this->displayErrors)?>
    <div class="row">
      <?= FH::inputBlock('text','Name','name',$this->media->name,['class'=>'form-control input-sm'],['class'=>'form-group col-md-6'],$this->displayErrors) ?>
    </div>

    <div class="row">
      <?= FH::textareaBlock('Body','body',$this->media->body,['class'=>'form-control','rows'=>'6'],['class'=>'form-group col-md-12'],$this->displayErrors) ?>
    </div>

    <div class="row">
      <h3 class="col-6">Current Image</h3>
      <h3 class="col-6">Current Audio</h3>
    </div>
    <div class="row">
      <img class="w-25 col-6" src="<?=PROOT.$this->image->url?>" alt="<?=$this->media->name?> image">
      <audio class="col-6 align-self-center" controls src="<?=PROOT.$this->audio->url?>"
          Your browser does not support the
          <code>audio</code> element.
        </audio>
    </div>

    <div class="row">
      <?= FH::inputBlockAccept('image',"Change Mix Image:",'mediaImage','',['class'=>'form-control'],['class'=>'form-group col-md-6'],$this->displayErrors) ?>
      <?= FH::inputBlockAccept('audio',"Change Mix Audio:",'mediaAudio','',['class'=>'form-control'],['class'=>'form-group col-md-6'],$this->displayErrors) ?>
    </div>

    <div class="row">
      <?= FH::submitBlock('Save',['class'=>'btn btn-large btn-primary'],['class'=>'text-right col-md-12']); ?>
    </div>
  </form>

<?php $this->end(); ?>
