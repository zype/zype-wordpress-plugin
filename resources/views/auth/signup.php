<div id="zype-modal-signup">
<div class="content-wrap signup-wrap user-action-wrap zype-form-center">
  <div class="main-heading inner-heading">
    <h1 class="title text-uppercase zype-title">Create my login</h1>
  </div>
  <div class="user-wrap">
    <div class="holder-main">
      <div class="row">
        <div class="">
          <form action="<?php echo admin_url('admin-ajax.php') ?>" class="user-form nice-form zype_ajax_form" method="post">
            <input type="hidden" name="action" value="zype_sign_up">
            <div class="error-section"><?php echo $zype_message; ?></div>
            <div class="field-section">
              <div class="zype_flash_messages"></div>
              <div class="form-group required-row zype-input-wrap">
                <input placeholder="Name" type="text" class="form-control required zype-input-text" id="first-name" name="name" <?php if(isset($zype_signup_name)){?> value=<?php echo $zype_signup_name; ?><?php } ?>>
              </div>
              <div class="form-group required-row zype-input-wrap">
                <input placeholder="Email" type="email" class="form-control required-email zype-input-text" id="email-signup" name="email" <?php if(isset($zype_signup_email)){?> value=<?php echo $zype_signup_email; ?><?php } ?>>
              </div>
              <div class="form-group required-row zype-input-wrap">
                <input placeholder="Password" type="password" class="form-control required zype-input-text" id="password-signup" name="password">
              </div>
              <div class="signup-note">By clicking Create My Login, you agree to our <a href="<?php echo get_permalink(get_page_by_path('terms-of-service')); ?>">Terms of Service</a></div>
              <button type="submit" class="zype-button">Create my login</button>
              <p class="to-sign-in">Already have an account? <a href="<?php echo get_permalink() . "?zype_auth_type=login" ?>" class="zype_auth_markup" data-type="login" data-id="0">Sign In</a></p>
              </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
</div>