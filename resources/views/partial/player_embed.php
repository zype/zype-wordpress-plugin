<?php if (!defined('ABSPATH')) die(); ?>

<?php
    $auto_play_ = $auto_play ? '&autoplay=true' : '&autoplay=false';
    $audio_only_ = $audio_only ? '&audio=true' : '';
    $key = 'api_key=' . $options['player_key'];
    $has_access_to_video = (new ZypeMedia\Services\Access())->checkUserVideoAccess($video->_id, $playlist_id);
    if (\Auth::logged_in() && $has_access_to_video) {
        $key = 'access_token=' . \Auth::get_access_token();
    }
    $video_url = $options['playerHost'] . '/embed/' . $video->_id . '.js?' . $key . $auto_play_ . $audio_only_;
    $preview_video_url = '';
    if (count($video->preview_ids) === 1) {
        $preview_video_url = $options['playerHost'] . '/embed/' . $video->preview_ids[0] . '.js?' . $key . '&autoplay=false';
    }
?>

<script src="https://resources.zype.com/player/<?php echo $theo_player_version;?>/zypeplayer.js"></script>

<div>
    <?php if ($has_access_to_video): ?>
        <div class="video-player">
            <div id="zype_<?php echo $video->_id; ?>"></div>
            <script src="<?php echo $video_url; ?>"></script>
        </div>
        <?php if (count($video->preview_ids) === 1) : ?>
            <div class="preview-video-player">
                <div id="zype_<?php echo $video->preview_ids[0]; ?>"></div>
                <script src="<?php echo $preview_video_url; ?>"></script>
            </div>
        <?php endif ?>
    <?php else: ?>
        <?php if ($audio_only): ?>
            <div
                class="zype_player_container"
                data-video-id="<?php echo $video->_id; ?>"
                data-auto-play="<?php echo $auto_play ? 'true' : 'false'; ?>"
                data-auth-required="<?php echo $auth_required ? 'true' : 'false'; ?>"
                data-audio-only="<?php echo $audio_only ? 'true' : 'false'; ?>">
                <img class="placeholder" src="<?php echo $video->thumbnail_url; ?>">
                <div class="zype_player" id="zype_<?php echo $video->_id; ?>">
            </div>
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
            <?php if (\Auth::logged_in()): ?>
                <?php if (!$has_access_to_video) : ?>
                    <div class="overlay_player">
                        <div class="overlay-buttons">
                            <div class="overlay-title">Unlock to watch</div>
                            <div class="white-button zype-signin-button">
                                Let's go
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
        <?php if (count($video->preview_ids) === 1) : ?>
            <div class="preview-video-player">
                <div id="zype_<?php echo $video->preview_ids[0]; ?>"></div>
                <script src="<?php echo $preview_video_url; ?>"></script>
            </div>
        <?php endif ?>
    <?php endif; ?>

    <div class="player-auth-required zype-custom-modal">
        <i id="zype_video__auth-close" class="fa fa-3x fa-times"></i>
        <div class="player-auth-required-content">
            <div class="login-sub-section">
                <?php if (!\Auth::logged_in()): ?>
                    <?php echo do_shortcode(ajax_shortcode('zype_auth', ['root_parent' => $root_parent, 'redirect_url' => $redirect_url])); ?>
                    <?php echo do_shortcode(ajax_shortcode('zype_signup', ['root_parent' => $root_parent, 'redirect_url' => $redirect_url])); ?>
                    <?php echo do_shortcode(ajax_shortcode('zype_forgot', ['root_parent' => $root_parent])); ?>
                <?php elseif (!$has_access_to_video): ?>
                    <?php
                        $short_code = ajax_shortcode('zype_video_checkout', [
                            'root_parent'   => $root_parent,
                            'redirect_url'  => $redirect_url,
                            'type'          => 'paywall',
                            'video_id'     => esc_attr($video->_id),
                            'playlist_id'     => esc_attr($playlist_id),
                            'object_type'   => 'video'
                        ]);
                        echo do_shortcode($short_code);
                    ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
    (function($){
        var videoIdSelector = "#zype-video-<?php echo $video->_id; ?>"
        var rootParent = "#<?php echo $root_parent; ?>";
        var playTrailerButtonSelector = rootParent + ' section.episode-main .head .play-trailer-button button';
        var previewPlayerSelector = rootParent + ' .preview-video-player';

        var videoPlayerSelect = rootParent + ' div.video-player';
        var videoPlayButtonSelector = rootParent + ' div.video-player .vjs-play-control.vjs-control.vjs-button';
        var videoUnlockSelector = rootParent + ' .zype_player_container';
        var previewPlayButtonSelector = '.preview-video-player .vjs-play-control.vjs-control.vjs-button';
        var previewVideoUrl = "<?php echo $preview_video_url; ?>";
        var closePreviewButton = "<i class='fa fa-2x fa-times video-preview-close-button-sandbox'></i>";
        var playingTrailer = false;
        $(previewPlayerSelector).css('display', 'none');

        // Listener for when the user open the trailer
        $(document).on('click', playTrailerButtonSelector, function(e) {
            if(playingTrailer) return;
            playingTrailer = true;
            $(previewPlayerSelector).css('display', 'block');
            $(videoUnlockSelector).css('display', 'none');
            $(videoPlayerSelect).css('display', 'none');
            $(rootParent + '.zype_video .zype_video__heading').append(closePreviewButton);
            if(!($(previewPlayButtonSelector).hasClass('vjs-playing'))) $(previewPlayButtonSelector).click();
            if(($(videoPlayButtonSelector).hasClass('vjs-playing'))) $(videoPlayButtonSelector).click();

        });

        // Listener for when the user close the trailer
        $(document).on('click', rootParent + '.zype_video .zype_video__heading .video-preview-close-button-sandbox', function(e) {
            playingTrailer = false;
            $(previewPlayerSelector).css('display', 'none');
            $(videoUnlockSelector).css('display', 'block');
            $(videoPlayerSelect).css('display', 'block');
            if($(previewPlayButtonSelector).hasClass('vjs-playing')) $(previewPlayButtonSelector).click();
            $(rootParent + '.zype_video .zype_video__heading .video-preview-close-button-sandbox').remove();
        });
    })(jQuery);
</script>
