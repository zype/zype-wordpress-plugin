<?php
  $subscriptionShortcodeId = 'subscribe-shortcode-' . (time() * rand(1, 1000000));
  $subscribeButtonId = 'subscribe-button-' . (time() * rand(1, 1000000));
  $contentId = 'subscribe-button-content-' . (time() * rand(1, 1000000))
?>

<div id="<?php echo $subscriptionShortcodeId; ?>">
  <div class="btn-holder" id="<?php echo $subscribeButtonId; ?>">
    <button class="zype_get_all_ajax user-profile-wrap__button zype-join-button">
        <?php if (!\Auth::subscriber()): ?>
          <?php echo $btn_text ?>
        <?php else: ?>
          <?php echo $btn_text_after_sub ?>
        <?php endif; ?>
    </button>
  </div>

  <?php if (!\Auth::subscriber()): ?>
    <div class="subscribe-button">
        <i id="zype_video__auth-close" class="fa fa-3x fa-times"></i>
        <div class="subscribe-button-content" id="<?php echo $contentId; ?>">
            <div class="login-sub-section">
                <?php if (!\Auth::logged_in()): ?>
                  <?php echo do_shortcode('[zype_auth root_parent="' . $contentId . '" ajax=true]');?>
                  <?php echo do_shortcode('[zype_signup root_parent="' . $contentId . '" ajax=true]');?>
                  <?php echo do_shortcode('[zype_forgot root_parent="' . $contentId . '" ]');?>
                <?php endif; ?>
                  <div id="plans" style=<?php echo (\Auth::logged_in() ? '' : 'display:none;') ?>>
                    <?php
                        $shortCode = '[zype_auth type="plans"';
                        $shortCode .= ' root_parent="' . $contentId . '"';
                        $shortCode .= ' redirect_url="' . $redirect_url;
                        $shortCode .= '"]';
                    ?>
                    <?php echo do_shortcode($shortCode);?>
                  </div>
            </div>
        </div>
    </div>
  <?php endif; ?>
</div>

<script type="text/javascript">
  (function($){
    var subscriptionShortcodeId = "#<?php echo $subscriptionShortcodeId; ?>";
    var subscribeButtonId = "#<?php echo $subscribeButtonId; ?>";
    var subscribeButtonContentId = "#<?php echo $contentId; ?>";

    var zypeJoinButtonPath = subscriptionShortcodeId + ' ' + subscribeButtonId + ' .zype-join-button';
    var zypeSignInButtonPath = subscriptionShortcodeId + ' ' + subscribeButtonContentId + ' .zype-signin-button';
    var zypePlansPath = subscriptionShortcodeId + ' ' + subscribeButtonContentId + ' #plans';
    var zypeModalSignupPath = subscriptionShortcodeId + ' ' + subscribeButtonContentId + ' #zype-modal-signup.zype-form';
    var zypeModalAuthPath = subscriptionShortcodeId + ' ' + subscribeButtonContentId + ' #zype-modal-auth.zype-form';
    var zypeModalForgotPath = subscriptionShortcodeId + ' ' + subscribeButtonContentId + ' #zype-modal-forgot.zype-form';
    var zypeCloseButtonPath = subscriptionShortcodeId + ' .subscribe-button #zype_video__auth-close';
    var zypeSubscribeButtonPath = subscriptionShortcodeId + ' .subscribe-button';

    $(document).ready(function(){
      <?php if (!\Auth::subscriber()): ?>
        $(document).on('click', zypeSignInButtonPath, function(e) {
          e.preventDefault();
          $(zypeModalSignupPath).hide();
          $(zypeModalAuthPath).show();
          $(zypeModalForgotPath).hide();
        });


        $(document).on('click', zypeJoinButtonPath, function(e) {
          e.preventDefault();
          if($(zypePlansPath).css('display') === 'none') {
            $(zypeModalSignupPath).show();
            $(zypeModalAuthPath).hide();
            $(zypeModalForgotPath).hide();
          }
        });

        $(document).on('click', zypeJoinButtonPath + ', ' + zypeSignInButtonPath, function() {
          $(zypeSubscribeButtonPath).fadeIn();
          $(subscribeButtonContentId).css('top', '10%');
        });

        $(document).on('click', zypeCloseButtonPath + ', ' + subscribeButtonContentId + ' #zype_modal_close', function(e) {
            $(subscribeButtonContentId).css('top', '-50%');
            $('.subscribe-button').fadeOut();

            if($('.close_reload').val() === 'reload') {
              location.reload();
            }
        });
      <?php else: ?>
        $(document).on('click', zypeJoinButtonPath, function() {
          var url = '<?php echo $profile_url ?>';
          window.location.replace(url);
        });
      <?php endif; ?>
    });
  })(jQuery);
</script>
