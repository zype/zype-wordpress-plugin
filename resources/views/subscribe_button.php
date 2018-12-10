<div id="<?php echo $subscription_shortcode_id; ?>">
  <div class="btn-holder" id="<?php echo $subscribe_button_id; ?>">
    <button class="zype_get_all_ajax user-profile-wrap__button zype-join-button zype-custom-button">
        <?php if (!\Auth::subscriber()): ?>
          <?php echo $btn_text ?>
        <?php else: ?>
          <?php echo $btn_text_after_sub ?>
        <?php endif; ?>
    </button>
  </div>

  <?php if (!\Auth::subscriber()): ?>
    <div class="subscribe-button zype-custom-modal">
        <i id="zype_video__auth-close" class="fa fa-3x fa-times"></i>
        <div class="subscribe-button-content" id="<?php echo $content_id; ?>">
            <div class="login-sub-section">
                <?php if (!\Auth::logged_in()): ?>
                    <?php echo do_shortcode($shortcodes['login']);?>
                    <?php echo do_shortcode($shortcodes['sign_up']);?>
                    <?php echo do_shortcode($shortcodes['forgot_pass']);?>
                <?php endif; ?>
                  <div id="plans" style=<?php echo (\Auth::logged_in() ? '' : 'display:none;') ?>>
                    <?php echo do_shortcode($shortcodes['plans']);?>
                  </div>
            </div>
        </div>
    </div>
  <?php endif; ?>
</div>

<script type="text/javascript">
  (function($){
    var subscriptionShortcodeId = "#<?php echo $subscription_shortcode_id; ?>";
    var subscribeButtonId = "#<?php echo $subscribe_button_id; ?>";
    var subscribeButtonContentId = "#<?php echo $content_id; ?>";

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
