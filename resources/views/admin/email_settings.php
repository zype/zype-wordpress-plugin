<div class="wrap zype-admin" id='email-settings'>
    <h2><?php echo get_admin_page_title(); ?></h2>
    <form method="post" action="<?php echo admin_url('admin.php'); ?>">
        <input type="hidden" name="action" value="zype_email_settings">
        <?php wp_nonce_field('zype_email_settings'); ?>
        <div class="container">
            <div class="row">
                <div class="col-xs-2 email-title">
                    <b>Cancel subscription email</b>
                    <br>
                    <label for="cancel-subscription-enabled">Enabled</label>
                    <input type="checkbox" name="cancel_subscription_enabled" id="cancel-subscription-enabled" class="regular-checkbox"
                        <?php echo $options['emails']['cancel_subscription']['enabled'] ? 'checked="checked"' : ''; ?>
                    >
                </div>
                <div class="col-xs-4">
                    <textarea cols="60" rows="4" name="cancel_subscription" id="cancel-subscription" class="regular-text"><?php echo $options['emails']['cancel_subscription']['text']; ?></textarea>
                    <p class="email-description">This text will be shown on the subscription cancellation email.</p>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-2 email-title">
                    <b>Forgot password email</b>
                    <br>
                    <label for="forgot-password-enabled">Enabled</label>
                    <input type="checkbox" name="forgot_password_enabled" id="forgot-password-enabled" class="regular-checkbox"
                        <?php echo $options['emails']['forgot_password']['enabled'] ? 'checked="checked"' : ''; ?>
                    >
                </div>
                <div class="col-xs-4">
                    <textarea cols="60" rows="4" name="forgot_password" id="forgot-password" class="regular-text"><?php echo $options['emails']['forgot_password']['text']; ?></textarea>
                    <p class="email-description">
                        Required placeholders: <?php echo join(', ', $options['emails']['forgot_password']['required']); ?>.
                        <br>
                        This text will be shown on reset forgotten password email.
                    </p>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-2 email-title">
                    <b>New account email</b>
                    <br>
                    <label for="new-account-enabled">Enabled</label>
                    <input type="checkbox" name="new_account_enabled" id="new-account-enabled" class="regular-checkbox"
                        <?php echo $options['emails']['new_account']['enabled'] ? 'checked="checked"' : ''; ?>
                    >
                </div>
                <div class="col-xs-4">
                    <textarea cols="60" rows="4" name="new_account" id="new-account" class="regular-text"><?php echo $options['emails']['new_account']['text']; ?></textarea>
                    <p class="description">
                        Required placeholders: <?php echo join(', ', $options['emails']['new_account']['required']); ?>.
                        <br>
                        This text will be shown on the new account email.
                    </p>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-2 email-title">
                    <b>New rental email</b>
                    <br>
                    <label for="new-rental-enabled">Enabled</label>
                    <input type="checkbox" name="new_rental_enabled" id="new-rental-enabled" class="regular-checkbox"
                        <?php echo $options['emails']['new_rental']['enabled'] ? 'checked="checked"' : ''; ?>
                    >
                </div>
                <div class="col-xs-4">
                    <textarea cols="60" rows="4" name="new_rental" id="new-rental" class="regular-text"><?php echo $options['emails']['new_rental']['text']; ?></textarea>
                    <p class="description">
                        Required placeholders: <?php echo join(', ', $options['emails']['new_rental']['required']); ?>.
                        <br>
                        This text will be shown on the new rental email.
                    </p>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-2 email-title">
                    <b>New purchase email</b>
                    <br>
                    <label for="new-purchase-enabled">Enabled</label>
                    <input type="checkbox" name="new_purchase_enabled" id="new-purchase-enabled" class="regular-checkbox"
                        <?php echo $options['emails']['new_purchase']['enabled'] ? 'checked="checked"' : ''; ?>
                    >
                </div>
                <div class="col-xs-4">
                    <textarea cols="60" rows="4" name="new_purchase" id="new-purchase" class="regular-text"><?php echo $options['emails']['new_purchase']['text']; ?></textarea>
                    <p class="description">
                        Required placeholders: <?php echo join(', ', $options['emails']['new_purchase']['required']); ?>.
                        <br>
                        This text will be shown on the new purchase email.
                    </p>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-2 email-title">
                    <b>New pass email</b>
                    <br>
                    <label for="new-pass-enabled">Enabled</label>
                    <input type="checkbox" name="new_pass_enabled" id="new-pass-enabled" class="regular-checkbox"
                        <?php echo $options['emails']['new_pass']['enabled'] ? 'checked="checked"' : ''; ?>
                    >
                </div>
                <div class="col-xs-4">
                    <textarea cols="60" rows="4" name="new_pass" id="new-pass" class="regular-text"><?php echo $options['emails']['new_pass']['text']; ?></textarea>
                    <p class="description">
                        Required placeholders: <?php echo join(', ', $options['emails']['new_pass']['required']); ?>.
                        <br>
                        This text will be shown on the new pass plan email.
                    </p>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-2 email-title">
                    <b>New subscription email</b>
                    <br>
                    <label for="new-subscription-enabled">Enabled</label>
                    <input type="checkbox" name="new_subscription_enabled" id="new-subscription-enabled" class="regular-checkbox"
                        <?php echo $options['emails']['new_subscription']['enabled'] ? 'checked="checked"' : ''; ?>
                    >
                </div>
                <div class="col-xs-4">
                    <textarea cols="60" rows="4" name="new_subscription" id="new-subscription" class="regular-text"><?php echo $options['emails']['new_subscription']['text']; ?></textarea>
                    <p class="description">
                        Required placeholders: <?php echo join(', ', $options['emails']['new_subscription']['required']); ?>.
                        <br>
                        This text will be shown on the new subscription email.
                    </p>
                </div>
            </div>
        </div>
        <p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="Save Changes"></p>
    </form>
</div>
