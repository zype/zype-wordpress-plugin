<?php
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
<div class="zype_video" id='zype-video'>
  <div class="zype_video__wrapper">
    <div class="zype_video__heading">
      <h1><?php echo $video->title; ?></h1>
      <!-- <div class="zype_play_sample">
        <!?php if (zype_audio_only()): ?>
          <a href="<!?php echo get_permalink() ?>?zype_wp=true&zype_type=video_single&zype_video_id=<!?php echo $video->_id ?>" class="btn btn-lg btn-primary">Watch Video</a>
        <!?php else: ?>
          <a href="<!?php echo get_permalink() ?>?zype_wp=true&zype_type=video_single&audio=true&zype_video_id=<!?php echo $video->_id ?>" class="btn btn-lg btn-primary">Listen to Audio</a>
        <!?php endif ?>
      </div> -->
    </div>
    <?php if (zype_audio_only()): ?>
        <?php zype_player_embed($video, ['auth' => $video->subscription_required, 'auto_play' => false, 'audio_only' => true, 'root_parent' => 'zype-video']); ?>
    <?php else: ?>
        <?php zype_player_embed($video, ['auth' => $video->subscription_required, 'auto_play' => false, 'audio_only' => false, 'root_parent' => 'zype-video']); ?>
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
      <div class="summary">
          <p><?php echo $video->description; ?></p>
      </div>
    </section>
    <div class="zype_play_sample">
      <?php if(zype_audio_only()){ ?>
        <a href="<?php echo get_permalink() ?>?zype_wp=true&zype_type=video_single&zype_video_id=<?php echo $video->_id ?>" class="btn btn-lg btn-primary">Watch Video</a>
      <?php } else { ?>
        <a href="<?php echo get_permalink() ?>?zype_wp=true&zype_type=video_single&audio=true&zype_video_id=<?php echo $video->_id ?>" class="btn btn-lg btn-primary">Listen to Audio</a>
      <?php } ?>
    </div>
  <?php endif ?>
</div>
<script type="text/javascript">
(function($){
  $(document).on('click', '#zype_video__auth-close, #zype_modal_close', function(e){
      $('.player-auth-required-content').css('top', '-50%');
      $('.player-auth-required').fadeOut();

      if($('.close_reload').val() === 'reload') {
        location.reload();
      }
  });

  $(document).on('click', '.zype-signin-button', function() {
        $('#zype-modal-auth').show();
        $('#zype-modal-signup').hide();
        $('#zype-modal-forgot').hide();
  });

  $(document).on('click', '.zype-join-button', function() {
        $('#zype-modal-signup').show();
        $('#zype-modal-auth').hide();
        $('#zype-modal-forgot').hide();
  });

  $(document).on('click', '.zype-join-button, .zype-signin-button', function() {
      $('.player-auth-required').fadeIn();
      $('.player-auth-required-content').css('top', '10%');
  });
})(jQuery);
</script>
