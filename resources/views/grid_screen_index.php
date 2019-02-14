<div class="grid-screen grid_screen-container">
    <div class="content-box grid_screen-box">
        <?php if (($items_count > 0 || $pagination) && $parent_playlist): ?>
            <h2 class="playlist-view-title"><?php echo $parent_playlist->title ?></h2>
        <?php endif ?>

        <div class="box-with-content <?php echo $pagination ? 'box-with-content-viewall' : '' ?>">
            <?php foreach ($content as $cont): ?>
                <?php
                    $id = $cont->_id;
                    $thumbnail_layout = !empty($cont->thumbnail_layout) ? $cont->thumbnail_layout : 'landscape';
                ?>
                <?php if (!$pagination): ?>
                    <?php if (preg_match('/.*Video$/', $cont->type())): ?>
                        <!-- $cont is a video -->
                        <?php
                            $thumbnail_layout = !empty($parent_playlist->thumbnail_layout) ? $parent_playlist->thumbnail_layout : 'landscape';

                            $poster_image = '';
                            if ($thumbnail_layout == 'poster' && !empty($cont->images)) {
                                foreach ($cont->images as $image) {
                                    if ($image->layout == 'poster') {
                                        $poster_image = $image->url;
                                        break;
                                    }
                                }
                            }

                            if ($poster_image) {
                                $background_image = $poster_image;
                            } else {
                                if (!empty($cont->thumbnails[0]->url)) {
                                    $background_image = $cont->thumbnails[0]->url;
                                } else {
                                    $background_image = $thumbnail_layout == 'landscape' ? asset_url('images/320x180.png') : asset_url('images/200x300.png');
                                }
                            }

                        ?>
                        <div class="view_all_images zype-<?php echo $thumbnail_layout ?>">
                            <a href="<?php echo get_permalink() . '?zype_type=video_single&zype_video_id=' . $cont->_id . '&playlist_id=' . $parent_id?>">
                                <div class="zype-background-thumbnail"
                                     style="background-image: url(<?php echo $background_image ?>);">
                                </div>
                            </a>

                            <div title="<?php echo $cont->title ?>" class="item_title_block">
                                <?php echo $cont->title ?>
                            </div>
                        </div>
                    <?php else: ?>
                        <!-- $cont is a playlist -->
                        <div class="playlist-with-content">
                            <div class="slider_links">
                                <div class="slider_links-title">
                                    <a href="<?php echo get_permalink() . '?zype_parent=' . $id?>"><?php echo $cont->title ?></a>

                                </div>
                                <div class="get-all-playlists slider_links-all">
                                    <?php if ($playlist_pagination_enabled): ?>
                                        <a href="<?php echo get_permalink() . '?pagination=true&zype_parent=' . $id?>">
                                            See all
                                        </a>
                                    <?php else: ?>
                                        <a href="<?php echo get_permalink() . '?zype_parent=' . $id?>">
                                            See all
                                        </a>
                                    <?php endif ?>
                                </div>
                            </div>
                            <div class="slider-list zype-<?php echo $thumbnail_layout ?>">
                                <?php if ($subcontent[$id]): ?>
                                    <?php foreach ($subcontent[$id] as $sub): ?>
                                        <?php
                                            $poster_image = '';
                                            if ($thumbnail_layout == 'poster' && !empty($sub->images)) {
                                                foreach ($sub->images as $image) {
                                                    if ($image->layout == 'poster') {
                                                        $poster_image = $image->url;
                                                        break;
                                                    }
                                                }
                                            }

                                            if ($poster_image) {
                                                $background_image = $poster_image;
                                            } else {
                                                if (!empty($sub->thumbnails[0]->url)) {
                                                    $background_image = $sub->thumbnails[0]->url;
                                                } else {
                                                    $background_image = $thumbnail_layout == 'landscape' ? asset_url('images/320x180.png') : asset_url('images/200x300.png');
                                                }
                                            }
                                        ?>

                                        <div class="slider_slide_first">
                                            <?php if (preg_match('/.*Playlist$/', $sub->type())): ?>
                                                <!--$sub is a playlist-->
                                                <a href="<?php echo get_permalink() . '?pagination=' . $playlist_pagination_enabled . '&zype_parent=' . $sub->_id?>">
                                                    <div class="zype-background-thumbnail"
                                                            style="background-image: url(<?php echo $background_image ?>);">
                                                    </div>
                                                </a>
                                            <?php else: ?>
                                                <!--$sub is a video-->
                                                <a href="<?php echo get_permalink() . '?zype_type=video_single&zype_video_id=' . $sub->_id . '&playlist_id=' . $id?>">
                                                    <div class="zype-background-thumbnail"
                                                            style="background-image: url(<?php echo $background_image ?>);">
                                                    </div>
                                                </a>
                                                <?php endif ?>
                                            <div title="<?php echo $sub->title ?>" class="item_title_block">
                                                <?php echo $sub->title ?>
                                            </div>
                                        </div>
                                    <?php endforeach ?>
                                <?php endif ?>
                            </div>
                        </div>

                    <?php endif ?>

                <?php else: ?>
                    <?php
                        $id = $cont->_id;
                        $items = !empty($playlist_item_count->playlist_item_count) ? $playlist_item_count->playlist_item_count : 0;
                        $thumbnail_layout = !empty($parent_playlist->thumbnail_layout) ? $parent_playlist->thumbnail_layout : 'landscape';

                        $poster_image = '';
                        if ($thumbnail_layout == 'poster' && !empty($cont->images)) {
                            foreach ($cont->images as $image) {
                                if ($image->layout == 'poster') {
                                    $poster_image = $image->url;
                                    break;
                                }
                            }
                        }

                        if ($poster_image) {
                            $background_image = $poster_image;
                        } else {
                            if (!empty($cont->thumbnails[0]->url)) {
                                $background_image = $cont->thumbnails[0]->url;
                            } else {
                                $background_image = $thumbnail_layout == 'landscape' ? asset_url('images/320x180.png') : asset_url('images/200x300.png');
                            }
                        }
                    ?>

                    <div class="view_all_images zype-<?php echo $thumbnail_layout ?>">
                        <?php if (preg_match('/.*Playlist$/', $cont->type())): ?>
                            <a href="<?php echo get_permalink() . '?pagination=' . $playlist_pagination_enabled . '&zype_parent=' . $id?>">
                                <div class="zype-background-thumbnail"
                                    style="background-image: url(<?php echo $background_image ?>);">
                                </div>
                            </a><br>
                        <?php else: ?>
                            <a href="<?php echo get_permalink() . '?zype_type=video_single&zype_video_id=' . $cont->_id . '&playlist_id=' . $parent_id?>">
                                <div class="zype-background-thumbnail"
                                    style="background-image: url(<?php echo $background_image ?>);">
                                </div>
                            </a>
                        <?php endif ?>
                        <div class="item_title_block"><?php echo $cont->title ?></div>
                    </div>
                <?php endif ?>
            <?php endforeach ?>
        </div>
    </div>

    <?php if ($pagination): ?>
        <?php $npage = $page + 1;
        $ppage = $page - 1; ?>
        <div class="pages" style="heignt:30px; width:400px; float:top">
            <?php for ($i = 1; $i <= ceil($items_count / $per_page); $i++): ?>
                <a href="<?php echo get_permalink() . '?pagination=' . $pagination . ($parent_id ? '&zype_parent=' . $parent_id : '') . '&zype_str=' . $i ?>"
                      class="grid-paginate <?php echo($page == $i ? ' active' : '') ?>"><?php echo $i ?>
                </a>
            <?php endfor ?>
        </div>
    <?php endif ?>
</div>
