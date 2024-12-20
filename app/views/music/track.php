<?php use App\Lib\Elements\FrontEnd;
      use Core\{FH,H};
      $ffprobe = FFMpeg\FFProbe::create(array(
  'ffprobe.binaries' => ROOT.'/vendor/bin/ffprobe'));
      $path = ROOT.'/'.$this->track->audioUrl;
      H::dnd($ffprobe->format($path));
    // $ffmpeg = FFMpeg\FFMpeg::create(array(
    //       'ffmpeg.binaries'  => '/opt/lampp/htdocs/storytime/vendor/bin/ffmpeg',
    //       'ffprobe.binaries' => '/opt/lampp/htdocs/storytime/vendor/bin/ffprobe',
    //       'timeout'          => 3600, // The timeout for the underlying process
    //       'ffmpeg.threads'   => 12,   // The number of threads that FFMpeg should use
    //   ));
    // $audio = $ffmpeg->open(PROOT.$this->track->audioUrl);
    //$waveform = $audio->waveform(640, 120, array('#00FF00'));
    // H::dnd($audio);
    // $waveform->save('waveform.png');
?>
<?php $this->setSiteTitle($this->track->name); ?>
<?php $this->start('body'); ?>

  <span class="br-row"></span>
  <h1 class="kanit-bold heading"><?=$this->track->name?></h1>
  <span class="br-head"></span>
  <div class="grid-2">
    <div class="image-block">
      <img class="full-image" src="<?=PROOT.$this->track->imageUrl?>" alt="<?=$this->track->name?>">
    </div>
    <div class="description-block">
      <h1 class="kanit-medium sub-heading">Track</h1>
      <h1 class="roboto-light body-heading">by</h1>
      <h1 class="kanit-bold heading"><?=$this->artist_name?></h1>
      <p class="roboto-light body"><?=html_entity_decode($this->track->body)?></p>
      <h1 class="roboto-light body">Uploaded:<br/><?=$this->track->created_at?></h1>
    </div>
  </div>
  <input type="hidden" id="track" audiourl="<?=PROOT.$this->track->audioUrl?>" cover="<?=PROOT.$this->track->imageUrl?>" title="<?=$this->track->name?>" artist="<?=$this->artist_name?>"/>
  <span class="br-row"></span>
  <?=FrontEnd::mediaPlayer(); ?>
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

    function initAudio() {
        var elem = document.querySelector('input[id="track"]');
        var url = elem.getAttribute('audiourl');
        // var cover = elem.getAttribute('cover');
        var artist = elem.getAttribute('artist');
        var title = elem.getAttribute('title');

        $('.player .track-title').text(title);
        $('.player .artist-name').text(artist);
        // $('.player .cover').css('background-image','url('+cover+')');;

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
            }
        });
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
        // e.preventDefault();
        //
        // stopAudio();
        //
        // var next = $('.playlist li.active').next();
        // if (next.length == 0) {
        //     next = $('.playlist li:first-child');
        // }
        // initAudio(next);
        // setTimeout(() => { playAudio();},500);
    });

    // rewind click
    $('.rewind').click(function (e) {
        // e.preventDefault();
        //
        // stopAudio();
        //
        // var prev = $('.playlist li.active').prev();
        // if (prev.length == 0) {
        //     prev = $('.playlist li:last-child');
        // }
        // initAudio(prev);
        // setTimeout(() => { playAudio();},500);
    });

    // playlist elements - click
    $('.playlist tr').click(function () {
        // stopAudio();
        // initAudio($(this));
        // setTimeout(() => { playAudio();},500);
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
