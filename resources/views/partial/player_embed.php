<?php if (!defined('ABSPATH')) die(); ?>

<?php if ($audio_only): ?>
<div
    class="zype_player_container"
    data-video-id="<?php echo $video->_id; ?>"
    data-auto-play="<?php echo $auto_play ? 'true' : 'false'; ?>"
    data-auth-required="<?php echo $auth_required ? 'true' : 'false'; ?>"
    data-audio-only="<?php echo $audio_only ? 'true' : 'false'; ?>">
  
    <img class="placeholder" src="<?php echo $video->thumbnail_url; ?>">

    <div class="zype_player" id="zype_<?php echo $video->_id; ?>"></div>
<?php else: ?>
<div
    class="zype_player_container"
    data-video-id="<?php echo $video->_id; ?>"
    data-auto-play="<?php echo $auto_play ? 'true' : 'false'; ?>"
    data-auth-required="<?php echo $auth_required ? 'true' : 'false'; ?>"
    data-audio-only="<?php echo $audio_only ? 'true' : 'false'; ?>">
    <div class="zype_player">
      <div id="zype_<?php echo $video->_id; ?>"></div>
    </div>
    <img class="placeholder" src="<?php echo $video->thumbnail_url; ?>">
<?php endif ?>
    <?php if (($auth_required && !\Auth::logged_in()) || (\Auth::logged_in() && $video->subscription_required && !\Auth::subscriber())): ?>
        <div class="player-auth-required">
            <div class="player-auth-required-content">
                <div id="zype_video__auth-close">˟</div>
                <h3>This video is for subscribers only.</h3>

                <div class="login-sub-section">
                    <?php if (!\Auth::logged_in()): ?>
                        <div class="login-sub-section-title">If you are a subscriber:</div>
                        <?php echo do_shortcode('[zype_auth]');?>
                    <?php else: ?>
                        <p>If you'd like to subscribe:</p>
                        <a href="<?php echo home_url(\Config::get('zype.subscribe_url')) ?>/" class="zype_sign_in">Get Started Now</a>
                    <?php endif ?>
                </div>
            </div>
        </div>
    <?php endif ?>
    <img class="play-placeholder" src="<?php echo asset_url('images/play-button.png') ?>">
</div>
