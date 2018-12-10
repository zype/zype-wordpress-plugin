<div id="zype-modal-auth">
    <div class="content-wrap zype-form-center">
        <div class="main-heading inner-heading">
            <h1 class="title zype-title zype-custom-title">Sign in</h1>
        </div>
        <div class="user-wrap">
            <div class="holder-main">
                <div class="row">
                    <div class="">
                        <form action="<?php echo admin_url('admin-ajax.php') ?>"
                              class="user-form nice-form zype_ajax_form" method="post">
                            <input type="hidden" name="action" value="zype_login">
                            <input type="hidden" name="redirect_url" value="<?php echo $redirect_url ?>">
                            <div class="zype_flash_messages"></div>
                            <div class="error-section"></div>
                            <div class="field-section">
                                <p class="form-group required-row zype-input-wrap">
                                    <input name="username" type="email" class="required zype-input-text"
                                           id="email-login" placeholder="Email">
                                </p>
                                <p class="form-group required-row zype-input-wrap">
                                    <input name="password" type="password" class="required zype-input-text"
                                           id="password-login" placeholder="Password">
                                </p>
                                <div class="btn-holder">
                                    <button type="submit" class="zype_get_all_ajax zype-button zype-custom-button">Sign in</button>
                                </div>
                            </div>
                            <div class="success-section"></div>
                            <div class="">
                                <p class="to-forgot-password"><a
                                            href="<?php echo get_permalink() . "?zype_auth_type=forgot" ?>"
                                            class="zype_auth_markup" data-root-parent-id="<?php echo $root_parent ?>" data-type="forgot">Forgot password?</a></p>
                                <p class="to-sign-up">Don't have an account? <a
                                            href="<?php echo get_permalink() . "?zype_auth_type=register" ?>"
                                            class="zype_auth_markup" data-root-parent-id="<?php echo $root_parent ?>" data-type="register">Sign Up</a></p>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
