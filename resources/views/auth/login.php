<div class="content-wrap">
  <div class="main-heading inner-heading">
    <strong class="title text-uppercase">Sign In</strong>
  </div>
  <div class="user-wrap">
    <div class="holder-main">
      <div class="row">
        <div class="col-sm-6">
          <div class="zype_flash_messages_social"></div>
          <?php do_action('wordpress_social_login'); ?>
          <div class="stat-whole">
            <div class="subscribe-sub">
              <p>Don’t have an account yet? <a href="<?php echo get_permalink() . "?zype_auth_type=register" ?>" class="zype_auth_markup" data-type="register">Sign Up</a></p>
              <p>Forgot your password? <a href="<?php echo get_permalink() . "?zype_auth_type=forgot" ?>" class="zype_auth_markup" data-type="forgot">Click Here</a></p>
            </div>
          </div>
        </div>
        <div class="col-sm-6">
          <form action="<?php echo admin_url('admin-ajax.php') ?>" class="user-form nice-form zype_ajax_form" method="post">
            <input type="hidden" name="action" value="zype_login">
            <fieldset>
              <div class="zype_flash_messages"></div>
              <div class="error-section"></div>
              <div class="field-section">
                <div class="form-group required-row">
                  <label class="text-uppercase" for="email">email</label>
                  <input name="username" type="email" class="form-control required-email" id="email">
                </div>
                <div class="form-group required-row">
                  <label class="text-uppercase" for="password">password</label>
                  <input name="password" type="password" class="form-control required" id="password">
                </div>
                <div class="check-group check-login">
                  <label for="check-1">
                    <input name="remember_me" id="check-1" type="checkbox">
                    <span class="fake-input"></span>
                    <span class="fake-label text-uppercase"><strong>remember me</strong></span>
                  </label>
                </div>
                <div class="btn-holder">
                  <input type="submit" class="btn btn-primary" value="Login">
                </div>
              </div>
              <div class="success-section"></div>
            </fieldset>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
