<?php
  use Core\{H,FH};
?>
<?php $this->start('body')?>
<h1 class="text-center pb-5">Your Uploaded Images</h1>

<?php if($this->images): ?>
  <table class="table table-bordered table-hover table-striped table-sm">
    <thead>
      <th class="col-4">Name</th>
      <th class="col-4">Last Updated</th>
      <th class="col-2">Edit/Delete</th>
    </thead>
    <tbody>
      <?php foreach($this->images as $image): ?>
        <?php
          $tz = 'America/North_Dakota/New_Salem';
          $date = new DateTime($image->updated_at);
          $date->setTimezone(new DateTimeZone($tz));
         ?>
        <tr data-id="<?=$image->id?>" id="image_<?=$image->id?>">
          <td><?=$image->name ?></td>
          <td><?=$date->format('H:i d-m')?></td>
          <td class="text-right">
            <a class="btn btn-sm btn-secondary mr-1" href="<?=PROOT?>adminGallery/edit/<?=$image->id?>"><i class="fas fa-edit"></i></a>
            <a class="btn btn-sm btn-danger" href="#" onclick="deleteImage('<?=$image->id?>');return false;"><i class="fas fa-trash-alt"></i></a>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
<?php endif; ?>

<script>
  function deleteImage(id)
  {
    if(window.confirm("Are you sure you want to delete this image? It cannot be reversed.")){
      jQuery.ajax({
        url : '<?=PROOT?>adminGallery/delete',
        method : "POST",
        data : {id : id},
        success: function(resp){
          var msgType = (resp.success)? 'success' : 'danger';
          if(resp.success){
            jQuery('tr[id="image_'+resp.model_id+'"]').remove();
          }
          alertMsg(resp.msg, msgType);
        }
      });
    }
  }
</script>

<?php $this->end(); ?>
