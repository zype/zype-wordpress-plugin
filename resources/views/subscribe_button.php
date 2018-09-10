<div class="btn-holder" id="subscribe-button">
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
      <div id="subscribe-button-content">
          <div class="login-sub-section">
              <?php if (!\Auth::logged_in()): ?>
                <?php echo do_shortcode('[zype_auth root_parent="subscribe-button-content" ajax=true]');?>
                <?php echo do_shortcode('[zype_signup root_parent="subscribe-button-content" ajax=true]');?>
                <?php echo do_shortcode('[zype_forgot root_parent="subscribe-button-content" ]');?>
              <?php endif; ?>
                <div id="plans" style=<?php echo (\Auth::logged_in() ? '' : 'display:none;') ?>>
                  <?php
                      $shortCode = '[zype_auth type="plans" root_parent="subscribe-button-content"';
                      $shortCode .= '  redirect_url="' . $redirect_url;
                      $shortCode .= '"]';
                  ?>
                  <?php echo do_shortcode($shortCode);?>
                </div>
          </div>
      </div>
  </div>
<?php endif; ?>

<script type="text/javascript">
  (function($){
    $(document).ready(function(){
      <?php if (!\Auth::subscriber()): ?>
        $(document).on('click', '#subscribe-button-content .zype-signin-button', function(e) {
          e.preventDefault();
          $('#subscribe-button-content #zype-modal-signup.zype-form').hide();
          $('#subscribe-button-content #zype-modal-auth.zype-form').show();
          $('#subscribe-button-content #zype-modal-forgot.zype-form').hide();
        });

        $(document).on('click', '#subscribe-button .zype-join-button', function(e) {
          e.preventDefault();
          if($('#subscribe-button-content #plans').css('display') === 'none') {
            $('#subscribe-button-content #zype-modal-signup.zype-form').show();
            $('#subscribe-button-content #zype-modal-auth.zype-form').hide();
            $('#subscribe-button-content #zype-modal-forgot.zype-form').hide();
          }
        });

        $(document).on('click', '#subscribe-button .zype-join-button, #subscribe-button-content .zype-signin-button', function() {
          $('.subscribe-button').fadeIn();
          $('#subscribe-button-content').css('top', '10%');
        });

        $(document).on('click', '.subscribe-button #zype_video__auth-close, #subscribe-button-content #zype_modal_close', function(e) {
            $('#subscribe-button-content').css('top', '-50%');
            $('.subscribe-button').fadeOut();

            if($('.close_reload').val() === 'reload') {
              location.reload();
            }
        });
      <?php else: ?>
        $(document).on('click', '#subscribe-button button.zype-join-button', function() {
          var url = '<?php echo $profile_url ?>';
          window.location.replace(url);
        });
      <?php endif; ?>
    });
  })(jQuery);
</script>
