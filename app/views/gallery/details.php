<?php use App\Lib\Elements\FrontEnd; ?>
<?php $this->setSiteTitle($this->image->name); ?>
<?php $this->start('body') ?>

  <span class="br-row"></span>
  <h1 class="kanit-bold heading"><?=$this->image->name?></h1>
  <span class="br-head"></span>
  <div class="grid-2">
    <div class="image-block">
      <img class="full-image" src="<?=PROOT.$this->image->url?>" alt="<?=$this->image->name?>">
    </div>
    <div class="description-block">
      <h1 class="roboto-light body-heading">by</h1>
      <h1 class="kanit-bold heading"><?=$this->artist_name?></h1>
      <p class="roboto-light body"><?=html_entity_decode($this->image->body)?></p>
      <h1 class="roboto-light body">Uploaded:<br/><?=$this->image->created_at?></h1>
    </div>
  </div>
  <span class="br-row"></span>
  <?=FrontEnd::backToMenuButton('gallery'); ?>
  <span class="br-row"></span>
<?php $this->end() ?>
