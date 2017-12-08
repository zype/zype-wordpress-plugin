<div class="content-wrap signup-wrap user-action-wrap">
  <div class="main-heading inner-heading">
    <div class="logo">
      <a href="#"><img src="" height="75" width="51" alt="Your Channel"></a>
    </div>
  </div>
  <div class="user-wrap">
    <div class="user-intro">
      <h1>Subscribe to the channel</h1>
      <p>Gain exclusive access to over 300 hours of original content.</p>
      <div class="user-frame">Already have an account, <a href="<?php echo get_permalink() . "?zype_auth_type=login" ?>" class="zype_auth_markup" data-type="login">Login</a> </div>
    </div>
    <div class="holder-main">
      <div class="row">
        <div class="col-sm-6">
          <div class="stat-whole">
            <div class="stat-text">
              <div class="col">
                <strong class="title">&#36;1.99</strong>
                <span class="sub-title">per month</span>
              </div>
              <div class="col">
                <strong class="title">&#36;19.99</strong>
                <span class="sub-title">per year</span>
              </div>
            </div>
            <div class="subscribe-sub">
              <strong class="title"><span>Subscribing is easy.</span> Once you’re registered, you can:</strong>
              <p>Become a subscriber to the channel.</p>
              <p>Be notifed of periodic updates on our site,</p>
              <p>View and listen to exclusive live and archived content only  available to to channel subscribers.</p>
              <p>Interact and experience the show in a whole new way.</p>
            </div>
          </div>
        </div>
        <div class="col-sm-6">
          <form action="<?php echo admin_url('admin-ajax.php') ?>" class="user-form nice-form zype_ajax_form" method="post">
            <input type="hidden" name="action" value="zype_sign_up">
            <fieldset>
              <div class="error-section"><?php echo $zype_message; ?></div>
              <div class="field-section">
              <div class="zype_flash_messages"></div>
                <div class="form-group required-row">
                  <label class="text-uppercase" for="first-name">Full Name</label>
                  <input type="text" class="form-control required" id="first-name" name="name" <?php if(isset($zype_signup_name)){?> value=<?php echo $zype_signup_name; ?><?php } ?>>
                </div>
                <div class="form-group required-row">
                  <label class="text-uppercase" for="email">Email</label>
                  <input type="email" class="form-control required-email" id="email" name="email" <?php if(isset($zype_signup_email)){?> value=<?php echo $zype_signup_email; ?><?php } ?>>
                </div>
                <div class="form-group required-row">
                  <label class="text-uppercase" for="password">Password</label>
                  <input type="password" class="form-control required" id="password" name="password">
                </div>
                <div class="form-group required-row">
                  <label class="text-uppercase" for="confirm_password">Confirm password</label>
                  <input type="password" class="form-control required" id="confirm_password" name="confirm_password">
                </div>
                
                <div class="btn-holder">
                  <input type="submit" class="btn btn-primary" value="Create Your Account">
                </div>
                <div class="signup-note">By signing up you agree to the <a href="<?php echo get_permalink(get_page_by_path('terms-of-service')); ?>">Terms of Service</a> &amp; <a href="<?php echo get_permalink(get_page_by_path('privacy-policy')); ?>">Privacy Policy</a></div>
              </div>
            </fieldset>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>