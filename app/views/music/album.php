<?php use App\Lib\Elements\FrontEnd;
      use Core\{FH,H};

      $ffmpeg = FFMpeg\FFMpeg::create();
      H::dnd($ffmpeg);
?>
<?php $this->setSiteTitle($this->album->name); ?>
<?php $this->start('body') ?>

  <span class="br-row"></span>
  <h1 class="kanit-bold heading"><?=$this->album->name?></h1>
  <span class="br-head"></span>
  <div class="grid-2">
    <div class="image-block">
      <img class="full-image" src="<?=PROOT.$this->album->imageUrl?>" alt="<?=$this->album->name?>">
    </div>
    <div class="description-block">
      <h1 class="kanit-medium sub-heading">Digital Album</h1>
      <h1 class="roboto-light body-heading">by</h1>
      <h1 class="kanit-bold heading"><?=$this->artist_name?></h1>
      <p class="roboto-light body"><?=html_entity_decode($this->album->body)?></p>
      <h1 class="roboto-light body">Uploaded:<br/><?=$this->album->created_at?></h1>
    </div>
  </div>
  <span class="br-row"></span>
  <form action="<?=PROOT?>cart/addToCart" method="post">
    <?=FH::csrfInput()?>
    <?=FrontEnd::addToCart($this->album->price,0,true); ?>
    <input type="hidden" name="<?=$this->inventory->id?>" value="on">
  </form>

  <span class="br-row"></span>
  <?=FrontEnd::mediaPlayer(); ?>
  <?=FrontEnd::playlist($this->media,$this->artist_name);?>
  <span class="br-row"></span>
  <?=FrontEnd::backToMenuButton('music'); ?>
  <span class="br-row"></span>

  <script type="text/javascript">
  function rangeSlider(id, onDrag) {

      var slider = document.querySelector('span[id="slider"]');
      var tracker = document.querySelector('div[id="tracker"]');

      var range = document.getElementById(id),
          dragger = range.children[0],
          draggerWidth = 20, // width of your dragger
          down = false,
          rangeWidth, rangeLeft;

      dragger.style.width = draggerWidth + 'px';
      dragger.style.left = -draggerWidth + 'px';
      dragger.style.marginLeft = (draggerWidth / 2) + 'px';

      range.addEventListener("mousedown", function(e) {
          rangeWidth = this.offsetWidth;
          rangeLeft = this.offsetLeft;
          down = true;
          updateDragger(e);
          return false;
      });

      document.addEventListener("mousemove", function(e) {
          updateDragger(e);
      });

      document.addEventListener("mouseup", function() {
          down = false;
      });

      function updateDragger(e) {
          if (down && e.pageX >= rangeLeft && e.pageX <= (rangeLeft + rangeWidth)) {
              dragger.style.left = e.pageX - rangeLeft - draggerWidth + 'px';
              if (typeof onDrag == "function") onDrag(Math.round(((e.pageX - rangeLeft) / rangeWidth) * 100));
          }
      }

    }


    jQuery(document).ready(function() {

      // inner variables
      var song;
      var slider = document.querySelector('span[id="slider"]');
      var tracker = document.querySelector('div[id="tracker"]');

      function initAudio(elem) {
          //var elem = document.querySelector('tr[class="active"]');

          var image = elem.attr('imageurl');

          // set default image if empty
          if(image == '/storytime/'){
            image += "images/optimized/Storywes300.png";
          }
          var url = elem.attr('audiourl');
          var artist = elem.attr('artist');
          var title = elem.attr('title');

          $('.player .track-title').text(title);
          $('.player .artist-name').text(artist);
          $('.player .media-player-image').attr('src',image);

          song = new Audio(url);

          // timeupdate event listener
          song.addEventListener('timeupdate',function (){
              var curtime = parseInt(song.currentTime, 10);
              var sliderWidth = parseInt(slider.style.width);
              var duration = parseInt(song.duration);
              if(tracker.scrollWidth == 795){
                slider.style.left = (((curtime/ duration) * tracker.scrollWidth) - sliderWidth) + 'px';
              }
              var min = parseInt(song.currentTime/60,10);
              var sec = parseInt(song.currentTime%60);
              if (min < 10) {
                min = "0"+ min;
              }
              if (sec < 10) {
                sec = "0"+ sec;
              }

              $('.player .current-time').text(min+':'+sec);
              if(song.currentTime == song.duration){
                stopAudio();
                slider.style.left = 0 - sliderWidth + 'px';

                var next = $('.playlist tr.active').next();
                if (next.length == 0) {
                    next = $('.playlist tr:first-child');
                }
                initAudio(next);
                setTimeout(() => { playAudio();},500);
              }
          });

          $('.playlist tr').removeClass('active');
          elem.addClass('active');
      }
      function playAudio() {
          song.play();

          // tracker.slider("option", "max", song.duration);
          var maxMin = parseInt(song.duration/60,10);
          var maxSec = parseInt(song.duration%60);
          if (maxMin < 10) {
            maxMin = "0"+ maxMin;
          }
          if (maxSec < 10) {
            maxSec = "0"+ maxSec;
          }
          $('.player .max-time').text(maxMin+':'+maxSec);


          $('.play').addClass('hidden');
          $('.pause').removeClass('hidden');
      }
      function stopAudio() {
          song.pause();

          $('.play').removeClass('hidden');
          $('.pause').addClass('hidden');
      }

      // play click
      $('.play').click(function (e) {
          e.preventDefault();

          playAudio();
      });

      // pause click
      $('.pause').click(function (e) {
          e.preventDefault();

          stopAudio();
      });

      // forward click
      $('.forward').click(function (e) {
          e.preventDefault();

          stopAudio();

          var next = $('.playlist tr.active').next();
          if (next.length == 0) {
              next = $('.playlist tr:first-child');
          }
          initAudio(next);
          setTimeout(() => { playAudio();},500);
      });

      // rewind click
      $('.rewind').click(function (e) {
          e.preventDefault();

          stopAudio();

          var prev = $('.playlist tr.active').prev();
          if (prev.length == 0) {
              prev = $('.playlist tr:last-child');
          }
          initAudio(prev);
          setTimeout(() => { playAudio();},500);
      });

      // playlist elements - click
      $('.playlist tr').click(function () {
          stopAudio();
          initAudio($(this));
          setTimeout(() => { playAudio();},500);
      });

      // initialization - first element in playlist
      initAudio($('.playlist tr:first-child'));

      // set volume
      song.volume = 1.0;

      rangeSlider('tracker', function(value) {
          // Make changes to media player
          var pos = parseInt(slider.style.left);
          var width = parseInt(slider.style.width);
          var curtime = ((pos + width) / tracker.scrollWidth) * song.duration;
          song.currentTime = curtime;
      });
    });
  </script>
<?php $this->end() ?>
