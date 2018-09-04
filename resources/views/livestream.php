<?php
$merches = get_recent_merch(6);
$videos = get_recent_videos(3);
?>
<?php get_header(); ?>
</div>

<section class="video-slide">
    <div class="container">
        <div class="col-md-8">
            <?php if ($livestream) { ?>
                <?php zype_player_embed($livestream, ['auth' => $zype_livestream_auth_required, 'auto_play' => false, 'audio_only' => zype_audio_only()]); ?>
            <?php } else { ?>
                <div class="off-air">
                    <div class="off-air-image"
                         style="background-image:url('<?php echo asset_url('images/blur.jpg') ?>');"></div>
                    <h2>We're currently off-air.</h2>
                    <p>There is no live show at this time. While you wait, <a href="<?php zype_url('video'); ?>/">check
                            out the show archives.</a></p>
                </div>
            <?php } ?>
        </div>
    </div>
</section>

<div class="container">
    <div class="container1">
        <div class="live-container">
            <div class="row">

                <section class="col-sm-6 col-left">
                    <h2>
                        <center>Watch <br> Archived Shows</center>
                    </h2>
                    <ul class="archive-list">
                        <?php if ($videos): ?>
                            <?php foreach ($videos as $video) { ?>
                                <li>
                                    <div class="image-holder">
                <span data-picture data-alt="image description">
                  <span data-src="<?php echo $video->thumbnail_url; ?>"></span>
                  <span data-src="<?php echo $video->thumbnail_url; ?>" data-media="(max-width:1023px)"></span>
                    <!-- retina 1x mobile -->
                  <span data-src="<?php echo $video->thumbnail_url; ?>"
                        data-media="(max-width:1023px) and (-webkit-min-device-pixel-ratio:1.5), (max-width:1023px) and (min-resolution:144dpi)"></span>
                    <!-- retina 2x tablet -->
                  <span data-src="<?php echo $video->thumbnail_url; ?>" data-media="(max-width:767px)"></span>
                    <!-- retina 1x mobile -->
                  <span data-src="<?php echo $video->thumbnail_url; ?>"
                        data-media="(max-width:767px) and (-webkit-min-device-pixel-ratio:1.5), (max-width:767px) and (min-resolution:144dpi)"></span>
                    <!-- retina 2x mobile -->
                  <noscript><img src="<?php echo $video->thumbnail_url; ?>" height="157" width="248"
                                 alt="image description"></noscript>
                </span>
                                    </div>
                                    <div class="des">
                                        <strong class="title"><?php if ($video->episode) { ?>Episode <?php echo $video->episode;
                                            } ?></strong>
                                        <h2><a href="<?php echo $video->permalink; ?>"><?php echo $video->title; ?></a>
                                        </h2>
                                    </div>
                                </li>
                            <?php } ?>
                        <?php endif ?>
                    </ul>
                    <div class="btn-holder">
                        <a href="<?php echo zype_url('video'); ?>/" class="btn btn-sm btn-primary">View all Archived
                            Shows</a>
                    </div>
                </section>

                <section class="col-sm-6 merchandise-col">
                    <h2>
                        <center>Channel <br> Merchandise</center>
                    </h2>
                    <ul class="list">
                        <?php foreach ($merches as $merch) { ?>
                            <li>
                                <a href="<?php the_field('store_url', $merch->ID); ?>">
                <span data-picture data-alt="image description">
                  <span data-src="<?php the_post_thumbnail_url($merch->ID); ?>"></span>
                  <span data-src="<?php the_post_thumbnail_url($merch->ID); ?>" data-media="(max-width:1023px)"></span>
                    <!-- retina 1x tablet -->
                  <span data-src="<?php the_post_thumbnail_url($merch->ID); ?>"
                        data-media="(max-width:1023px) and (-webkit-min-device-pixel-ratio:1.5), (max-width:1023px) and (min-resolution:144dpi)"></span>
                    <!-- retina 2x tablet -->
                  <noscript><img src="<?php the_post_thumbnail_url($merch->ID); ?>" height="185" width="170"
                                 alt="image description"></noscript>
                </span>
                                </a>
                            </li>
                        <?php } ?>
                    </ul>
                </section>

            </div>
        </div>
    </div>
</div>
<?php get_footer(); ?>
