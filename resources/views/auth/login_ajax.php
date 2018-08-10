<div id="zype-modal-auth">
  <div class="content-wrap zype-form-center">
    <div class="main-heading inner-heading">
      <h1 class="title zype-title">Sign in</h1>
    </div>
    <div class="user-wrap">
      <div class="holder-main">
        <div class="row">
          <div class="">
            <form action="<?php echo admin_url('admin-ajax.php') ?>" class="user-form nice-form" id="zype_login_form_ajax" method="post">
              <input type="hidden" name="action" value="zype_login_ajax">
              <div class="zype_flash_messages"></div>
              <div class="error-section"></div>
              <div class="field-section">
                <p class="form-group required-row zype-input-wrap">
                  <input name="username" type="email" class="required zype-input-text" id="email-login" placeholder="Email">
                </p>
                <p class="form-group required-row zype-input-wrap">
                  <input name="password" type="password" class="required zype-input-text" id="password-login" placeholder="Password">
                </p>
                <div class="btn-holder">
                  <button type="submit" class="zype_get_all_ajax zype-button">Sign in</button>
                </div>
              </div>
              <div class="success-section"></div>
              <div class="">
                <p class="to-forgot-password"><a href="<?php echo get_permalink() . "?zype_auth_type=forgot" ?>" class="zype_auth_markup" data-type="forgot">Forgot password?</a></p>
                <p class="to-sign-up">Don't have an account? <a href="<?php echo get_permalink() . "?zype_auth_type=register" ?>" class="zype_auth_markup" data-type="register">Sign Up</a></p>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  (function($){
    $(document).ready(function() {
      var zype_ajax_form = $("#zype_login_form_ajax");

      zype_ajax_form.ajaxForm({
        beforeSubmit: function() {
          $("#zype_login_form_ajax .zype-button").append('<i class="zype-spinner"></i>');
          $("#zype_login_form_ajax .error-section").html("");
        },
        success: function(data) {
          $('.zype-spinner').remove();
          data = $.parseJSON(data);
          if (data.status == true) {
            if(!data.is_subscribed) {
              var planDiv = $(".subscribe-button-content #plans")
              if (planDiv.length > 0) {
                planDiv.show();
                $('#zype-modal-auth').hide();
                $('#zype-modal-signup').hide();
                $('#zype-modal-forgot').hide();
              }
            }
            else {
              $('#zype_login_form_ajax').hide();
              $('#zype-modal-auth .main-heading .title').text('You\'re already subscribed!');
              $('#zype-modal-auth .holder-main .row div').html('<p class="to-sign-up">Enjoy!</p><button class="zype-button" id="alread-subscribed-btn">Let\'s starting watching</button><input type="hidden" class="close_reload" value="reload">');
            }
          } else {
            $("#zype_login_form_ajax").find('button[type="submit"]').prop('disabled', false);
            if (data.errors) {
              $("#zype_login_form_ajax").find('.error-section').html(data.errors.join(","));
            } else {
              $("#zype_login_form_ajax").find('.error-section').html('Something went wrong...');
            }
          }
        }
      });

      $(document).on('click', '#alread-subscribed-btn', function(e) {
        e.preventDefault();
        var url = '<?php echo Config::get('zype.sub_short_code_redirect_url') ?>';
        if (url) {
            window.location.replace(url);
        } else {
            window.location.reload();
        }
      });
   });
  })(jQuery);
</script>
