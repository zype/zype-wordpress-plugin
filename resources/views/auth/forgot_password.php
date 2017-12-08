<div class="content-wrap">
  <div class="user-wrap">
    <div class="stat-whole">
      <div class="subscribe-sub">
        <p></p>
        <p>Don’t have an account yet? <a href="<?php echo get_permalink() . "?zype_auth_type=register" ?>" class="zype_auth_markup" data-type="register">Sign Up</a></p>
        <p>Already have an account, <a href="<?php echo get_permalink() . "?zype_auth_type=login" ?>" class="zype_auth_markup" data-type="login">Login</a></p>
      </div>
    </div>
    <div class="main-heading inner-heading">
      <strong class="title text-uppercase">Forgot Password?</strong>
    </div>
    <div class="forget-note">Enter your email address to reset your password</div>
    <form action="<?php echo admin_url('admin-ajax.php') ?>" class="user-form forget-form nice-form zype_ajax_form" method="post">
      <input type="hidden" name="action" value="zype_forgot_password">
      <fieldset>
        <div class="success-section"></div>
        <div class="error-section"></div>
        <div class="zype_flash_messages"></div>
        <div class="field-section">
          
          <div class="form-group required-row">
            <label for="email" class="text-uppercase">Email</label>
            <input type="email" class="form-control required-email" required="required" id="email" name="email">
          </div>

          <div class="btn-holder">
            <input type="submit" value="Reset Password" class="btn btn-primary">
          </div>
        
        </div>
      </fieldset>
    </form>
  </div>
</div>