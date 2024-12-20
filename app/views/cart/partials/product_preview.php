<span class="br-form"></span>
<h3 class="kanit-medium small-form-heading">Purchase Summary</h3>
<?php foreach($this->items as $item):?>
  <div class="cart-preview-item">
    <div class="cart-preview-item-img"><img src="<?=PROOT . $item->url?>" alt="<?=$item->name?>" /></div>
    <div class="cart-preview-item-info">
      <p><?=$item->name?></p>
      <p><?= $item->qty?> @ $<?=$item->price?></p>
      <p>Shipping: $<?=$item->shipping?></p>
    </div>
  </div>
<?php endforeach; ?>

<div class="d-flex justify-content-between">
  <div class="kanit-medium body-heading">Total:</div>
  <div class="kanit-bold body-heading">$<?=number_format($this->grandTotal,2)?></div>
</div>
