<?php use Core\{H,FH};
      use App\Models\Users;
?>
<?php $this->setSiteTitle('Storytime Music'); ?>
<?php $this->start('body'); ?>

  <span class="br-row"></span>
  <h1 class="kanit-bold heading">Music</h1>
  <span class="br-head"></span>
  <div class="grid-4">
    <?php
      $i=0;
      foreach($this->media as $media):
        $name_link = strtolower($media->name);
        $name_link =  str_replace([' ','.'], '-', $name_link);
        $name_link =  str_replace([',','?','!','/'], '', $name_link);
      ?>
      <a class="menu-item" href="<?=PROOT?>music/<?=$media->type?>/<?=$name_link?>">
        <div class="card-image-block">
          <img class="card-image" src="<?=PROOT.$media->url?>" alt="<?=$media->name?>">
        </div>
        <div class="title-block">
          <h1 class="kanit-bold body-heading"><?=$media->name?></h1>
          <h1 class="roboto-light body-sub-heading"><?=ucfirst($media->type)?></h1>
          <h1 class="roboto-light body">by</h1>
          <h1 class="kanit-bold body-heading"><?=$media->aName?></h1>
        </div>
      </a>

    <?php
      $i++;
      if($i==4)
        echo '</div><span class="br-row"></span><div class="grid-4">';
      endforeach;
      ?>
  </div>
  <span class="br-row"></span>
  <div class="grid-width">
    <img class="hero" src="<?=PROOT?>images/optimized/MenuImages/Music.jpg" alt="Music Menu Image">
  </div>
  <span class="br-row"></span>
<?php $this->end(); ?>
