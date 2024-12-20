<?php use App\Lib\Utilities\{TextFormat}; ?>
<?php use Core\{H}; ?>
<?php $this->setSiteTitle("Shopping Cart"); ?>

<?php $this->start('body')?>
<div class="grid-width">
  <span class="br-row"></span>
  <h2>Shopping Cart (<?=$this->itemCount?> item<?=($this->itemCount == 1)?"" : "s"?>)</h2>
  <hr />
  <div class="row">
    <?php if(sizeof($this->items) == 0): ?>
      <div class="col col-md-8 offset-md-2 text-center">
        <h3 class="kanit-medium sub-heading">Your shopping cart is empty!</h3>
        <a href="<?=PROOT?>" class="btn btn-lg btn-info">Continue Shopping</a>
      </div>
      <span class="br-spacer"></span>
  <?php else: ?>
      <?php if(sizeof($this->items) == 1): ?>
        <span class="br-spacer"></span>
      <?php endif; ?>
    <div class="col col-md-8">
      <?php foreach($this->items as $item):
        $shipping = ($item->shipping == 0)? "Free Shipping" : "Shipping: $" . $item->shipping;
        $shipping = ($item->type == 'album')? "Digital Download" : $shipping;
        if($item->option){
          $option = explode('_', $item->option);
          $option = ucfirst($option[0]).' '.strtoupper($option[1]);
        } else {
          $option = '';
        }

        $link_name = TextFormat::convertNameToUrl($item->name);
        switch($item->type){
          case 'tape':
            $link = PROOT.'music/tape/'.$link_name;
          break;
          case 'album':
            $link = PROOT.'music/album/'.$link_name;
          break;
          case 'merch':
            $link = PROOT.'merch/details/'.$item->item_id;
          break;
          default:
            $link = PROOT.'merch/details/'.$item->item_id;
          break;
        }

        ?>
        <div class="shopping-cart-item">
          <div class="shopping-cart-item-img">
            <img src="<?=PROOT. $item->url?>" alt="<?=$item->name?>">
          </div>
          <div class="shopping-cart-item-name">
            <a class="kanit-medium" href="<?=$link?>" title="<?=$item->name?>">
              <?=$item->name?>
            </a>
            <p class="kanit-medium dark-purple"><?=$option?></p>
          </div>


            <div class="shopping-cart-item-qty">
              <?php if($item->type != 'album'): ?>
              <label>Qty</label>
              <?php if($item->qty > 1): ?>
                <a href="<?=PROOT?>cart/changeQty/down/<?=$item->id?>"><i class="fas fa-chevron-down dark"></i></a>
              <?php endif;?>
              <!-- Create an onchange script to make this inputable -->
              <input class="form-control form-control-sm" value="<?=$item->qty?>" onchange="updateQuantity(this,'<?=PROOT?>cart/updateItemQuantity/<?=$item->id?>/<?=$item->stock?>/')"/>
              <?php if($item->qty < $item->stock): ?>
                <a href="<?=PROOT?>cart/changeQty/up/<?=$item->id?>"><i class="fas fa-chevron-up dark"></i></a>
            <?php endif; ?>
            <?php endif; ?>
            </div>

          <div class="shopping-cart-item-price">
            <div class="kanit-bold body dark">$<?=$item->price?></div>
            <div class="roboto-light dark"><?=$shipping?></div>
            <div class="remove-item kanit-bold dark-purple" onclick="confirmRemoveItem('<?=PROOT?>cart/removeItem/<?=$item->id?>')">
              <i class="fas fa-trash-alt"></i> Remove
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>

    <aside class="col col-md-4 ">
      <div class="shopping-cart-summary">
        <a href="<?=PROOT?>cart/checkout/<?=$this->cartId?>" class="btn btn-lg btn-chkout btn-block dark kanit-medium">Proceed With Checkout</a>
        <span class="br-light-controls"></span>
        <div class="kanit-medium cart-line-item body">
          <div>Item<?=($this->itemCount == 1)?"" : "s"?> (<?=$this->itemCount?>)</div>
          <div>$<?=$this->subTotal?></div>
        </div>
        <div class="kanit-medium cart-line-item body">
          <div>Shipping</div>
          <div>$<?=$this->shippingTotal?></div>
        </div>
        <hr />
        <div class="kanit-bold cart-line-item body-heading">
          <div>Total:</div>
          <div>$<?=$this->grandTotal?></div>
        </div>
      </div>
    </aside>
  <?php endif; ?>

  </div>
  <span class="br-row"></span>


</div>

<script>
  function confirmRemoveItem(href){
    if(confirm("Are you sure?")){
      window.location.href = href;
    }
    return false;
  }

  function updateQuantity(ele,href){
    // console.log(ele.value);
    href = href + ele.value;
    console.log(href);
    window.location.href = href;
  }
</script>
<?php $this->end()?>
