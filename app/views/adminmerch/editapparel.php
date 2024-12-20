<?php
  use Core\{Router,FH,H};
  use App\Lib\Elements\{BackEnd};

  $apparel_options = Router::getOptions('options')['apparel'];

  $womens_options = $apparel_options['womens'];
  $mens_options = $apparel_options['mens'];
?>
<?php $this->start('head'); ?>
  <script src='<?=PROOT?>vendor/tinymce/tinymce/tinymce.min.js'></script>
  <script>
    tinymce.init({
      selector: '#body',
      branding: false
    });
  </script>
<?php $this->end(); ?>
<?php $this->start('body'); ?>
<?php $this->setSiteTitle('Edit '.$this->product->name); ?>
<h1 class="text-center">Edit Apparel</h1>
<form action="" method="POST" enctype="multipart/form-data">
  <input type="hidden" id="images_sorted" name="images_sorted" value="" />
  <?= FH::csrfInput();?>
  <?= FH::displayErrors($this->displayErrors)?>
  <div class="row">
    <?= FH::inputBlock('text','Name','name',$this->product->name,['class'=>'form-control input-sm'],['class'=>'form-group col-md-6'],$this->displayErrors) ?>
    <?= FH::inputBlock('text','Price','price',$this->product->price,['class'=>'form-control input-sm'],['class'=>'form-group col-md-2'],$this->displayErrors) ?>
    <?= FH::inputBlock('text','Shipping','shipping',$this->product->shipping,['class'=>'form-control input-sm'],['class'=>'form-group col-md-2'],$this->displayErrors) ?>
  </div>
  <div class="stock">
    <div style="display: none;" id="womenArray">
      <?php
      $counter=0;
        echo '<span id="womenArray.count">'.sizeof($womens_options).'</span>';
        foreach($womens_options as $w_option){
            echo '<span id="womenArray.'.$counter.'">'.$w_option.'</span>';
            $counter++;
        }
      ?>
    </div>
    <div style="display: none;" id="menArray">
      <?php
      $counter=0;
        echo '<span id="menArray.count">'.sizeof($mens_options).'</span>';
        foreach($mens_options as $m_option){
            echo '<span id="menArray.'.$counter.'">'.$m_option.'</span>';
            $counter++;
        }
      ?>
    </div>
    <div class="row">
      <h3 class="col-12 text-center">Womens</h3>
    </div>

    <div class="row">



      <?php foreach($womens_options as $option):?>
      <?= BackEnd::displayApparelOptions($option,$this->stocks,$this); ?>

    <?php endforeach; ?>

    </div>
    <div class="row">
      <h3 class="col-12 text-center">Mens</h3>
    </div>

    <div class="row">

      <?php foreach($mens_options as $option):?>
      <?= BackEnd::displayApparelOptions($option,$this->stocks,$this); ?>
    <?php endforeach; ?>

    </div>

    <div class="row">
      <?= FH::disabledInputBlock('text','Total Stock','total_stock',$this->total_stock,['class'=>'form-control input-sm text-center'],['class'=>'form-group col-md-2 text-center block-center'],$this->displayErrors) ?>
      <!-- display total for double checking big orders -->
    </div>
  </div>



  <div class="row">
    <?= FH::textareaBlock('Body','body',$this->product->body,['class'=>'form-control','rows'=>'6'],['class'=>'form-group col-md-12'],$this->displayErrors) ?>
  </div>

  <div class="row">
      <?php $this->partial('adminproducts','editImages')?>
  </div>

  <div class="row">
    <?= FH::inputBlockAccept('image',"Upload Apparel Images:",'productImages[]','',['class'=>'form-control','multiple'=>'multiple'],['class'=>'form-group col-md-6'],$this->displayErrors) ?>
  </div>
  <div class="row">
    <?= FH::submitBlock('Save',['class'=>'btn btn-large btn-primary'],['class'=>'text-right col-md-12']); ?>
  </div>
</form>

<script type="text/javascript">


  $( document ).ready(function() {
    update_total();
  });

  function update_total(){

    var total = 0;

    var womenArray = new Array();

    for(i = 0; i < document.getElementById('womenArray.count').innerHTML; i++) {
      var option = document.getElementById('womenArray.'+i).innerHTML;
      var element = document.getElementById(option);
      var value = parseInt(element.value);
      if(Number.isInteger(value)){
        //console.log(element.value);
        total+= +value ;
      }
    }

    var menArray = new Array();

    for(i = 0; i < document.getElementById('menArray.count').innerHTML; i++) {
      var option = document.getElementById('menArray.'+i).innerHTML;
      var element = document.getElementById(option);
      var value = parseInt(element.value);
      if(Number.isInteger(value)){
        //console.log(element.value);
        total+= +value ;
      }
    }

    document.getElementById('total_stock').value = total;

  }

</script>

<?php $this->end(); ?>
