<div id="zype-modal-forgot" class='zype-form'>
    <div class="content-wrap signup-wrap user-action-wrap zype-form-center">
        <div class="main-heading inner-heading">
            <h1 class="title zype-title zype-custom-title">Forgot Password?</h1>
        </div>
        <div class="user-wrap">
            <div class="holder-main">
                <div class="row">
                    <div class="">
                        <form action="<?php echo admin_url('admin-ajax.php') ?>" class="user-form nice-form zype_ajax_form" method="post">
                            <input type="hidden" name="action" value="zype_forgot_password">
                            <div class="error-section"><?php echo isset($zype_message) ? $zype_message : ''; ?></div>
                            <div class="field-section">
                                <div class="zype_flash_messages"></div>
                                <div class="form-group required-row zype-input-wrap">
                                    <input placeholder="Email" type="email" class="required-email zype-input-text" id="email-forgot" name="email" <?php if(isset($zype_signup_email)){?> value=<?php echo $zype_signup_email; ?><?php } ?>>
                                </div>
                                <button type="submit" class="zype-button">Reset Password</button>
                                <p class="to-sign-in">
                                    Already have an account?
                                    <a href="<?php echo get_permalink() . "?zype_auth_type=login&root_parent=" . $root_parent ?>" class="zype_auth_markup" data-type="login" data-id="0" data-root-parent-id="<?php echo $root_parent; ?>">
                                        Sign In
                                    </a><br>
                                    Don't have an account?
                                    <a href="<?php echo get_permalink() . "?zype_auth_type=register&root_parent=" . $root_parent ?>" class="zype_auth_markup" data-type="register" data-root-parent-id="<?php echo $root_parent; ?>">
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
