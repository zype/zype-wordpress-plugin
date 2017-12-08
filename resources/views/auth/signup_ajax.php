<div class="content-wrap zype_signup-wrapper user-action-wrap">
  <div class="zype_signup__heading">
      <p>Gain exclusive access to original content.</p>
      <div class="zype_signup__login">Already have an account, <a class="zype_signup__link" href="<?php echo home_url(Config::get('zype.login_url')) ?>/">Login</a> </div>
  </div>
  <!--<div class="zype_signup__plans">
    <div class="zype_signup__plans--plan">
      <strong class="price">&#36;6.95</strong>
      <span class="duration">per month</span>
    </div>
    <div class="zype_signup__plans--plan">
      <strong class="price">&#36;32.95</strong>
      <span class="duration">6 months</span>
    </div>
    <div class="zype_signup__plans--plan">
      <strong class="price">&#36;59.95</strong>
      <span class="duration">per year</span>
    </div>
  </div> -->
  <div class="zype_signup__subscribe">
    <strong class="zype_signup__subscribe--title"><span>Subscribing is easy.</span> Once you’re registered, you can:</strong>
    <p>Become a subscriber</p>
    <p>Be notifed of periodic updates on our site,</p>
    <p>View and listen to exclusive live and archived content only availableto to subscribers.</p>
    <p>Interact and experience the show in a whole new way.</p>
  </div>
  <form id="zype_signup_form_ajax" action="<?php echo get_site_url()?>/signup_ajax/" class="user-form nice-form" method="post">
    <input type="hidden" name="action" value="zype_sign_up">
    <div class="error-section"><?php echo $zype_message; ?></div>
    <div class="zype_signup__fields">
      <div class="zype_flash_messages"></div>
      <div class="zype_signup__fields-group required-row">
        <label for="first-name">Full Name</label>
        <input type="text" class="required" id="first-name" name="name" <?php if(isset($zype_signup_name)){?> value=<?php echo $zype_signup_name; ?><?php } ?>>
      </div>
      <div class="zype_signup__fields-group required-row">
        <label for="email">Email</label>
        <input type="email" class="required-email" placeholder="name@company.com" id="email" name="email" <?php if(isset($zype_signup_email)){?> value=<?php echo $zype_signup_email; ?><?php } ?>>
      </div>
      <div class="zype_signup__fields-group required-row">
        <label for="password">Password</label>
        <input type="password" class="required" id="password" name="password">
      </div>
      <div class="zype_signup__fields-group required-row">
        <label for="confirm_password">Confirm password</label>
        <input type="password" class="required" id="confirm_password" name="confirm_password">
      </div>
      
      <div class="zype_signup__fields-submit">
        <input type="submit" value="Sign Up">
      </div>
    </div>
  </form>
  <div class="zype_signup__note">By signing up you agree to the <a class="zype_signup__link" href="<?php echo get_permalink(get_page_by_path('terms-of-service')); ?>">Terms of Service</a> &amp; <a class="zype_signup__link" href="<?php echo get_permalink(get_page_by_path('privacy-policy')); ?>">Privacy Policy</a></div>
</div>
<script> 
(function($){
$(document).ready(function() { 
	$("#zype_signup_form_ajax").ajaxForm({
		beforeSubmit: function() {
			$("#zype_signup_form_ajax .error-section").html("");
		},
		success: function(data) {
			data = $.parseJSON(data);
			
			if(data.status == true)
				location.reload();
			else 
				$("#zype_signup_form_ajax .error-section").html(data.errors.join(","));
		}
	});
});
})(jQuery); 
</script>