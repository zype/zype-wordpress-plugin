  <div class="content-wrap zype_login-wrapper">
    <div class="main-heading inner-heading zype_heading">
      <strong class="title text-uppercase">Log in to Zype</strong>
    </div>
    <div class="user-wrap zype_form__login">
      <form id="zype_login_form_ajax" action="<?php echo get_site_url()?>/login_ajax/" class="user-form nice-form" method="post">
        <div class="zype_login__form-fields">
          <input type="hidden" name="action" value="zype_login">
          <div class="zype_flash_messages"></div>
          <div class="error-section"></div>
          <div class="zype_form-group required-row">
            <input name="username" type="email" class="form-control required-email" id="email" placeholder="Email">
          </div>
          <div class="zype_form-group required-row">
            <input name="password" type="password" class="form-control required" id="password" placeholder="Password">
          </div>
          <div class="flex-nowrap">
            <div class="check-group check-login">
              <label for="check-1">
                <input name="remember_me" id="check-1" type="checkbox">
                <span class="fake-input"></span>
                <span class="fake-label"><strong>Remember me</strong></span>
              </label>
            </div>
            <div class="zype_login__form-spacer">|</div>
            <a class="zype_form__link" href="<?php echo zype_url('profile'); ?>/forgot-password/">Forgot your password?</a>
          </div>
          <div class="zype_form__submit">
            <input type="submit" class="btn btn-primary" value="Login">
          </div>
          <div class="success-section"></div>
          <p>Don’t have an account yet? <a class="zype_form__link" href="<?php echo zype_url('signup'); ?>/">Sign Up</a></p>
        </div>
      </form>
      <div class="zype_login__divider"><span>OR</span></div>
      <div class="zype_form__login-social">
        <div class="zype_flash_messages_social"></div>
        <?php do_action('wordpress_social_login'); ?>
      </div>
    </div>
  </div>
  <script> 
  (function($){
    $(document).ready(function() { 
     $("#zype_login_form_ajax").ajaxForm({
      beforeSubmit: function() {
       $("#zype_login_form_ajax .error-section").html("");
     },
     success: function(data) {
       data = $.parseJSON(data);

       if(data.status == true)
        location.reload();
      else 
        $("#zype_login_form_ajax .error-section").html(data.errors.join(","));
    }
  });
   });
})(jQuery); 
 </script>
