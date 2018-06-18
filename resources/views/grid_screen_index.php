<div class="grid-screen grid_screen-container">
  <?php
    if (!empty($_GET['zype_parent'])) {
      $parent_id=$_GET['zype_parent'];
    }

    $get_all = !empty($_GET['zype_get_all'])? $_GET['zype_get_all']: 0;

    if (!empty($_GET['zype_str'])) {
      $page = $_GET['zype_str'];
    } else {
      $page = 1;
    }

    if ($get_all!=0 and $get_all!=2) {
      exit("can't load page");
    }

    $pagination = Config::get('zype.playlist_pagination', true);

    $i=0; $j=0;
  ?>

  <div class="content-box grid_screen-box">
    <?php if ((!empty($_GET['zype_items']) && $_GET['zype_items'] > 0 || $get_all == 2) && $parent_playlist ): ?>
      <h2 class="playlist-view-title"><?php echo $parent_playlist->title ?></h2>
    <?php endif ?>

    <div class="box-with-content <?php echo $get_all == 2? 'box-with-content-viewall': '' ?>" >
      <?php foreach($content as $cont): ?>
        <?php
          $items = !empty($cont->playlist_item_count)? $cont->playlist_item_count: 0;
          $id = $cont->_id;
          $thumbnail_layout = !empty($cont->thumbnail_layout)? $cont->thumbnail_layout: 'landscape';
        ?>
        <?php if($get_all == 0): ?>
          <!-- normal mode -->
          <?php if(empty($cont->playlist_type) || !$cont->playlist_type): ?>
            <?php
              $thumbnail_layout = !empty($parent_playlist->thumbnail_layout)? $parent_playlist->thumbnail_layout: 'landscape';

              $poster_image = '';
              if( $thumbnail_layout == 'poster' && !empty($cont->images) ) {
                foreach( $cont->images as $image ){
                  if( $image->layout == 'poster'){
                    $poster_image = $image->url;
                    break;
                  }
                }
              }

              if( $poster_image ){
                $background_image = $poster_image;
              } else {
                if (!empty($cont->thumbnails[0]->url)) {
                  $background_image = $cont->thumbnails[0]->url;
                } else {
                  $background_image = $thumbnail_layout == 'landscape'? asset_url('images/320x180.png'): asset_url('images/200x300.png');
                }
              }

            ?>
            <div class="view_all_images zype-<?php echo $thumbnail_layout ?>">
              <a href="<?php echo get_permalink().'?zype_wp=true&zype_type=video_single&zype_video_id='.$cont->_id ?>">
                <div class="zype-background-thumbnail" style="background-image: url(<?php echo $background_image ?>);">
                </div>
              </a>

              <div title="<?php echo $cont->title ?>" class="item_title_block"><?php echo $cont->title ?></div>
            </div>
          <?php else: ?>
            <div class="playlist-with-content">
              <div class="slider_links">
                <div class="slider_links-title">
                  <a href="<?php echo get_permalink().'?zype_parent='.$id.'&zype_items='.$items?>"><?php echo $cont->title ?></a>

                </div>
                <div class="get-all-playlists slider_links-all" >
                  <?php if($pagination): ?>
                    <a href="<?php echo get_permalink().'?zype_get_all=2&zype_parent='.$id.'&zype_items='.$items ?>">See all</a>
                  <?php else: ?>
                    <a href="<?php echo get_permalink().'?zype_parent='.$id.'&zype_items='.$items ?>">See all</a>
                  <?php endif ?>
                </div>
              </div>
              <div class="slider-list zype-<?php echo $thumbnail_layout ?>">
              <?php if ($subcontent): ?>
                <?php foreach ($subcontent as $sub): ?>
                  <?php
                    $poster_image = '';
                    if( $thumbnail_layout == 'poster' && !empty($sub->images) ) {
                      foreach( $sub->images as $image ){
                        if( $image->layout == 'poster'){
                          $poster_image = $image->url;
                          break;
                        }
                      }
                    }

                    if( $poster_image ){
                      $background_image = $poster_image;
                    } else {
                      if (!empty($sub->thumbnails[0]->url)) {
                        $background_image = $sub->thumbnails[0]->url;
                      } else {
                        $background_image = $thumbnail_layout == 'landscape'? asset_url('images/320x180.png'): asset_url('images/200x300.png');
                      }
                    }
                  ?>

                  <?php if (!empty($sub->playlist_type)): ?>
                    <?php if($sub->parent_id == $cont->_id): ?>
                      <div class="slider_slide_first">
                        <?php
                          $id = $sub->_id;
                          $items = !empty($sub->playlist_item_count)? $sub->playlist_item_count: 0;
                        ?>
                        <a href="<?php echo get_permalink().'?zype_parent='.$id.'&zype_items='.$items?>">
                          <div class="zype-background-thumbnail" style="background-image: url(<?php echo $background_image ?>);">
                          </div>
                        </a>
                        <div title="<?php echo $sub->title ?>" class="item_title_block">
                          <?php echo $sub->title ?>
                        </div>
                      </div>
                    <?php endif ?>
                  <?php else: ?>
                    <?php if($sub->parent_id == $cont->_id): ?>
                      <div class="slider_slide_second">
                        <a href="<?php echo get_permalink().'?zype_wp=true&zype_type=video_single&zype_video_id='.$sub->_id ?>">
                          <div class="zype-background-thumbnail" style="background-image: url(<?php echo $background_image ?>);">
                          </div>
                        </a>
                        <div title="<?php echo $sub->title ?>" class="item_title_block">
                          <?php echo $sub->title ?>
                        </div>
                      </div>
                    <?php endif ?>
                  <?php endif ?>
                <?php endforeach ?>
              <?php endif ?>
            </div>
          </div>

          <?php endif ?>

        <?php elseif ($get_all == 2): ?>
          <?php
            $id = $cont->_id;
            $items = !empty($playlist_item_count->playlist_item_count)? $playlist_item_count->playlist_item_count: 0;
            $thumbnail_layout = !empty($parent_playlist->thumbnail_layout)? $parent_playlist->thumbnail_layout: 'landscape';

            $poster_image = '';
            if( $thumbnail_layout == 'poster' && !empty($cont->images) ) {
              foreach( $cont->images as $image ){
                if( $image->layout == 'poster'){
                  $poster_image = $image->url;
                  break;
                }
              }
            }

            if( $poster_image ){
              $background_image = $poster_image;
            } else {
              if (!empty($cont->thumbnails[0]->url)) {
                $background_image = $cont->thumbnails[0]->url;
              } else {
                $background_image = $thumbnail_layout == 'landscape'? asset_url('images/320x180.png'): asset_url('images/200x300.png');
              }
            }
          ?>

          <?php if (!empty($cont->playlist_type)): ?>
            <div class="view_all_images zype-<?php echo $thumbnail_layout ?>">
              <a href="<?php echo get_permalink().'?zype_parent='.$id.'&zype_items='.$items?>">
                <div class="zype-background-thumbnail" style="background-image: url(<?php echo $background_image ?>);">
                </div>
              </a><br>
              <div class="item_title_block"><?php echo $cont->title ?></div>
            </div>
          <?php else: ?>
            <div class="view_all_images zype-<?php echo $thumbnail_layout ?>">
              <a href="<?php echo get_permalink().'?zype_wp=true&zype_type=video_single&zype_video_id='.$cont->_id ?>">
                <div class="zype-background-thumbnail" style="background-image: url(<?php echo $background_image ?>);">
                </div>
              </a>
              <div class="item_title_block"><?php echo $cont->title ?></div>
            </div>
          <?php endif ?>
        <?php endif ?>
      <?php endforeach ?>
    </div>
  </div>

  <?php if($get_all != 0): ?>
    <?php $npage=$page+1; $ppage=$page-1; ?>
    <div class="pages" style="heignt:30px; width:400px; float:top">
      <?php if($get_all == 2): ?>
        <?php for ($i = 1; $i <= ceil(\Input::get( 'zype_items', 0 ) / $per_page); $i++): ?>
          <a href="<?php echo get_permalink().'?zype_get_all='.$get_all. (\Input::get('zype_parent', 0)? '&zype_parent='.\Input::get('zype_parent', 0): '' ) . '&zype_str=' . $i . (\Input::get('zype_items', 0)? '&zype_items='.\Input::get('zype_items', 0): '' ) ?>" class="grid-paginate <?php echo (\Input::get('zype_str', 0) == $i? ' active': '' ) ?>"><?php echo $i ?></a>
        <?php endfor ?>
      <?php endif ?>
    </div>
  <?php endif ?>
</div>
