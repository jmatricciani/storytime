<?php $this->start('body')?>
<h1 class="text-center pb-5">Your Uploaded Merch</h1>

<?php if($this->items): ?>
  <h3 class="text-center pb-3">Items</h3>
  <table class="table table-bordered table-hover table-striped table-sm">
    <thead>
      <th class="col-4">Name</th>
      <th class="col-2">Price</th>
      <th class="col-2">Shipping</th>
      <th class="col-2">Stock</th>
      <th class="col-2"></th>
    </thead>
    <tbody>
      <?php foreach($this->items as $item): ?>
        <tr data-id="<?=$item->id?>">
          <td><?=$item->name ?></td>
          <td><?=$item->price ?></td>
          <td><?=$item->shipping ?></td>
          <td><?=$item->_stock?></td>
          <td class="text-right">
            <a class="btn btn-sm btn-secondary mr-1" href="<?=PROOT?>adminMerch/editItem/<?=$item->id?>"><i class="fas fa-edit"></i></a>
            <a class="btn btn-sm btn-danger" href="#" onclick="deleteProduct('<?=$item->id?>');return false;"><i class="fas fa-trash-alt"></i></a>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
<?php endif; ?>

<?php if($this->apparel): ?>
  <h3 class="text-center pb-3">Apparel</h3>
  <table class="table table-bordered table-hover table-striped table-sm">
    <thead>
      <th class="col-4">Name</th>
      <th class="col-2">Price</th>
      <th class="col-2">Shipping</th>
      <th class="col-2">Stock</th>
      <th class="col-2"></th>
    </thead>
    <tbody>
      <?php foreach($this->apparel as $app): ?>
        <tr data-id="<?=$app->id?>">
          <td><?=$app->name ?></td>
          <td><?=$app->price ?></td>
          <td><?=$app->shipping ?></td>
          <td><?=$app->_stock?></td>
          <td class="text-right">
            <a class="btn btn-sm btn-secondary mr-1" href="<?=PROOT?>adminMerch/editApparel/<?=$app->id?>"><i class="fas fa-edit"></i></a>
            <a class="btn btn-sm btn-danger" href="#" onclick="deleteProduct('<?=$app->id?>');return false;"><i class="fas fa-trash-alt"></i></a>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
<?php endif; ?>





<script>
  function deleteProduct(id){
    if(window.confirm("Are you sure you want to delete this product. It cannot be reversed.")){
      jQuery.ajax({
        url : '<?=PROOT?>adminMerch/delete',
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
</script>
<?php $this->end(); ?>
