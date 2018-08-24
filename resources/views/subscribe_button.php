<div class="btn-holder">
  <button class="zype_get_all_ajax user-profile-wrap__button zype-join-button">
      <?php if (!\Auth::subscriber()): ?>
        <?php echo $btn_text ?>
      <?php else: ?>
        <?php echo $btn_text_after_sub ?>
      <?php endif; ?>
  </button>
</div>

<?php if (!\Auth::subscriber()): ?>
  <div class="subscribe-button" id="subscribe-button">
      <i id="zype_video__auth-close" class="fa fa-3x fa-times"></i>
      <div class="subscribe-button-content">
          <div class="login-sub-section">
              <?php if (!\Auth::logged_in()): ?>
                <?php echo do_shortcode('[zype_auth ajax=true]');?>
                <?php echo do_shortcode('[zype_signup ajax=true]');?>
                <?php echo do_shortcode('[zype_forgot]');?>
              <?php endif; ?>
                <div id="plans" style=<?php echo (\Auth::logged_in() ? '' : 'display:none;') ?>>
                  <?php
                      $shortCode = '[zype_auth type="plans" root_parent="subscribe-button"';
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
        $(document).on('click', '.zype-signin-button', function(e) {
          e.preventDefault();
          $('#zype-modal-signup').hide();
          $('#zype-modal-auth').show();
          $('#zype-modal-forgot').hide();
        });

        $(document).on('click', '.zype-join-button', function(e) {
          e.preventDefault();
          if($('#plans').css('display') === 'none') {
            $('#zype-modal-signup').show();
            $('#zype-modal-auth').hide();
            $('#zype-modal-forgot').hide();
          }
        });

        $(document).on('click', '.zype-join-button, .zype-signin-button', function() {
          $('.subscribe-button').fadeIn();
          $('.subscribe-button-content').css('top', '10%');
        });

        $(document).on('click', '#zype_video__auth-close, #zype_modal_close', function(e) {
            $('.subscribe-button-content').css('top', '-50%');
            $('.subscribe-button').fadeOut();

            if($('.close_reload').val() === 'reload') {
              location.reload();
            }
        });
      <?php else: ?>
        $(document).on('click', 'button.zype-join-button', function() {
          var url = '<?php echo $profile_url ?>';
          window.location.replace(url);
        });
      <?php endif; ?>
    });
  })(jQuery);
</script>
