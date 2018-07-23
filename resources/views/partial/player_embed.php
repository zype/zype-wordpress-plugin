<?php if (!defined('ABSPATH')) die(); ?>

<?php
$auto_play_ = $auto_play ? '&autoplay=true' : '&autoplay=false';
$audio_only_ = $audio_only ? '&audio=true' : '';
$key = 'api_key=' . Themosis\Facades\Config::get('zype.player_key');
$hasUserAccessToVideo = (new ZypeMedia\Services\Access())->checkUserVideoAccess($video->_id);
if (\Auth::logged_in() && $hasUserAccessToVideo) {
    $key = 'access_token=' . \Auth::get_access_token();
}
$video_url = Themosis\Facades\Config::get('zype.playerHost') . '/embed/' . $video->_id . '.js?' . $key . $auto_play_ . $audio_only_;
?>

<div>
    <?php if ($hasUserAccessToVideo): ?>
        <div id="zype_<?php echo $video->_id; ?>"></div>
        <script src="<?php echo $video_url; ?>"></script>
    <?php else: ?>
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

            <?php endif ?>

            <?php if (\Auth::logged_in()): ?>
                <?php if ($video->subscription_required && !\Auth::subscriber()) : ?>
                    <div class="overlay_player">
                        <div class="overlay-buttons">
                            <div class="overlay-title">Unlock to watch</div>
                            <div class="white-button zype-signin-button zype_auth_markup" data-type="plans"
                                 data-root-parent="<?php echo $root_parent; ?>">Let's go
                            </div>
                        </div>
                    </div>
                <?php else: ?>
                    <img class="play-placeholder" src="<?php echo asset_url('images/play-button.png') ?>">
                <?php endif ?>
            <?php else: ?>
                <div class="overlay_player">
                    <div class="overlay-buttons">
                        <div class="overlay-title">Sign in or join to watch</div>
                        <div class="white-button zype-signin-button">Sign in</div>
                        <div class="empty-button zype-join-button">Join</div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
        <?php endif; ?>

        <div class="player-auth-required">
            <i id="zype_video__auth-close" class="fa fa-3x fa-times"></i>
            <div class="player-auth-required-content">
                <div class="login-sub-section">
                    <?php if (!\Auth::logged_in()): ?>
                        <?php echo do_shortcode('[zype_auth]'); ?>
                        <?php echo do_shortcode('[zype_signup]'); ?>
                        <?php echo do_shortcode('[zype_forgot]'); ?>
                    <?php else: ?>
                        <?php
                        $shortCode = '[zype_auth type="plans"';
                        if ($root_parent) {
                            $shortCode .= ' root_parent="' . $root_parent;
                        }
                        $shortCode .= '"]';
                        ?>
                        <?php echo do_shortcode($shortCode); ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
