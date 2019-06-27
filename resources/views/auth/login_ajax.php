<div id="zype-modal-auth" class='zype-form'>
  <div class="content-wrap zype-form-center">
    <div class="main-heading inner-heading">
      <h1 class="title zype-title zype-custom-title">Sign in</h1>
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
                  <button type="submit" class="zype_get_all_ajax zype-button zype-custom-button">Sign in</button>
                </div>
              </div>
              <div class="success-section"></div>
              <div class="">
                <p class="to-forgot-password">
                  <a href="<?php echo get_permalink() . "?zype_auth_type=forgot" ?>" class="zype_auth_markup" data-type="forgot" data-root-parent-id="<?php echo $root_parent ?>">Forgot password?</a>
                </p>
                <p class="to-sign-up">
                  Don't have an account?
                  <a href="<?php echo get_permalink() . "?zype_auth_type=register" ?>" class="zype_auth_markup" data-type="register" data-root-parent-id="<?php echo $root_parent ?>">
                    Sign Up
                  </a>
                </p>
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
      var rootParentId = "#<?php echo $root_parent ?>";
      var showPlans = "<?php echo $show_plans ?>" === "true" ? true : false;
      var zypeAjaxFormPath = [rootParentId, "#zype_login_form_ajax"].join(' ');;
      var zypeSubmitButtonFormPath = [zypeAjaxFormPath, '.zype-button'].join(' ');
      var zypeErrorSectionFormPath = [zypeAjaxFormPath, '.error-section'].join(' ');
      var zypeSpinnerFormPath = [zypeAjaxFormPath, '.zype-spinner'].join(' ');

      var zypeAjaxForm = $(zypeAjaxFormPath);

      zypeAjaxForm.ajaxForm({
        beforeSubmit: function() {
          $(zypeSubmitButtonFormPath).append('<div class="zype-spinner"></div>');
          $(zypeErrorSectionFormPath).html("");
        },
        success: function(data) {
          $(zypeSpinnerFormPath).remove();
          data = $.parseJSON(data);
          if (data.status == true) {
            if(!data.is_subscribed && showPlans) {
              var planDiv = $(rootParentId + ".subscribe-button-content #plans")
              if (planDiv.length > 0) {
                planDiv.show();
                $('.zype-form').hide();
              }
            }
            else {
              $(zypeAjaxFormPath).hide();
              var afterSignInText = 'You\'re already subscribed!';
              if(!showPlans) {
                afterSignInText = 'Now you can start enjoying your content!';
              }
              $(rootParentId).find('#zype-modal-auth .main-heading .title').text(afterSignInText);
              $(rootParentId).find('#zype-modal-auth .holder-main .row div').html('<p class="to-sign-up">Enjoy!</p><button class="zype-button zype-custom-button" id="already-subscribed-btn">Let\'s starting watching</button><input type="hidden" class="close_reload" value="reload">');
            }
          } else {
            $(zypeAjaxFormPath).find('button[type="submit"]').prop('disabled', false);
            if (data.errors) {
              $(zypeErrorSectionFormPath).html(data.errors.join("<br>"));
            } else {
              $(zypeErrorSectionFormPath).html('Something went wrong...');
            }
          }
        }
      });

      $(document).on('click', rootParentId + ' #zype-modal-auth' + ' #already-subscribed-btn', function(e) {
        e.preventDefault();
        var url = '<?php echo $redirect_url ?>';
        if (url.length > 0) {
            window.location.replace(url);
        } else {
            window.location.reload();
        }
      });
   });
  })(jQuery);
</script>
