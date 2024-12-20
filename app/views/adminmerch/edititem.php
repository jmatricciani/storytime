<?php use Core\FH;?>
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
<?php $this->setSiteTitle('Edit '.$this->product->name); ?>
<h1 class="text-center">Edit Item</h1>
<form action="" method="POST" enctype="multipart/form-data">
  <input type="hidden" id="images_sorted" name="images_sorted" value="" />
  <?= FH::csrfInput();?>
  <?= FH::displayErrors($this->displayErrors)?>
  <div class="row">
    <?= FH::inputBlock('text','Name','name',$this->product->name,['class'=>'form-control input-sm'],['class'=>'form-group col-md-6'],$this->displayErrors) ?>
    <?= FH::inputBlock('text','Price','price',$this->product->price,['class'=>'form-control input-sm'],['class'=>'form-group col-md-2'],$this->displayErrors) ?>
    <?= FH::inputBlock('text','Shipping','shipping',$this->product->shipping,['class'=>'form-control input-sm'],['class'=>'form-group col-md-2'],$this->displayErrors) ?>
    <?= FH::inputBlock('text','Stock','stock',$this->stock,['class'=>'form-control input-sm'],['class'=>'form-group col-md-2'],$this->displayErrors) ?>
  </div>

  <div class="row">
    <?= FH::textareaBlock('Body','body',$this->product->body,['class'=>'form-control','rows'=>'6'],['class'=>'form-group col-md-12'],$this->displayErrors) ?>
  </div>

  <div class="row">
      <?php $this->partial('adminproducts','editImages')?>
  </div>

  <div class="row">
    <?= FH::inputBlockAccept('image',"Upload Item Images:",'productImages[]','',['class'=>'form-control','multiple'=>'multiple'],['class'=>'form-group col-md-6'],$this->displayErrors) ?>
  </div>
  <div class="row">
    <?= FH::submitBlock('Save',['class'=>'btn btn-large btn-primary'],['class'=>'text-right col-md-12']); ?>
  </div>
</form>

<?php $this->end(); ?>
