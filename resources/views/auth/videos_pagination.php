<?php
    $my_library_sign_in_btn_id = 'my-library-sign-in-btn-' . (time() * rand(1, 1000000));
    $my_library_sign_in_content_id = 'my-library-sign-in-button-content-' . (time() * rand(1, 1000000))
?>

<div class="my-library grid_screen-container" id="<?php echo $my_library_container_id ?>">
    <?php if (!\Auth::logged_in()): ?>
        <div class="btn-holder" id="<?php echo $my_library_sign_in_btn_id; ?>">
            <button class="zype_get_all_ajax user-profile-wrap__button zype-join-button">
                <?php echo $sign_in_text ?>
            </button>
        </div>
        <div class="my-library-sign-in-button">
            <i id="zype_video__auth-close" class="fa fa-3x fa-times"></i>
            <div class="my-library-sign-in-button-content" id="<?php echo $my_library_sign_in_content_id; ?>">
                <div class="login-sub-section">
                    <?php echo do_shortcode($shortcodes['login']);?>
                    <?php echo do_shortcode($shortcodes['sign_up']);?>
                    <?php echo do_shortcode($shortcodes['forgot_pass']);?>
                </div>
            </div>
        </div>
    <?php else: ?>
        <div class="content-box grid_screen-box">
            <?php foreach ($videos as $video): ?>
                <?php
                    if (!empty($video->thumbnails[0]->url)) {
                        $background_image = $video->thumbnails[0]->url;
                    } else {
                        $background_image = asset_url('images/320x180.png');
                    }
                ?>
                <div class="view_all_images zype-landscape">
                    <a href="<?php echo get_permalink() . '?shortcode=zype_my_library&zype_type=video_single&zype_video_id=' . $video->_id ?>">
                        <div class="zype-background-thumbnail"
                                style="background-image: url(<?php echo $background_image ?>);">
                        </div>
                    </a>
                    <div class="item_title_block"><?php echo $video->title ?></div>
                </div>
            <?php endforeach ?>
            <?php if (isset($pagination) && $pagination && ($pagination->previous || $pagination->next)): ?>
                <?php
                    $big = 999999999; // need an unlikely integer

                    $paginate_array = paginate_links( array(
                        'format' => '?page_number=%#%',
                        'end_size' => 1,
                        'current' => $pagination->current,
                        'mid_size' => 2,
                        'prev_text' => '«',
                        'next_text' => '»',
                        'total' => $pagination->last
                    ) );
                ?>
                <nav class="navigation">
                    <?php
                        echo $paginate_array;
                    ?>
                </nav>
            <?php endif ?>
        </div>
    <?php endif; ?>
</div>

<script type="text/javascript">
    (function($){
        var myLibraryContainerId = "#<?php echo $my_library_container_id; ?>";
        var myLibrarySignInBtnId = "#<?php echo $my_library_sign_in_btn_id; ?>";
        var myLibrarySignInBtnContentId = "#<?php echo $my_library_sign_in_content_id; ?>";

        var zypeJoinButtonPath = myLibraryContainerId + ' ' + myLibrarySignInBtnId + ' .zype-join-button';
        var zypeSignInButtonPath = myLibraryContainerId + ' ' + myLibrarySignInBtnContentId + ' .zype-signin-button';
        var zypeModalSignupPath = myLibraryContainerId + ' ' + myLibrarySignInBtnContentId + ' #zype-modal-signup.zype-form';
        var zypeModalAuthPath = myLibraryContainerId + ' ' + myLibrarySignInBtnContentId + ' #zype-modal-auth.zype-form';
        var zypeModalForgotPath = myLibraryContainerId + ' ' + myLibrarySignInBtnContentId + ' #zype-modal-forgot.zype-form';
        var zypeCloseButtonPath = myLibraryContainerId + ' .my-library-sign-in-button #zype_video__auth-close';
        var zypeMyLibrarySignInButtonPath = myLibraryContainerId + ' .my-library-sign-in-button';

        $(document).ready(function() {
            $(document).on('click', zypeSignInButtonPath, function(e) {
                e.preventDefault();
                $(zypeModalSignupPath).hide();
                $(zypeModalAuthPath).show();
                $(zypeModalForgotPath).hide();
            });

            $(document).on('click', zypeJoinButtonPath, function(e) {
                e.preventDefault();
                $(zypeModalSignupPath).hide();
                $(zypeModalAuthPath).show();
                $(zypeModalForgotPath).hide();
            });

            $(document).on('click', zypeJoinButtonPath + ', ' + zypeSignInButtonPath, function() {
                $(zypeMyLibrarySignInButtonPath).fadeIn();
                $(myLibrarySignInBtnContentId).css('top', '10%');
            });

            $(document).on('click', zypeCloseButtonPath, function(e) {
                $(myLibrarySignInBtnContentId).css('top', '-50%');
                $('.my-library-sign-in-button').fadeOut();

                if($('.close_reload').val() === 'reload') {
                    location.reload();
                }
            });
        });
    })(jQuery);
</script>
