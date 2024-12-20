<?php
  use Core\{H,FH};
?>
<?php $this->start('body')?>
<h1 class="text-center pb-5">Your Uploaded Music</h1>

<?php if($this->tracks): ?>
<h3 class="text-center pb-3">Tracks</h3>
<table class="table table-bordered table-hover table-striped table-sm">
  <thead>
    <th class="col-4">Name</th>
    <th class="col-4">Listen</th>
    <th class="col-4">Edit/Delete</th>
  </thead>
  <tbody>
    <?php foreach($this->tracks as $media):  ?>
      <tr data-id="<?=$media->id?>">
        <td><?=$media->name ?></td>
        <td><audio controls src="<?=PROOT.$media->audioUrl?>"
          <code>Your browser does not support the
          audio element.</code>
          </audio></td>
        <td class="text-right">
          <a class="btn btn-sm btn-secondary mr-1" href="<?=PROOT?>adminMusic/editTrack/<?=$media->id?>"><i class="fas fa-edit"></i></a>
          <a class="btn btn-sm btn-danger" href="#" onclick="deleteMedia('<?=$media->id?>');return false;"><i class="fas fa-trash-alt"></i></a>
        </td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>
<?php endif; ?>

<?php if($this->mixes): ?>
<h3 class="text-center pb-3">Mixes</h3>
<table class="table table-bordered table-hover table-striped table-sm">
  <thead>
    <th class="col-4">Name</th>
    <th class="col-4">Listen</th>
    <th class="col-4">Edit/Delete</th>
  </thead>
  <tbody>
    <?php foreach($this->mixes as $media):  ?>
      <tr data-id="<?=$media->id?>">
        <td><?=$media->name ?></td>
        <td><audio controls src="<?=PROOT.$media->audioUrl?>"
            <code>Your browser does not support the
            audio element.</code>
          </audio></td>
        <td class="text-right">
          <a class="btn btn-sm btn-secondary mr-1" href="<?=PROOT?>adminMusic/editMix/<?=$media->id?>"><i class="fas fa-edit"></i></a>
          <a class="btn btn-sm btn-danger" href="#" onclick="deleteMedia('<?=$media->id?>');return false;"><i class="fas fa-trash-alt"></i></a>
        </td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>

<?php endif; ?>

<?php if($this->albums): ?>
<h3 class="text-center pb-3">Digital Albums</h3>
<table class="table table-bordered table-hover table-striped table-sm">
  <thead>
    <th class="col-4">Name</th>
    <th class="col-4">Price</th>
    <th class="col-4">Edit/Delete</th>
  </thead>
  <tbody>
    <?php
    foreach($this->albums as $album):  ?>
      <tr data-id="<?=$album->id?>">
        <td><?=$album->name ?></td>
        <td><?=$album->price ?></td>
        <td class="text-right">
          <a class="btn btn-sm btn-secondary mr-1" href="<?=PROOT?>adminMusic/editAlbum/<?=$album->id?>"><i class="fas fa-edit"></i></a>
          <a class="btn btn-sm btn-danger" href="#" onclick="deleteStack('<?=$album->id?>');return false;"><i class="fas fa-trash-alt"></i></a>
        </td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>

<?php endif; ?>


<?php if($this->tapes): ?>
<h3 class="text-center pb-3">Tape Releases</h3>
<table class="table table-bordered table-hover table-striped table-sm">
  <thead>
    <th class="col-4">Name</th>
    <th class="col-4">Price</th>
    <th class="col-4">Edit/Delete</th>
  </thead>
  <tbody>
    <?php
    foreach($this->tapes as $tape):  ?>
      <tr data-id="<?=$tape->id?>">
        <td><?=$tape->name ?></td>
        <td><?=$tape->price ?></td>
        <td class="text-right">
          <a class="btn btn-sm btn-secondary mr-1" href="<?=PROOT?>adminMusic/editTape/<?=$tape->id?>"><i class="fas fa-edit"></i></a>
          <a class="btn btn-sm btn-danger" href="#" onclick="deleteStack('<?=$tape->id?>');return false;"><i class="fas fa-trash-alt"></i></a>
        </td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>

<?php endif; ?>

<script>
  function deleteMedia(id){
    if(window.confirm("Are you sure you want to delete this? It cannot be reversed.")){
      jQuery.ajax({
        url : '<?=PROOT?>adminMusic/delete',
        method : "POST",
        data : {id : id},
        success: function(resp){
          var msgType = (resp.success)? 'success' : 'danger';
          if(resp.success){
            jQuery('tr[data-id="'+resp.model_id+'"]').remove();
          }
          alertMsg(resp.msg, msgType);
        }
      });
    }
  }

  function deleteStack(id){
    if(window.confirm("Are you sure you want to delete this? It cannot be reversed.")){
      jQuery.ajax({
        url : '<?=PROOT?>adminMusic/deleteStack',
        method : "POST",
        data : {id : id},
        success: function(resp){
          var msgType = (resp.success)? 'success' : 'danger';
          if(resp.success){
            jQuery('tr[data-id="'+resp.model_id+'"]').remove();
          }
          alertMsg(resp.msg, msgType);
        }
      });
    }
  }

  function onlyPlayOneIn(container) {
    container.addEventListener("play", function(event) {
    audio_elements = container.getElementsByTagName("audio")
      for(i=0; i < audio_elements.length; i++) {
        audio_element = audio_elements[i];
        if (audio_element !== event.target) {
          audio_element.pause();
        }
      }
    }, true);
  }

  document.addEventListener("DOMContentLoaded", function() {
    onlyPlayOneIn(document.body);
});
</script>
<?php $this->end() ?>
