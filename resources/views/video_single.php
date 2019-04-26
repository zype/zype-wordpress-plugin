<?php
    $id = 'zype-video-' . $video->_id . '-' . (time() * rand(1, 1000000));
    $hours = floor($video->duration / 3600);
    $minutes = floor(($video->duration / 60) % 60);
    $seconds = $video->duration % 60;

    $duration = '';
    if ($hours >= 1) {
        $duration = "{$hours}h, {$minutes}m";
    } else {
        if (!$minutes) {
            $duration = "{$seconds}s";
        } else {
            $duration = "{$minutes}m";
        }
    }
?>
<div class="zype_video" id="<?php echo $id; ?>">
    <div class="zype_video__wrapper">
        <div class="zype_video__heading">
            <h1><?php echo $video->title; ?></h1>
        </div>
        <?php
            zype_player_embed(
                $video,
                [
                    'auth' => $video->transaction_required,
                    'playlist_id' => $playlist_id,
                    'auto_play' => false,
                    'audio_only' => zype_audio_only(),
                    'root_parent' => $id,
                    'redirect_url' => $redirect_url
                ]
            );
        ?>
    </div>
    <?php if ($view == 'full'): ?>
        <section class="episode-main">
            <div class="head">
                <h2><?php echo $video->title; ?></h2>
                <?php if (count($video->preview_ids) === 1) : ?>
                    <div class="play-trailer-button">
                        <button class="zype-btn-container-plan" type="button">Play Trailer</button>
                    </div>
                <?php endif ?>
            </div>
            <div class="head">
                <h5 class="duration-title">Duration <?php echo $duration ?></h5>
            </div>
            <div class="summary">
                <p><?php echo $video->description; ?></p>
            </div>
        </section>
        <div class="zype_play_sample">
            <?php if (zype_audio_only()) { ?>
                <a href="<?php echo get_permalink() ?>?zype_wp=true&zype_type=video_single&zype_video_id=<?php echo $video->_id ?>"
                   class="btn btn-lg btn-primary">Watch Video</a>
            <?php } else { ?>
                <a href="<?php echo get_permalink() ?>?zype_wp=true&zype_type=video_single&audio=true&zype_video_id=<?php echo $video->_id ?>"
                   class="btn btn-lg btn-primary">Listen to Audio</a>
            <?php } ?>
        </div>
    <?php endif ?>
</div>
<script type="text/javascript">
    (function($){
        var id = "#<?php echo $id; ?>"
        $(document).on('click', id + ' #zype_video__auth-close, ' +  id + ' #zype_modal_close', function(e) {
            $('.player-auth-required-content').css('top', '-50%');
            $('.player-auth-required').fadeOut();
            $('body').css('overflow', '');

            if ($('.close_reload').val() === 'reload') {
                location.reload();
            }
        });

        $(document).on('click', id + ' .zype-signin-button', function() {
            $(id + ' #zype-modal-auth').show();
            $(id + ' #zype-modal-signup').hide();
            $(id + ' #zype-modal-forgot').hide();
        });

        $(document).on('click', id + ' .zype-join-button', function() {
            $(id + ' #zype-modal-signup').show();
            $(id + ' #zype-modal-auth').hide();
            $(id + ' #zype-modal-forgot').hide();
        });

        $(document).on('click', id + ' .zype-join-button, ' + id + ' .zype-signin-button', function() {
            $(id + ' .player-auth-required').fadeIn();
            $(id + ' .player-auth-required-content').css('top', '10%');
            $('body').css('overflow', 'hidden');
        });
    })(jQuery);
</script>
