<?php $this->setSiteTitle('Thank You!');?>

<?php $this->start('body')?>
<div class="grid-width">
  <span class="br-row"></span>
  <div class="row">
    <div class="col-md-8 offset-md-2 text-center">
      <h1 class="dark kanit-bold sub-heading">Thank You!</h2>
      <p class="dark roboto-light body-sub-heading">Your purchase of $<?=number_format($this->tx->amount,2)?> was successful.</p>
      <p class="dark roboto-light body-sub-heading">Your purchase will be shipped to the following address:</p>
      <p class="dark kanit-medium body-heading">
        <?=$this->tx->name?> <br />
        <?= $this->tx->shipping_address1?> <br />
        <?php if($this->tx->shipping_address2):?>
          <?=$this->tx->shipping_address2?><br />
        <?php endif;?>
        <?=$this->tx->shipping_city?>, <?=$this->tx->shipping_state?> <?=$this->tx->shipping_zip?>
      </p>
      <a href="<?=PROOT?>" class="btn btn-lg btn-chkout dark kanit-medium w-50 text-center">Continue</a>
    </div>
  </div>
  <span class="br-qspacer"></span>

</div>

<?php $this->end()?>
