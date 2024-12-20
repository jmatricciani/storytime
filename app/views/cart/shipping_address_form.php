<?php use Core\FH;?>
<?php $this->setSiteTitle("Checkout - Shipping Info"); ?>

<?php $this->start('body')?>
<div class="grid-width">
  <span class="br-row"></span>
  <div class="row">
    <div class="col-md-8">
      <div class="shipping-info-wrapper">
        <h3 class="kanit-medium dark form-heading">Shipping Information:</h3>
        <span class="br-form"></span>
        <form action="<?=PROOT?>cart/checkout/<?=$this->cartId?>" method="post">
          <?=FH::csrfInput()?>
          <div class="row">
            <input type="hidden" name="step" value="1" />
            <?=FH::inputBlock('input','Name','name',$this->tx->name,['class'=>'form-control form-control-sm'],['class'=>'form-group col-md-12'],$this->formErrors)?>
            <?=FH::inputBlock('input','Shipping Address','shipping_address1',$this->tx->shipping_address1,['class'=>'form-control form-control-sm'],['class'=>'form-group col-md-12'],$this->formErrors)?>
            <?=FH::inputBlock('input','Shipping Address (cont.)','shipping_address2',$this->tx->shipping_address2,['class'=>'form-control form-control-sm'],['class'=>'form-group col-md-12'],$this->formErrors)?>
            <?=FH::inputBlock('input','City','shipping_city',$this->tx->shipping_city,['class'=>'form-control form-control-sm'],['class'=>'form-group col-md-6'],$this->formErrors)?>
            <?=FH::inputBlock('input','State','shipping_state',$this->tx->shipping_state,['class'=>'form-control form-control-sm'],['class'=>'form-group col-md-3'],$this->formErrors)?>
            <?=FH::inputBlock('input','Zip Code','shipping_zip',$this->tx->shipping_zip,['class'=>'form-control form-control-sm'],['class'=>'form-group col-md-3'],$this->formErrors)?>
          </div>
      </div>
        <span class="br-form"></span>
        <button class="btn btn-lg btn-chkout btn-block dark kanit-medium">Continue</button>
      </form>
    </div>

    <div class="col-md-4"><?php $this->partial('cart','product_preview')?></div>
  </div>
  <span class="br-qspacer"></span>
</div>

<?php $this->end() ?>
