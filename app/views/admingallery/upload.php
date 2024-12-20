<?php use Core\{FH}; ?>
<?php $this->setSiteTitle("Add Image") ?>
<?php $this->start('head') ?>
  <script src='<?=PROOT?>vendor/tinymce/tinymce/tinymce.min.js'></script>
  <script>
    tinymce.init({
      selector: '#body',
      branding: false
    });
  </script>
<?php $this->end() ?>

<?php $this->start('body') ?>
<div class="row align-items-center justify-content-center">
  <div class="col-md-8 p-3">
    <h1 class="text-center">Add New Image</h1>
    <form action="<?=$this->formAction?>" method="POST" enctype="multipart/form-data">
      <?= FH::csrfInput();?>
      <?= FH::displayErrors($this->displayErrors)?>
      <div class="row">
        <?= FH::inputBlock('text','Name','name',$this->image->name,['class'=>'form-control input-sm'],['class'=>'form-group col-md-6'],$this->displayErrors) ?>
      </div>

      <div class="row">
        <?= FH::textareaBlock('Body','body',$this->image->body,['class'=>'form-control','rows'=>'6'],['class'=>'form-group col-md-12'],$this->displayErrors) ?>
      </div>


      <div class="row">
        <?= FH::inputBlock('file',"Upload Gallery Image:",'galleryImage[]','',['class'=>'form-control','multiple'=>'multiple'],['class'=>'form-group col-md-6'],$this->displayErrors) ?>
      </div>
      <div class="row">
        <?= FH::submitBlock('Save',['class'=>'btn btn-large btn-primary'],['class'=>'text-right col-md-12']); ?>
      </div>
    </form>

  </div>
</div>
<?php $this->end() ?>
