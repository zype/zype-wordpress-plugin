<?php get_header(); ?>
<div class="row guest-main">
    <div class="col-md-8">
        <div class="guest-profile-head">
            <div class="profile-holder">
                <div class="photo">
          <span data-picture data-alt="image description">
            <span data-src="<?php echo $guest->thumbnail_url; ?>"></span>
            <span data-src="<?php echo $guest->thumbnail_url; ?>" data-media="(max-width:1023px)"></span>
            <span data-src="<?php echo $guest->thumbnail_url; ?>"
                  data-media="(max-width:1023px) and (-webkit-min-device-pixel-ratio:1.5), (max-width:1023px) and (min-resolution:144dpi)"></span>
            <noscript><img src="<?php echo $guest->thumbnail_url; ?>" height="240" width="240" alt="image description"></noscript>
          </span>
                </div>
                <ul class="social-networks ss-icon">
                    <?php if ($guest->facebook != '') { ?>
                        <li><a href="<?php echo $guest->facebook; ?>" target="_blank"><i
                                        class="fa fa-fw fa-facebook-official"></i></a></li>
                    <?php } ?>
                    <?php if ($guest->twitter != '') { ?>
                        <li><a href="<?php echo $guest->twitter; ?>" target="_blank"><i class="fa fa-fw fa-twitter"></i></a>
                        </li>
                    <?php } ?>
                    <?php if ($guest->youtube != '') { ?>
                        <li><a href="<?php echo $guest->youtube; ?>" target="_blank"><i
                                        class="fa fa-fw fa-youtube-play"></i></a></li>
                    <?php } ?>
                </ul>
            </div>
            <div class="profile-des">
                <div class="clearfix"><?php the_share_buttons(); ?></div>
                <h1><?php echo $guest->title; ?></h1>
                <!--<div class="designation"></div>-->
                <!--<div class="link-holder"><a href="#">James Franco’s Link</a></div>-->
                <div><?php echo $guest->description; ?></div>
            </div>
        </div>
        <div class="ajax-area">
            <div class="recent-block" id="ajax-holder">
                <?php foreach ($videos as $video) { ?>
                    <article>
                        <div class="bg-stretch">
              <span data-picture data-alt="image description">
                <span data-src="<?php echo $video->thumbnails[0]->url; ?>"></span>
                <span data-src="<?php echo $video->thumbnails[0]->url; ?>" data-media="(max-width:1023px)"></span>
                <span data-src="<?php echo $video->thumbnails[0]->url; ?>"
                      data-media="(max-width:1023px) and (-webkit-min-device-pixel-ratio:1.5), (max-width:1023px) and (min-resolution:144dpi)"></span>
                <noscript><img src="<?php echo $video->thumbnails[0]->url; ?>" width="1442" height="400"
                               alt="image description"></noscript>
              </span>
                        </div>
                        <div class="text-col">
                            <div class="meta">
                                <time datetime="<?php echo date(DATE_W3C, strtotime($video->published_at ?: $video->created_at)); ?>"><?php formatted_time($video->published_at ?: $video->created_at); ?></time>
                            </div>
                            <h2><a href="<?php echo $video->permalink; ?>"><?php echo $video->title; ?></a></h2>
                            <p><?php echo $video->excerpt; ?></p>
                            <div class="btn-holder">
                                <a href="<?php echo $video->permalink; ?>" class="btn btn-sm btn-default">View more</a>
                            </div>
                            <!--<div class="thumb-holder">
                              <a href="#">
                                <span data-picture data-alt="image description">
                                  <span data-src = "images/thumb-01.png" ></span>
                                  <span data-src = "images/thumb-01.png" data-media = "(max-width:1023px)" ></span>
                                  <span data-src = "images/thumb01-2x.png" data-media = "(max-width:1023px) and (-webkit-min-device-pixel-ratio:1.5), (max-width:1023px) and (min-resolution:144dpi)" ></span>
                                  <noscript><img src="images/thumb-01.png" height="38" width="38" alt="image description"></noscript>
                                </span>
                              </a>
                            </div>-->
                        </div>
                        <div class="image-col">
                            <!--<a href="#" class="link-photo"></a>-->
                        </div>
                    </article>
                <?php } ?>
            </div>
            <div class="load-more"><a class="btn-load"
                                      href="<?php zype_url('video'); ?>/?search=<?php echo urlencode($guest->title); ?>">more
                    videos with <?php echo $guest->title; ?></a></div>
        </div>
    </div>
    <?php get_sidebar(); ?>
</div>
<?php get_footer(); ?>
