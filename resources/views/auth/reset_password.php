<?php get_header(); ?>
<div class="content-wrap container">
  <div class="main-heading inner-heading">
    <strong class="title text-uppercase">Reset your password</strong>
  </div>
  <div class="user-wrap">
    <div class="forget-note"></div>
    <form action="<?php zype_url('profile'); ?>/reset-password/<?php echo $zype_password_token; ?>/submit/" class="user-form forget-form nice-form" method="post">
      <input type="hidden" name="action" value="zype_reset_password">
      <fieldset>
        <div class="success-section"></div>
        <div class="error-section"><?php echo $zype_message; ?></div>
        <div class="zype_flash_messages"></div>
        <div class="field-section">
          <input type="hidden" value="<?php echo $zype_password_token; ?>" name="password_token">
          <div class="form-group required-row">
            <label for="email" class="text-uppercase">Email</label>
            <input type="email" class="form-control required-email" id="email" name="email" required="required">
          </div>
          
          <div class="form-group required-row">
            <label for="password" class="text-uppercase">New Password</label>
            <input type="password" class="form-control required" id="password" name="password" required="required">
          </div>
          
          <div class="form-group required-row">
            <label for="password_confirmation" class="text-uppercase">Confirm New Password</label>
            <input type="password" class="form-control required" id="password_confirmation" name="password_confirmation" required="required">
          </div>

          <div class="btn-holder">
            <input type="submit" value="Reset Password" class="btn btn-primary">
          </div>
        
        </div>
      </fieldset>
    </form>
  </div>
</div>
<?php get_footer(); ?>
