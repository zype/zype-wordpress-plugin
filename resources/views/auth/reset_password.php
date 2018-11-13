<?php get_header(); ?>
<div class="content-wrap container user-profile-wrap">
    <div class="main-heading inner-heading">
        <strong class="title text-uppercase">Reset your password</strong>
    </div>
    <div class="user-wrap">
        <div class="forget-note"></div>
        <form action="<?php zype_url('profile'); ?>/reset-password/<?php echo $zype_password_token; ?>/submit/"
              class="user-form forget-form nice-form" method="post">
            <input type="hidden" name="action" value="zype_reset_password">
            <div class="success-section"></div>
            <div class="error-section"><?php echo $zype_message; ?></div>
            <div class="field-section">
                <div class="zype_flash_messages"></div>
                <input type="hidden" value="<?php echo $zype_password_token; ?>" name="password_token">

                <div class="form-group required-row user-profile-wrap__field">
                    <input type="email" class="form-control required-email user-profile-wrap__inp" id="email"
                           name="email" required="required" placeholder="Email">
                </div>

                <div class="form-group required-row user-profile-wrap__field">
                    <input type="password" class="form-control required user-profile-wrap__inp" id="password"
                           name="password" required="required" placeholder="New Password">
                </div>

                <div class="form-group required-row user-profile-wrap__field">
                    <input type="password" class="form-control required user-profile-wrap__inp"
                           id="password_confirmation" name="password_confirmation" required="required"
                           placeholder="Confirm New Password">
                </div>

                <input type="submit" value="Reset Password" class="btn btn-primary user-profile-wrap__button zype-custom-button">

            </div>
        </form>
    </div>
</div>
<?php get_footer(); ?>
