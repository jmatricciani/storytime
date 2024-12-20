<?php use App\Lib\Elements\FrontEnd;
      use App\Models\Users;
      use Core\{FH,H};
?>
<?php $this->setSiteTitle($this->product->name); ?>
<?php $this->start('body');?>
  <span class="br-row"></span>
  <div class="grid-2">
    <div class="product-details-slideshow image-block">

      <?php if(sizeof($this->images) === 1 ): ?>
        <div class="image-block">
          <img src="<?= PROOT.$this->images[0]->url?>" class="full-image" alt="<?=$this->product->name?>">
        </div>
      <?php elseif(sizeof($this->images) === 0): ?>
          <div class="image-block">
            <img src="<?= PROOT?>images/optimized/stc-stick.png" class="full-image" alt="Default Story Time Image">
          </div>
      <?php else: ?>
      <!-- slideshow -->
      <div id="carouselIndicators" class="carousel slide w-100 d-flex" data-ride="carousel">
        <ol class="carousel-indicators">
          <?php for($i = 0; $i < sizeof($this->images); $i++):
            $active = ($i == 0)? "active" : "";
            ?>
            <li data-target="#carouselIndicators" data-slide-to="<?=$i?>" class="<?=$active?>" style="background-color:#101820;"></li>
          <?php endfor;?>
        </ol>
        <div class="carousel-inner d-flex">
          <?php for($i = 0; $i < sizeof($this->images); $i++):
            $active = ($i == 0)? " active" : "";
            ?>
            <div class="carousel-item<?=$active?>">
              <div class="image-block">
                <img src="<?= PROOT.$this->images[$i]->url?>" class="full-image" alt="<?=$this->product->name?>">
              </div>
            </div>
          <?php endfor;?>
        </div>
        <a class="carousel-control-prev" href="#carouselIndicators" role="button" data-slide="prev">
          <span class="carousel-control-prev-icon" aria-hidden="true"><i class="fas fa-chevron-left"></i></span>
          <span class="sr-only">Previous</span>
        </a>
        <a class="carousel-control-next" href="#carouselIndicators" role="button" data-slide="next">
          <span class="carousel-control-next-icon" aria-hidden="true"><i class="fas fa-chevron-right"></i></span>
          <span class="sr-only">Next</span>
        </a>
      </div>
      <?php endif; ?>
    </div>
    <div class="description-block">
      <h1 class="kanit-medium sub-heading"><?=$this->product->name?></h1>
      <h1 class="roboto-light body-heading">sold by</h1>
      <h1 class="kanit-bold heading"><?=$this->artist_name?></h1>
      <p class="roboto-light body">
        <?=html_entity_decode($this->product->body)?>
      </p>


    </div>
  </div>
  <form action="<?=PROOT?>cart/addToCart" method="post">
    <?=FH::csrfInput()?>
    <span class="br-row"></span>
    <?php if(!FrontEnd::isAvailable($this->inventories)): ?>
      <h1 class="kanit-bold no-stock-display text-center">Sold Out</h1>
    <?php elseif($this->inventories[0]->option != 0): ?>
      <h1 class="size-option-label text-center">Size Options</h1>
      <div class="grid-2 size-options">
        <?=FrontEnd::sizeOptions($this->inventories);?>
      </div>
    <?php else: ?>
      <h1 class="kanit-bold stock-display text-center"><?=$this->inventories[0]->stock?> in stock</h1>
      <input type="hidden" name="<?=$this->inventories[0]->id?>" value="on">
    <?php endif; ?>
    <span class="br-row"></span>

    <?=FrontEnd::addToCart($this->product->price,$this->product->shipping);?>
  </form>
  <span class="br-row"></span>
  <?=FrontEnd::backToMenuButton('merch'); ?>
  <span class="br-row"></span>

  <?php if($this->inventories[0]->option != 0): ?>
      <script type="text/javascript">
        button = document.querySelector('button[id="addToCart"]');
        button.disabled = true;
      </script>
  <?php endif; ?>


  <script type="text/javascript">
    function updateView(option,stock){
      uncheckAll();
      option.checked = true;

      // updates html with stock info
      label = document.getElementsByClassName('size-option-label')[0];
      label.className = "size-option-label stock-display kanit-bold text-center";
      label.innerHTML = stock+' in stock';
      enableAdd();

    }

    function uncheckAll(checked = false) {
      const cbs = document.querySelectorAll('input[id="option"]');
      cbs.forEach((cb) => {
          cb.checked = checked;
      });
    }

    function disableAdd(){
      button = document.querySelector('button[id="addToCart"]');
      button.disabled = true;
    }

    function enableAdd(){
      button = document.querySelector('button[id="addToCart"]');
      button.disabled = false;
    }

    // document.addEventListener('DOMContentLoaded', function() {
    //   disableAdd();
    // }, false);
  </script>
<?php $this->end() ?>
