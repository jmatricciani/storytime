<?php use Core\FH; ?>
<?php $imageName = ($this->image->name)? $this->image->name: 'Image' ;
$this->setSiteTitle("Edit ".$imageName);?>
<?php $this->start('head') ?>
  <!-- tinymce  -->
  <script src='<?=PROOT?>vendor/tinymce/tinymce/tinymce.min.js'></script>
  <script>
    tinymce.init({
      selector: '#body',
      branding: false
    });
  </script>
  <!-- CSS -->
  <style>
    .image-wrapper {
      width:400px;
      grid-column: auto;
      height: 600px;
      padding: 5px;
      display: grid;
      grid-template-rows: 1fr 1fr 1fr;
      /* border:1px solid #666; */
      border-radius: 1px;
      box-shadow: 2px 2px 3px rgba(0,0,0,0.6);
      margin: 22px;
    }
    .image{
      display: block;
      margin: 0 auto;
      max-height: 400px;
      max-width: 280px;
    }
    .image-body{
      justify-content: center;
      align-self: flex-end;
      height: auto;
    }
    .image-title{
      margin: 0 auto;
      padding: 2px;
    }
    .image-btn{
      width:60%;
      display: block;
      margin: 0 auto;
    }
  </style>
<?php $this->end() ?>
<?php $this->start('body') ?>
<form action="" method="POST" enctype="multipart/form-data">
  <?= FH::csrfInput();?>
  <div class="row align-items-center justify-content-center">
    <div class="image-wrapper" id="image_<?=$this->image->id?>">
      <?= FH::inputBlock('text','Name','name',$this->image->name,['class'=>'form-control input-sm'],['class'=>'form-group col-md-10 image-title'],$this->displayErrors) ?>
      <img src="<?= PROOT .$this->image->url?>" class="image" alt="<?=$this->image->name?>">
      <?= FH::textareaBlock('Body','body',$this->image->body,['class'=>'form-control image-body','rows'=>'6'],['class'=>'form-group col-md-12 '],$this->displayErrors) ?>
    </div>
  </div>
  <div class="row">
    <div class="col-md-12 text-center">
      <a href="<?=PROOT?>admingallery" class="btn btn-large btn-secondary">Cancel</a>
      <?= FH::submitTag('Save',['class'=>'btn btn-large btn-primary'],['class'=>'text-center col-md-12']); ?>
    </div>
  </div>
</form>

<?php $this->end() ?>
