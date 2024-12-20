<?php
use Core\{FH,H};
?>

<?php $this->start('head') ?>
  <script src='<?=PROOT?>vendor/tinymce/tinymce/tinymce.min.js'></script>
  <script>
    tinymce.init({
      selector: '#body',
      branding: false
    });
  </script>
<?php $this->end() ?>
<?php $this->setSiteTitle("Upload Album") ?>
<?php $this->start('body') ?>
<div class="row align-items-center justify-content-center">
  <div class="col-md-8 p-3">
    <h1 class="text-center">Upload Album</h1>

    <form action="<?=$this->formAction?>" method="POST" enctype="multipart/form-data">
      <?= FH::csrfInput();?>
      <?= FH::displayErrors($this->displayErrors)?>
      <div class="row">
        <?= FH::inputBlock('text','Name','name',$this->album->name,['class'=>'form-control input-sm'],['class'=>'form-group col-md-6'],$this->displayErrors) ?>
        <?= FH::inputBlockAccept('image',"Upload Album Art:",'albumArt[]','',['class'=>'form-control','multiple'=>'multiple'],['class'=>'form-group col-md-6'],$this->displayErrors) ?>
      </div>
      <div class="row">
        <?= FH::inputBlock('text','Price','price',$this->album->price,['class'=>'form-control input-sm'],['class'=>'form-group col-md-2'],$this->displayErrors) ?>
      </div>
      <div class="row">
        <?= FH::textareaBlock('Body','body',$this->album->body,['class'=>'form-control','rows'=>'6'],['class'=>'form-group col-md-12'],$this->displayErrors) ?>
      </div>

      <div class="row justify-content-center">
        <h2 class="">Upload Tracks</h2>
      </div>


      <div class="row justify-content-center">
        <span>
          <label for="numTracks">Select the number of tracks: </label>
          <select name="numTracks" onchange="changedSelect(this.value,'Album')" id="numTracks">
            <?php
              for($i=0;$i<=MAX_NUM_TRACKS;$i++){
                  echo '<option value="'.$i.'">'.$i.'</option>';
              }
             ?>
          </select>
        </span>
      </div>



      <div class="tracksUpload" name="tracksUpload" id="tracksUpload"></div>

      <div class="row">
        <?= FH::submitBlock('Save',['class'=>'btn btn-large btn-primary mt-5'],['class'=>'text-right col-md-12']); ?>
      </div>
    </form>


  </div>
</div>

<script>
function changedSelect(num,type)
{
    jQuery.ajax({
      url: '<?=PROOT?>adminMusic/changeTrackValue',
      method: "POST",
      data : {num : num,
              type : type},
      success : function(resp) {
        if(resp.success){
          document.getElementById('tracksUpload').innerHTML = '<div class="row justify-content-center"><h5 class="text-danger">Warning: Changing the number of tracks will remove any selected files</h5></div>';
          for (var i = 1; i <= num; i++) {
            document.getElementById('tracksUpload').innerHTML += '<div class="row track-form"><span class="col col-md-1 m-3"><h1>'+i+'</h1></span><div class="form-group col-md-2"><label class="control-label" for="mediaNames">Track Name:</label><input type="text" id="mediaNames" name="mediaNames[]" value="" class="form-control"><span class="invalid-feedback"></span></div><div class="form-group col-md-4"><label class="control-label" for="mediaImages">Upload Track Image:</label><input type="file" id="mediaImages" name="mediaImage[]" value="" class="form-control" accept="image/*"><span class="invalid-feedback"></span></div><div class="form-group col-md-4"><label class="control-label" for="mediaAudio">Upload Audio:</label><input type="file" id="mediaAudio" name="mediaAudio[]" value="" class="form-control" accept="audio/*"><span class="invalid-feedback"></span></div></div>';
          }
          alertMsg(resp.msg,'success');
        } else {
          alertMsg(resp.msg, 'danger');
        }
      }
    });
}
</script>

<?php $this->end(); ?>
