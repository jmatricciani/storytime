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
<?php $this->setSiteTitle("Edit ".$this->tape->name);?>
<?php $this->start('body'); ?>
  <!-- Modals -->
  <!-- Edit Tape Name Modal -->
  <div class="modal fade" id="TapeNameModal" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Edit Tape Name</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          <form method="post" action="" id="editNameForm">
            <input type="hidden" id="stack_id" name="stack_id" value="<?=$this->tape->id?>" />
            <?= FH::inputBlock('text','Name','nameStack',$this->tape->name,['class'=>'form-control input-sm'],['class'=>'form-group col-md-12'],$this->displayErrors) ?>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary" onclick="saveName()">Save</button>
        </div>
      </div>

    </div>
  </div>
  <!-- Edit Tape Price Modal -->
  <div class="modal fade" id="TapePriceModal" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Edit Tape Price</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          <form method="post" action="" id="editPriceForm">
            <input type="hidden" id="stack_id" name="stack_id" value="<?=$this->tape->id?>" />
            <?= FH::inputBlock('number','Price','priceStack',$this->tape->price,['class'=>'form-control input-sm'],['class'=>'form-group col-md-12'],$this->displayErrors) ?>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary" onclick="savePrice()">Save</button>
        </div>
      </div>

    </div>
  </div>
  <!-- Edit Tape Shiping Modal -->
  <div class="modal fade" id="TapeShippingModal" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Edit Tape Shipping</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          <form method="post" action="" id="editShippingForm">
            <input type="hidden" id="stack_id" name="stack_id" value="<?=$this->tape->id?>" />
            <?= FH::inputBlock('number','Shipping','shippingStack',$this->tape->shipping,['class'=>'form-control input-sm'],['class'=>'form-group col-md-12'],$this->displayErrors) ?>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary" onclick="saveShipping()">Save</button>
        </div>
      </div>

    </div>
  </div>

  <!-- Edit Tape Image Modal -->
    <div class="modal fade" id="TapeImageModal" role="dialog">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title" >Edit Tape Image</h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
          </div>
          <div class="modal-body">
            <div class="row">
              <div class="col-md-4">
                <img class="media-chart-image" id="modalTapeImage" src="<?=PROOT.$this->tapeArt->url?>" alt="not workings..">
              </div>
              <div class="col-md-8">
                <form method="post" action="" id="editTapeImageForm">
                  <input type="hidden" id="stack_id" name="stack_id" value="<?=$this->tape->id?>" />
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

<!-- Edit Tape Body Modal -->
    <div class="modal fade" id="TapeBodyModal" role="dialog">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">Edit Tape Body</h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
          </div>
          <div class="modal-body">
            <form method="post" action="" id="editBodyForm">
              <input type="hidden" id="stack_id" name="stack_id" value="<?=$this->tape->id?>" />
              <?= FH::textareaBlock('Body','bodyStack',$this->tape->body,['class'=>'form-control','rows'=>'10'],['class'=>'form-group col-md-12'],$this->displayErrors) ?>
            </form>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary" onclick="saveBody()">Save</button>
          </div>
        </div>

      </div>
    </div>

  <!-- Edit Track Label Modal -->
    <div class="modal fade" id="TrackLabelModal" role="dialog">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">Edit Track Label</h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
          </div>
          <div class="modal-body">
            <form method="post" action="" id="editTrackLabelForm">
              <input type="hidden" id="media_id_label" name="media_id_label" value="new" />
              <?= FH::inputBlock('text','Label Name','labelMedia',"new",['class'=>'form-control input-sm'],['class'=>'form-group col-md-12'],$this->displayErrors) ?>
            </form>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary" onclick="saveTrackLabel()">Save</button>
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
                  <?= FH::inputBlockAccept('image',"Change Image:",'imageMedia','',['class'=>'form-control','multiple'=>'multiple'],['class'=>'form-group'],$this->displayErrors) ?>
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
                  <code>
                    Your browser does not support the
                    audio element.
                  </code>
                  </audio>
                  <form method="post" action="" id="editTrackAudioForm">
                    <input type="hidden" id="media_id_audio" name="media_id" value="new" />
                    <!-- file input form helper -->
                    <?= FH::inputBlockAccept('audio',"Change Audio:",'audioMedia','',['class'=>'form-control','multiple'=>'multiple'],['class'=>'form-group'],$this->displayErrors) ?>
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
      <h1 class="text-center" id='tapeNameHeader'>Edit Tape</h1>
    </div>
  </div>
  <main class="album-wrapper">
    <!-- album info -->
    <div class="album">
      <a data-toggle="modal" href="#TapeImageModal">
        <img src="<?= PROOT .$this->tapeArt->url?>" id="tapeImage" class="album-img" alt="<?=$this->tape->name?>">
      </a>
      <div class="album-column">
        <h3 class="album-title"><a data-toggle="modal" href="#TapeNameModal" id='tapeName'><?=$this->tape->name?></a></h3>
        <h3 class="album-title"><a data-toggle="modal" href="#TapePriceModal" id='tapePrice'>$<?=$this->tape->price?></a></h3>
        <h5>Shipping:</h5>
        <h5 class="album-title"><a data-toggle="modal" href="#TapeShippingModal" id='tapeShipping'>$<?=$this->tape->shipping?></a></h5>
      </div>

    </div>
    <!-- Body -->

    <div class="album-body">
      <a class="album-body-link" data-toggle="modal" href="#TapeBodyModal" id='tapeBody'><?= html_entity_decode($this->tape->body)?></a>
    </div>


    <!-- track info -->

    <table class="table table-bordered table-hover table-striped table-sm">
      <thead>
        <th>Label</th>
        <th>Image</th>
        <th>Name</th>
        <th>Audio</th>
      </thead>
      <tbody>

        <?php foreach($this->tapeMedia as $media):
          ?>
          <tr data-id="<?=$media->id?>">
            <td>
              <h5>
                <a class="media-name text-info" id="mediaLabel_<?=$media->id?>" href="#TrackLabelModal" onclick="editTrackLabel('<?=$media->id?>')">
                  <?=$media->label?>
                </a>
              </h5>
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
              <code>
                Your browser does not support the
                audio element.
              </code>
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
          alertMsg("Tape Name Saved",'success');
          jQuery('#TapeNameModal').modal('hide');
          jQuery('#tapeName').text(resp.stack.name);
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
          alertMsg("Tape Price Saved",'success');
          jQuery('#TapePriceModal').modal('hide');
          jQuery('#tapePrice').text("$"+resp.stack.price);
        }
        else{
          console.log(resp);
          alertMsg(resp.errors,'danger');
        }
      }
    });
  }

  function saveShipping(){
    var formData = jQuery('#editShippingForm').serialize();
    jQuery.ajax({
      url : '<?=PROOT?>adminMusic/editShipping',
      method: "POST",
      data : formData,
      success: function(resp){
        if(resp.success){
          alertMsg("Tape Shipping Saved",'success');
          jQuery('#TapeShippingModal').modal('hide');
          jQuery('#tapeShipping').text("$"+resp.stack.shipping);
        }
        else{
          console.log(resp);
          alertMsg(resp.errors,'danger');
        }
      }
    });
  }

  function saveAlbumImage(){
    var form = $('#editTapeImageForm')[0];
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
          $('#tapeImage').attr("src", path);
          $('#modalTapeImage').attr("src", path);
          alertMsg("Tape Image Saved",'success');
          jQuery('#TapeImageModal').modal('hide');
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
          alertMsg("Tape Body Saved",'success');
          jQuery('#TapeBodyModal').modal('hide');
          var echo = resp.echo;
          console.log(echo);
          jQuery('#tapeBody').text(echo);
          jQuery('#bodyTape').text(echo);
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

  // Edit Track Name

  function editTrackLabel(id){
    jQuery.ajax({
      url : '<?=PROOT?>adminMusic/getTrackById',
      method : "POST",
      data : {id:id},
      success : function(resp){
        if(resp.success){
          jQuery('#labelMedia').val(resp.media.label);
          jQuery('#media_id_label').val(resp.media.id);
          jQuery('#TrackLabelModal').modal('show');
        } else {
          console.log('editTrackLabel response failed.');
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
          console.log(resp);
          alertMsg(resp.errors,'danger');
        }
      }
    });
  }

  function saveTrackLabel(){
    var formData = jQuery('#editTrackLabelForm').serialize();
    jQuery.ajax({
      url : '<?=PROOT?>adminMusic/editTrackLabel',
      method: "POST",
      data : formData,
      success: function(resp){
        if(resp.success){
          alertMsg("Track Label Saved",'success');
          jQuery('#TrackLabelModal').modal('hide');
          jQuery('#mediaLabel_'+resp.media.id).text(resp.media.label);
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
