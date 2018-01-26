<?php
  $guests = zype_video_zobjects('guest');
  
  $hours = floor($video->duration / 3600);
  $minutes = floor(($video->duration / 60) % 60);
  $seconds = $video->duration % 60;

  $duration = '';
  if ($hours) {
    $duration = "{$minutes}h, {$minutes}m";
  } else {
    if (!$minutes) {
      $duration = "{$seconds}s";
    } else {
      $duration = "{$minutes}m";
    }
  }
?>
<div class="zype_video">
  <div class="zype_video__wrapper">
    <div class="zype_video__heading">
      <h1><?php echo $video->title; ?></h1>
      <div class="zype_play_sample">
        <?php if (zype_audio_only()): ?>
          <a href="<?php echo get_permalink() ?>?zype_wp=true&zype_type=video_single&zype_video_id=<?php echo $video->_id ?>" class="btn btn-lg btn-primary">Watch Video</a>
        <?php else: ?>
          <a href="<?php echo get_permalink() ?>?zype_wp=true&zype_type=video_single&audio=true&zype_video_id=<?php echo $video->_id ?>" class="btn btn-lg btn-primary">Listen to Audio</a>
        <?php endif ?>
      </div>
    </div>
    <?php if (zype_audio_only()): ?>
        <?php zype_player_embed($video, ['auth' => $video->subscription_required, 'auto_play' => false, 'audio_only' => true]); ?>
    <?php else: ?>
        <?php zype_player_embed($video, ['auth' => $video->subscription_required, 'auto_play' => false, 'audio_only' => false]); ?>
    <?php endif ?>
  </div>
  <?php if ($view == 'full'): ?>
    <section class="episode-main">
      <div class="head">
        <h2><?php echo $video->title; ?></h2>
      </div>
      <div class="head">
        <h5 class="duration-title">Duration <?php echo $duration ?></h5>
      </div>
      <?php if ($guests) { ?>
      <ul class="user-list">
        <?php foreach($guests as $guest){ ?>
          <li>
            <div class="img">
              <a href="<?php echo $guest->permalink; ?>/">
                <span data-picture="" data-alt="image description">
                  <span data-src="<?php echo $guest->thumbnail_url; ?>"><img src="<?php echo $guest->thumbnail_url; ?>" alt="image description"></span>
                  <span data-src="<?php echo $guest->thumbnail_url; ?>" data-media="(max-width:1023px)"></span> <!-- retina 1x tablet -->
                  <span data-src="<?php echo $guest->thumbnail_url; ?>" data-media="(max-width:1023px) and (-webkit-min-device-pixel-ratio:1.5), (max-width:1023px) and (min-resolution:144dpi)"></span> <!-- retina 2x tablet -->
                  <noscript><img src="<?php echo $guest->thumbnail_url; ?>" height="80" width="80" alt="image description"></noscript>
                </span>
              </a>
            </div>
            <span class="username"><a href="<?php echo $guest->permalink; ?>/"><?php echo $guest->title; ?></a></span>
          </li>
        <?php } ?>
      </ul>
      <?php } ?>
      <div class="summary">
        <p><?php echo $video->description; ?></p>
        <ul class="tags">
          <?php foreach($video->keywords as $keyword){ ?>
          <li><a href="<?php zype_url('video'); ?>/?search=<?php echo $keyword; ?>" class="btn btn-sm"><?php echo $keyword; ?></a></li>
          <?php } ?>
        </ul>
      </div>
      <div class="guest-intro">
        <?php if($guests) { ?>
          <strong class="title">guest list</strong>
          <div class="listing">
            <?php foreach($guests as $guest) { ?>
              <div class="slot">
                <div class="image-holder">
                  <span data-picture="" data-alt="image description">
                    <span data-src="<?php echo $guest->thumbnail_url; ?>"><img src="<?php echo $guest->thumbnail_url; ?>" alt="image description"></span>
                    <span data-src="<?php echo $guest->thumbnail_url; ?>" data-media="(max-width:1023px)"></span> <!-- retina 1x tablet -->
                    <span data-src="<?php echo $guest->thumbnail_url; ?>" data-media="(max-width:1023px) and (-webkit-min-device-pixel-ratio:1.5), (max-width:1023px) and (min-resolution:144dpi)"></span> <!-- retina 2x tablet -->
                    <noscript><img src="<?php echo $guest->thumbnail_url; ?>" height="80" width="80" alt="image description"></noscript>
                  </span>
                </div>
                <div class="des">
                  <h2><a href="<?php echo $guest->permalink; ?>/"><?php echo $guest->title; ?></a></h2>
                  <p><?php echo $guest->description; ?></p>
                  <ul class="social-networks ss-icon">
                    <?php if($guest->facebook != ''){ ?>
                      <li><a href="<?php echo $guest->facebook; ?>" target="_blank"><i class="fa fa-fw fa-facebook-official"></i></a></li>
                    <?php } ?>
                    <?php if($guest->twitter != ''){ ?>
                      <li><a href="<?php echo $guest->twitter; ?>" target="_blank"><i class="fa fa-fw fa-twitter"></i></a></li>
                    <?php } ?>
                    <?php if($guest->youtube != ''){ ?>
                      <li><a href="<?php echo $guest->youtube; ?>" target="_blank"><i class="fa fa-fw fa-youtube-play"></i></a></li>
                    <?php } ?>
                  </ul>
                </div>
              </div>
            <?php } ?>
          </div>
        <?php } ?>
      </div>
    </section>
    <div class="zype_play_sample">
      <?php if(zype_audio_only()){ ?>
        <a href="<?php echo get_permalink() ?>?zype_wp=true&zype_type=video_single&zype_video_id=<?php echo $video->_id ?>" class="btn btn-lg btn-primary">Watch Video</a>
      <?php } else { ?>
        <a href="<?php echo get_permalink() ?>?zype_wp=true&zype_type=video_single&audio=true&zype_video_id=<?php echo $video->_id ?>" class="btn btn-lg btn-primary">Listen to Audio</a>
      <?php } ?>
    </div>
    <section class="timeline">
      <?php if(isset($video->segments)){ ?>
        <strong class="title">Timeline</strong>
        <ul class="list">
          <?php foreach($video->segments as $segment){ ?> 
            <li>
              <span class="meta"><?php zype_ms2hms($segment->start); ?></span>
              <span class="text"><?php echo $segment->description; ?></span>
            </li>
          <?php } ?>
        </ul>
      <?php } ?>
    </section>
  <?php endif ?>
</div>
<script type="text/javascript">
(function($){
  $(document).on('click', function(e){
    if ($(e.target).is('.player-auth-required')) {
      var modal = $('.player-auth-required');
      modal.removeClass('zype_modal_open');
      $('body').css('overflow-y', 'auto');
      zype_wp.zypeAuthMarkupRequest('login');
      setTimeout(function(){
        modal.find('.player-auth-required-content').css('top', '-50%');
      },10);
    }
  });
  
  $(document).on('click', '#zype_video__auth-close', function(e){
    e.preventDefault();
    var modal = $(this).closest('.player-auth-required');
    modal.removeClass('zype_modal_open');
    $('body').css('overflow-y', 'auto');
    zype_wp.zypeAuthMarkupRequest('login');
    setTimeout(function(){
      modal.find('.player-auth-required-content').css('top', '-50%');
    },10);
  });
  
  $(document).on('click', '.zype_player_container > img.placeholder, .zype_player_container > img.play-placeholder', function(e) {
    e.preventDefault();
    if ($(this).closest('.zype_player_container').find('.player-auth-required').length != 0) {
      var modal = $(this).closest('.zype_player_container').find('.player-auth-required');
      modal.addClass('zype_modal_open');
      $('body').css('overflow-y', 'hidden');
      setTimeout(function(){
        modal.find('.player-auth-required-content').css('top', '20px');
      },10);
    }
  })
})(jQuery); 
</script>


