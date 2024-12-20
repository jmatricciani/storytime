<?php use Core\{H,FH}; ?>
<?php $this->start('head') ?>
  <script src='<?=PROOT?>vendor/tinymce/tinymce/tinymce.min.js'></script>
  <script>
    tinymce.init({
      selector: '#body',
      branding: false
    });
  </script>
<?php $this->end() ?>
<?php $this->setSiteTitle("Edit ".$this->album->name);?>
<?php $this->start('body'); ?>
  <!-- Modals -->
  <!-- Edit Album Name Modal -->
  <div class="modal fade" id="AlbumNameModal" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Edit Album Name</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          <form method="post" action="" id="editNameForm">
            <input type="hidden" id="stack_id" name="stack_id" value="<?=$this->album->id?>" />
            <?= FH::inputBlock('text','Name','nameStack',$this->album->name,['class'=>'form-control input-sm'],['class'=>'form-group col-md-12'],$this->displayErrors) ?>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary" onclick="saveName()">Save</button>
        </div>
      </div>

    </div>
  </div>
  <!-- Edit Album Price Modal -->
  <div class="modal fade" id="AlbumPriceModal" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Edit Album Price</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          <form method="post" action="" id="editPriceForm">
            <input type="hidden" id="stack_id" name="stack_id" value="<?=$this->album->id?>" />
            <?= FH::inputBlock('number','Price','priceStack',$this->album->price,['class'=>'form-control input-sm'],['class'=>'form-group col-md-12'],$this->displayErrors) ?>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary" onclick="savePrice()">Save</button>
        </div>
      </div>

    </div>
  </div>

  <!-- Edit Album Image Modal -->
    <div class="modal fade" id="AlbumImageModal" role="dialog">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title" >Edit Album Art</h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
          </div>
          <div class="modal-body">
            <div class="row">
              <div class="col-md-4">
                <img class="media-chart-image" id="modalAlbumImage" src="<?=PROOT.$this->albumArt->url?>" alt="not workings..">
              </div>
              <div class="col-md-8">
                <form method="post" action="" id="editAlbumImageForm">
                  <input type="hidden" id="stack_id" name="stack_id" value="<?=$this->album->id?>" />
                  <!-- file input form helper -->
                  <?= FH::inputBlockAccept('image',"Change Image:",'imageStack','',['class'=>'form-control','multiple'=>'multiple'],['class'=>'form-group'],$this->displayErrors) ?>
                </form>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary" onclick="saveAlbumImage()">Save</button>
          </div>
        </div>

      </div>
    </div>

<!-- Edit Album Body Modal -->
    <div class="modal fade" id="AlbumBodyModal" role="dialog">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">Edit Album Body</h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
          </div>
          <div class="modal-body">
            <form method="post" action="" id="editBodyForm">
              <input type="hidden" id="stack_id" name="stack_id" value="<?=$this->album->id?>" />
              <?= FH::textareaBlock('Body','bodyStack',$this->album->body,['class'=>'form-control','rows'=>'10'],['class'=>'form-group col-md-12'],$this->displayErrors) ?>
            </form>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary" onclick="saveBody()">Save</button>
          </div>
        </div>

      </div>
    </div>

<!-- Edit Track Name Modal -->
  <div class="modal fade" id="TrackNameModal" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Edit Track Name</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          <form method="post" action="" id="editTrackNameForm">
            <input type="hidden" id="media_id_name" name="media_id_name" value="new" />
            <?= FH::inputBlock('text','Track Name','nameMedia',"new",['class'=>'form-control input-sm'],['class'=>'form-group col-md-12'],$this->displayErrors) ?>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary" onclick="saveTrackName()">Save</button>
        </div>
      </div>

    </div>
  </div>

  <!-- Edit Track Image Modal -->
    <div class="modal fade" id="TrackImageModal" role="dialog">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title" >Edit Image for
              <span id="nameImageMedia"></span>
            </h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
          </div>
          <div class="modal-body">
            <div class="row">
              <div class="col-md-4">
                <img class="media-chart-image" id="modalTrackImage" src="" alt="not workings..">
              </div>
              <div class="col-md-8">
                <form method="post" action="" id="editTrackImageForm">
                  <input type="hidden" id="media_id_image" name="media_id" value="new" />
                  <!-- file input form helper -->
                  <?= FH::inputBlockAccept('image',"Change Image:",'imageMedia','',['class'=>'form-control'],['class'=>'form-group'],$this->displayErrors) ?>
                </form>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary" onclick="saveTrackImage()">Save</button>
          </div>
        </div>

      </div>
    </div>

    <!-- Edit Track Audio Modal -->
      <div class="modal fade" id="TrackAudioModal" role="dialog">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title" >Edit Audio for
                <span id="nameAudioMedia"></span>
              </h4>
              <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body col-md-12">
                  <!-- Use audio object -->
                  <audio
                  controls
                  id="modalAudio"
                  src="">
                  <code>Your browser does not support the
                  audio element.</code>
                  </audio>
                  <form method="post" action="" id="editTrackAudioForm">
                    <input type="hidden" id="media_id_audio" name="media_id" value="new" />
                    <!-- file input form helper -->
                    <?= FH::inputBlockAccept('audio',"Change Audio:",'audioMedia','',['class'=>'form-control'],['class'=>'form-group'],$this->displayErrors) ?>
                  </form>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-primary" onclick="saveTrackAudio()">Save</button>
            </div>
          </div>

        </div>
      </div>

  <!-- Page -->
  <div class="row align-items-center justify-content-center">
    <div class="col-md-6 page-header">
      <h1 class="text-center" id='albumNameHeader'>Edit Album</h1>
    </div>
  </div>
  <main class="album-wrapper">
    <!-- album info -->
    <div class="album">
      <a data-toggle="modal" href="#AlbumImageModal">
        <img src="<?= PROOT .$this->albumArt->url?>" id="albumImage" class="album-img" alt="<?=$this->album->name?>">
      </a>
      <div class="album-column">
        <h4 class="album-title"><a data-toggle="modal" href="#AlbumNameModal" id='albumName'><?=$this->album->name?></a></h4>
        <h4 class="album-title"><a data-toggle="modal" href="#AlbumPriceModal" id='albumPrice'>$<?=$this->album->price?></a></h4>
      </div>

    </div>
    <!-- Body -->

    <div class="album-body">
      <a class="album-body-link" data-toggle="modal" href="#AlbumBodyModal" id='albumBody'><?= html_entity_decode($this->album->body)?></a>
    </div>


    <!-- track info -->

    <table class="table table-bordered table-hover table-striped table-sm">
      <thead>
        <th>T#</th>
        <th>Image</th>
        <th>Name</th>
        <th>Audio</th>
      </thead>
      <tbody>

        <?php foreach($this->albumMedia as $media):
          ?>
          <tr data-id="<?=$media->id?>">
            <td>
              <h4><?=$media->sort?></h4>
            </td>
            <td>
              <a href="#TrackImageModal"  onclick="editTrackImage('<?=$media->id?>')">
                <img class="media-chart-image" id="mediaImage_<?=$media->id?>" src="<?=PROOT.$media->imageUrl?>" alt="No image for <?=$media->name?>">
              </a>
            </td>
            <td>
              <h5 >
                <a class="media-name" id="mediaName_<?=$media->id?>" href="#TrackNameModal" onclick="editTrackName('<?=$media->id?>')">
                  <?=$media->name?>
                </a>
              </h5>
            </td>
            <td>
              <audio id="mediaAudio_<?=$media->id?>"
              controls
              src="<?=PROOT.$media->audioUrl?>">
              <code>Your browser does not support the
              audio element.</code>
              </audio>
              <a class="btn btn-sm btn-secondary mr-1" href="#TrackAudioModal" onclick="editTrackAudio('<?=$media->id?>')">
                <i class="fas fa-edit"></i> Edit Audio
              </a>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>

  </main>

<script>

  // One Track at a Time script
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

  //Pause Modal script

  $("#TrackAudioModal").on("hidden.bs.modal", function () {
    // put your default event here
    document.getElementById('modalAudio').pause();
  });

  document.addEventListener("DOMContentLoaded", function() {
    onlyPlayOneIn(document.body);
  });

  // Save Name script

  function saveName(){
    var formData = jQuery('#editNameForm').serialize();
    jQuery.ajax({
      url : '<?=PROOT?>adminMusic/editName',
      method: "POST",
      data : formData,
      success: function(resp){
        if(resp.success){
          alertMsg("Album Name Saved",'success');
          jQuery('#AlbumNameModal').modal('hide');
          jQuery('#albumName').text(resp.stack.name);
          document.title = 'Edit '+resp.stack.name;
        }
        else{
          console.log(resp);
          alertMsg(resp.errors,'danger');
        }
      }
    });
  }

  function savePrice(){
    var formData = jQuery('#editPriceForm').serialize();
    jQuery.ajax({
      url : '<?=PROOT?>adminMusic/editPrice',
      method: "POST",
      data : formData,
      success: function(resp){
        if(resp.success){
          alertMsg("Album Price Saved",'success');
          jQuery('#AlbumPriceModal').modal('hide');
          jQuery('#albumPrice').text("$"+resp.stack.price);
        }
        else{
          console.log(resp);
          alertMsg(resp.errors,'danger');
        }
      }
    });
  }

  function saveAlbumImage(){
    var form = $('#editAlbumImageForm')[0];
    var formData = new FormData(form);
    event.preventDefault();
    jQuery.ajax({
      url : '<?=PROOT?>adminMusic/editStackImage',
      method: "POST",
      processData: false,
      contentType: false,
      data : formData,
      success: function(resp){
        if(resp.success){
          console.log(resp.file);
          d = new Date();
          path = resp.proot+resp.file.url+"?timestamp="+d.getTime();
          console.log(path);
          $('#albumImage').attr("src", path);
          $('#modalAlbumImage').attr("src", path);
          alertMsg("Album Art Saved",'success');
          jQuery('#AlbumImageModal').modal('hide');
        }
        else{
          alertMsg(resp.errors,'danger');
        }
      }
    });
  }

  // Save Body script

  function saveBody(){
    var formData = jQuery('#editBodyForm').serialize();
    jQuery.ajax({
      url : '<?=PROOT?>adminMusic/editBody',
      method: "POST",
      data : formData,
      success: function(resp){
        if(resp.success){
          alertMsg("Album Body Saved",'success');
          jQuery('#AlbumBodyModal').modal('hide');
          var echo = resp.echo;
          console.log(echo);
          jQuery('#albumBody').text(echo);
          jQuery('#bodyStack').text(echo);
        }
        else{
          alertMsg(resp.errors.body,'danger');
        }
      }
    });
  }

  // Edit Track Name

  function editTrackName(id){
    jQuery.ajax({
      url : '<?=PROOT?>adminMusic/getTrackById',
      method : "POST",
      data : {id:id},
      success : function(resp){
        if(resp.success){
          jQuery('#nameMedia').val(resp.media.name);
          jQuery('#media_id_name').val(resp.media.id);
          jQuery('#TrackNameModal').modal('show');
        } else {
          console.log('editTrackName response failed.');
        }
      }
    });

  }

  function saveTrackName(){
    var formData = jQuery('#editTrackNameForm').serialize();
    jQuery.ajax({
      url : '<?=PROOT?>adminMusic/editTrackName',
      method: "POST",
      data : formData,
      success: function(resp){
        if(resp.success){
          alertMsg("Track Name Saved",'success');
          jQuery('#TrackNameModal').modal('hide');
          jQuery('#mediaName_'+resp.media.id).text(resp.media.name);
        }
        else{
          alertMsg(resp.errors,'danger');
        }
      }
    });
  }

  // Edit Track Image
  function editTrackImage(id){
    jQuery.ajax({
      url : '<?=PROOT?>adminMusic/getTrackById',
      method : "POST",
      data : {id:id},
      success : function(resp){
        if(resp.success){
          d = new Date();
          path = resp.proot+resp.mediaImages.url+"?timestamp="+d.getTime();
          console.log(path);
          var proot = resp.proot;
          jQuery('#nameImageMedia').text(resp.media.name);
          jQuery('#media_id_image').val(resp.media.id);
          jQuery('#modalTrackImage').attr('src',path);
          jQuery('#imageMedia').val('');
          jQuery('#TrackImageModal').modal('show');

        } else {
          console.log('editTrackImage response failed.');
        }
      }
    });
  }

  function saveTrackImage(){
    var form = $('#editTrackImageForm')[0];
    var formData = new FormData(form);
    event.preventDefault();
    jQuery.ajax({
      url : '<?=PROOT?>adminMusic/editTrackImage',
      method: "POST",
      processData: false,
      contentType: false,
      data : formData,
      success: function(resp){
        if(resp.success){
          console.log(resp.file);
          d = new Date();
          path = resp.proot+resp.file.url+"?timestamp="+d.getTime();
          $('#mediaImage_'+resp.media_id).attr("src", path);
          $('#modalTrackImage').attr("src", path);
          alertMsg("Track Image Saved",'success');
          jQuery('#TrackImageModal').modal('hide');
        }
        else{
          alertMsg(resp.errors,'danger');
        }
      }
    });
  }

  // Edit Track Audio

  function editTrackAudio(id){
    jQuery.ajax({
      url : '<?=PROOT?>adminMusic/getTrackById',
      method : "POST",
      data : {id:id},
      success : function(resp){
        if(resp.success){
          var proot = resp.proot;
          console.log(resp.mediaAudio);
          console.log(proot+resp.mediaAudio.url);
          jQuery('#audioMedia').val('');
          jQuery('#nameAudioMedia').text(resp.media.name);
          jQuery('#media_id_audio').val(resp.media.id);
          jQuery('#modalAudio').attr('src',proot+resp.mediaAudio.url);
          jQuery('#TrackAudioModal').modal('show');
        } else {
          console.log('editTrackAudio response failed.');
        }
      }
    });
  }

  function saveTrackAudio(){
    var form = $('#editTrackAudioForm')[0];
    var formData = new FormData(form);
    event.preventDefault();
    jQuery.ajax({
      url : '<?=PROOT?>adminMusic/editTrackAudio',
      method: "POST",
      processData: false,
      contentType: false,
      data : formData,
      success: function(resp){
        if(resp.success){
          console.log(resp.file);
          d = new Date();
          path = resp.proot+resp.file.url+"?timestamp="+d.getTime();
          $('#mediaAudio_'+resp.media_id).attr("src", path);
          $('#modalAudio').attr("src", path);
          alertMsg("Track Audio Saved",'success');
          jQuery('#TrackAudioModal').modal('hide');
        }
        else{
          alertMsg(resp.errors,'danger');
        }
      }
    });
  }


</script>
<?php $this->end(); ?>
