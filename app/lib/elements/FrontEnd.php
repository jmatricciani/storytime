<?php

  namespace App\Lib\Elements;

  use Core\{H};

  class FrontEnd{

    public static function addToCart($price,$shipping,$digital = false){

      $html = '<div class="addToCart grid-2">';

      $html .= '<div class="price-box">
                  <span class="price kanit-bold heading">$'.$price.'</span>';

      if($digital){
        $html .= '<span class="dialog roboto-light body-heading">Available in multiple media formats</span>';
        $html .= '<input type=hidden name="digital" id="digital" value="true"/>';
      } else {
        $html .= '<span class="dialog roboto-light body-heading">+ $'.$shipping.' shipping</span>';
      }
      $html .= '</div><button type="submit" id="addToCart" class="btn btn-lg dark-cyan kanit-medium addToCartButton heading">Add To Cart</button>';
      $html .= '</div>';


      return $html;
    }

    public static function mediaPlayer(){

      $html = '
        <div class="media-player player">
          <div class="grid-12 justify-items-center pt-4">
            <span class="artist-name kanit-medium title-heading"></span>
            <span class="hyphen roboto-light body-heading">-</span>
            <span class="track-title kanit-medium body-heading"></span>
          </div>
          <span class="br-controls"></span>
          <div class="grid-2 justify-items-center">
            <div class="media-player-image-block">
              <img class="media-player-image cover" src="'.PROOT.'images/optimized/Storywes300.png" alt=""></img>
            </div>
            <span class="controls">
              <div class="rewind">
                <img src="'.PROOT.'images/mediaPlayer/left.png" alt="">
              </div>
              <div class="play">
                <img src="'.PROOT.'images/mediaPlayer/play.png" alt="">
              </div>
              <div class="pause hidden">
                <img src="'.PROOT.'images/mediaPlayer/pause.png" alt="">
              </div>
              <div class="forward">
                <img src="'.PROOT.'images/mediaPlayer/right.png" alt="">
              </div>
            </span>
          </div>

          <span class="br-controls"></span>
          <span class="track-bar grid-12">
            <span class="current-time kanit-medium body-heading">
              00:00
            </span>
              <div class="tracker range-slider" id="tracker">
                <span id="slider"></span>
              </div>
            <span class="max-time kanit-medium body-heading">
              00:00
            </span>

          </span>
        </div>
      ';

      return $html;
    }

    public static function playlist($medias,$artist_name){
      $html = '<table class="playlist"><tbody>';
      foreach($medias as $media){
        if($media->sort % 2){
          $html .= '<tr class="brown-row';
          if($media->sort == 1)
            $html .= ' active';
          $html .=  '" data-id="'.$media->id.'" audiourl="'.PROOT.$media->audioUrl.'" imageurl="'.PROOT.$media->imageUrl.'" artist="'.$artist_name.'" title="'.$media->name.'">
              <td class="roboto-light body-heading light text-center">'.$media->label.'</td>
              <td class="roboto-light body-heading light">'.$media->name.'</td>
              <td class="roboto-light body-heading light text-center">'.$media->id.'</td>
            </tr>';
        } else {
          $html .= '
            <tr class="green-row" data-id="'.$media->id.'" audiourl="'.PROOT.$media->audioUrl.'" imageurl="'.PROOT.$media->imageUrl.'" artist="'.$artist_name.'" title="'.$media->name.'">
              <td class="roboto-light body-heading dark text-center">'.$media->label.'</td>
              <td class="roboto-light body-heading dark">'.$media->name.'</td>
              <td class="roboto-light body-heading dark text-center">'.$media->id.'</td>
            </tr>';
        }
      }
      $html .= '</tbody></table>';

      return $html;
    }

    public static function isAvailable($inventories){
      //H::dnd($inventories);
      $available = false;
      foreach($inventories as $inv){
        if($inv->stock)
          $available = true;
      }
      return $available;
    }

    public static function sizeOptions($inventories){
      $womens = array();
      $mens = array();

      foreach($inventories as $inventory){
        $inv = explode('_',$inventory->option);
        if($inv[0] == 'womens'){
          array_push($womens,$inventory);
        } else if( $inv[0] == 'mens') {
          array_push($mens,$inventory);
        }
      }
      $html = '
      <!-- html for size options -->


        <h1 class="gender-label kanit-bold body-heading text-center">Women</h1>
        <h1 class="gender-label kanit-bold body-heading text-center">Men</h1>
        <div class="gender-sizes">';
        foreach($womens as $inv){
          if($inv->stock != 0){
            $option = strtoupper(explode('_',$inv->option)[1]);
            $html.= '
            <div class="size embed-grid-2">
              <input type="checkbox" class="defaultCheckbox" id="option"
                  name="'.$inv->id.'" onchange="updateView(this,'.$inv->stock.');"/>
              <h1 class="size-label kanit-bold body">'.$option.'</h1>
            </div>
            ';
          }
        }
        $html .='</div><div class="gender-sizes">';
        foreach($mens as $inv){
          if($inv->stock != 0){
            $option = strtoupper(explode('_',$inv->option)[1]);
            $html.= '
            <div class="size embed-grid-2">
              <input type="checkbox" class="defaultCheckbox" id="option"
                  name="'.$inv->id.'" onchange="updateView(this,'.$inv->stock.');"/>
              <h1 class="size-label kanit-bold body">'.$option.'</h1>
            </div>
            ';
          }
        }

      $html.= '</div>';


      return $html;
    }

    public static function backToMenuButton($controller){
      $html = '<div class="full-grid">
                <a href="'.PROOT.$controller.'" class="btn btn-lg dark kanit-medium backToMenuButton sub-heading">
                  Back to Menu
                </a>
              </div>
              ';
      return $html;
    }

  }
