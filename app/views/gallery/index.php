<?php use Core\FH;?>
<?php $this->setSiteTitle('Storytime Gallery'); ?>
<?php $this->start('body'); ?>

  <span class="br-row"></span>
  <h1 class="kanit-bold heading">Gallery</h1>
  <span class="br-head"></span>
  <div class="grid-4">
    <?php foreach($this->gallery as $image): ?>

      <a class="menu-item" href="<?=PROOT?>gallery/details/<?=$image->id?>">
        <div class="card-image-block">
          <img class="card-image" src="<?=PROOT.$image->url?>" alt="<?=$image->name?>">
        </div>
        <div class="title-block">
          <h1 class="kanit-bold body-heading"><?=$image->name?></h1>
          <h1 class="roboto-light body">uploaded by</h1>
          <h1 class="kanit-bold body-heading"><?=$image->aName?></h1>
        </div>
      </a>

    <?php endforeach; ?>

  </div>
  <span class="br-row"></span>
<?php $this->end(); ?>
