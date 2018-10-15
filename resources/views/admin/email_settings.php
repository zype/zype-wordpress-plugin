<?php if (!defined('ABSPATH')) die(); ?>
<div class="wrap zype-admin">
    <h2><?php echo get_admin_page_title(); ?></h2>
    <form method="post" action="<?php echo admin_url('admin.php'); ?>">
        <input type="hidden" name="action" value="zype_email_settings">
        <?php wp_nonce_field('zype_email_settings'); ?>
        <table class="form-table">
            <tbody>
                <tr>
                    <th scope="row">
                        <label for="cancel-subscription-email">Cancel subscription email</label>
                    </th>
                    <td>
                        <textarea cols="60" rows="4" name="cancel_subscription" id="cancel-subscription" class="regular-text"><?php echo $options['emails']['cancel_subscription']['text']; ?></textarea>
                        <p class="description">This text will be shown on the subscription cancellation email.</p>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="forgot-password">Forgot password email</label>
                    </th>
                    <td>
                        <textarea cols="60" rows="4" name="forgot_password" id="forgot-password" class="regular-text"><?php echo $options['emails']['forgot_password']['text']; ?></textarea>
                        <p class="description">
                            Required placeholders: <?php echo join(', ', $options['emails']['forgot_password']['required']); ?>.
                            <br>
                            This text will be shown on reset forgotten password email.
                        </p>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="new-account">New account email</label>
                    </th>
                    <td>
                        <textarea cols="60" rows="4" name="new_account" id="new-account" class="regular-text"><?php echo $options['emails']['new_account']['text']; ?></textarea>
                        <p class="description">
                            Required placeholders: <?php echo join(', ', $options['emails']['new_account']['required']); ?>.
                            <br>
                            This text will be shown on the new account email.
                        </p>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="new-rental">New rental email</label>
                    </th>
                    <td>
                        <textarea cols="60" rows="4" name="new_rental" id="new-rental" class="regular-text"><?php echo $options['emails']['new_rental']['text']; ?></textarea>
                        <p class="description">
                            Required placeholders: <?php echo join(', ', $options['emails']['new_rental']['required']); ?>.
                            <br>
                            This text will be shown on the new rental email.
                        </p>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="new-rental">New rental email</label>
                    </th>
                    <td>
                        <textarea cols="60" rows="4" name="new_rental" id="new-rental" class="regular-text"><?php echo $options['emails']['new_rental']['text']; ?></textarea>
                        <p class="description">
                            Required placeholders: <?php echo join(', ', $options['emails']['new_rental']['required']); ?>.
                            <br>
                            This text will be shown on the new rental email.
                        </p>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="new-subscription">New subscription email</label>
                    </th>
                    <td>
                        <textarea cols="60" rows="4" name="new_subscription" id="new-subscription" class="regular-text"><?php echo $options['emails']['new_subscription']['text']; ?></textarea>
                        <p class="description">
                            Required placeholders: <?php echo join(', ', $options['emails']['new_subscription']['required']); ?>.
                            <br>
                            This text will be shown on the new subscription email.
                        </p>
                    </td>
                </tr>
            </tbody>
        </table>
        <p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary"
                                 value="Save Changes"></p>
    </form>
</div>
