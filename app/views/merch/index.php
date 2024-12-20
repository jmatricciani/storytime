<?php use Core\FH;      ?>
<?php $this->setSiteTitle('Storytime Merch'); ?>
<?php $this->start('body'); ?>

  <span class="br-row"></span>
  <h1 class="kanit-bold heading">Merch</h1>
  <span class="br-head"></span>
  <div class="grid-4">

    <?php foreach($this->products as $product):?>
      <a class="menu-item" href="<?=PROOT?>merch/details/<?=$product->id?>">
        <div class="card-image-block">
          <img class="card-image" src="<?=PROOT.$product->url?>" alt="<?=$product->name?>">
        </div>
        <div class="title-block">
          <h1 class="kanit-bold body-heading"><?=$product->name?></h1>
          <h1 class="kanit-bold body-heading teal">$<?=$product->price?></h1>
          <h1 class="roboto-light body">sold by</h1>
          <h1 class="kanit-bold body-heading"><?=$product->aName?></h1>
        </div>
      </a>
    <?php endforeach; ?>
  </div>
  <span class="br-row"></span>
<?php $this->end(); ?>
