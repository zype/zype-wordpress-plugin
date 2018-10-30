<?php if (!defined('ABSPATH')) die(); ?>
<div class="wrap zype-admin">
    <h2><?php echo get_admin_page_title(); ?></h2>
    <p>If you monetize with subscriptions or other transaction paywalls,
        the Zype plugin allows you to easily add pages for consumer account and
        transaction management to your website. These pages allow your consumers
        to manage their profiles and subscriptions,
        link set top devices for universal authentication, and more.
    </p>
    <p>Check the box to enable each set of pages below.
        They will automatically appear in your website at the URL entered once you save changes.
        You can modify the URL that each page appears on by changing the URL slug in the accompanying boxes.
    </p>
    <h3>Profile Management</h3>
    <p>Enable Authentication to allow your consumers to update their account,
        including changing their login email and password,
        on the Profile page. This will also provide a dedicated Logout page
        allowing consumers to sign out of their accounts.</p>
    <form method="post" action="<?php echo admin_url('admin.php'); ?>">
        <input type="hidden" name="action" value="zype_users">
        <?php wp_nonce_field('zype_users'); ?>
        <table class="form-table">
            <tbody>
            <tr>
                <th scope="row">
                    <label for="authentication-enabled">Enable Authentication</label>
                </th>
                <td>
                    <input type="checkbox" name="authentication_enabled" id="authentication-enabled"
                           class="regular-checkbox" <?php echo $options['authentication_enabled'] ? 'checked="checked"' : ''; ?>>
                    <p class="description"></p>
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="logout-url">Login URL</label>
                </th>
                <td>
                    /<input type="text" name="auth_url" id="auth-url" class="regular-text"
                            value="<?php echo $options['auth_url']; ?>">
                    <p class="description"></p>
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="logout-url">Logout URL</label>
                </th>
                <td>
                    /<input type="text" name="logout_url" id="logout-url" class="regular-text"
                            value="<?php echo $options['logout_url']; ?>">
                    <p class="description"></p>
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="profile-url">Profile URL</label>
                </th>
                <td>
                    /<input type="text" name="profile_url" id="profile-url" class="regular-text"
                            value="<?php echo $options['profile_url']; ?>">
                    <p class="description"></p>
                </td>
            </tr>
            </tbody>
        </table>
        <h3>Device Linking</h3>
        <p>Enable Device Linking if you sell subscription or transaction paywalled
            content in set top apps like Apple TV, Roku, or Amazon Fire TV that
            feature device linking universal login authentication.
            The Device Linking page makes it easy for consumers to log into accounts
            to view content in your set top apps.</p>
        <table class="form-table">
            <tbody>
            <tr>
                <th scope="row">
                    <label for="device-link-enabled">Enable Device Linking</label>
                </th>
                <td>
                    <input type="checkbox" name="device_link_enabled" id="device-link-enabled"
                           class="regular-checkbox" <?php echo $options['device_link_enabled'] ? 'checked="checked"' : ''; ?>>
                    <p class="description"></p>
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="device-link-url">Device Linking URL</label>
                </th>
                <td>
                    /<input type="text" name="device_link_url" id="device-link-url" class="regular-text"
                            value="<?php echo $options['device_link_url']; ?>">
                    <p class="description"></p>
                </td>
            </tr>
            </tbody>
        </table>

        <h3>Subscription and Paywall Management</h3>
        <p>Enable Subscription Management to allow consumers to easily manage their
            subscriptions or purchased content on your website.</p>
        <table class="form-table">
            <tbody>
            <tr>
                <th scope="row">
                    <label for="subscriptions-enabled">Enable Subscription Management</label>
                </th>
                <td>
                    <input type="checkbox" name="subscriptions_enabled" id="subscriptions-enabled"
                           class="regular-checkbox" <?php echo $options['subscriptions_enabled'] ? 'checked="checked"' : ''; ?>>
                    <p class="description"></p>
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="subscribe-url">Subscribe URL</label>
                </th>
                <td>
                    /<input type="text" name="subscribe_url" id="subscribe-url" class="regular-text"
                            value="<?php echo $options['subscribe_url']; ?>">
                    <p class="description"></p>
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="subscribe-url">Rental URL</label>
                </th>
                <td>
                    /<input type="text" name="rental_url" id="rental-url" class="regular-text"
                            value="<?php echo $options['rental_url']; ?>">
                    <p class="description"></p>
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="subscribe-url">Pass URL</label>
                </th>
                <td>
                    /<input type="text" name="pass_url" id="pass-url" class="regular-text"
                            value="<?php echo $options['pass_url']; ?>">
                    <p class="description"></p>
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="subscribe-url">Purchase URL</label>
                </th>
                <td>
                    /<input type="text" name="purchase_url" id="purchase_url" class="regular-text"
                            value="<?php echo $options['purchase_url']; ?>">
                    <p class="description"></p>
                </td>
            </tr>            
            </tbody>
        </table>

        <h3>Other links</h3>
        <table class="form-table">
            <tbody>
            <tr>
                <th scope="row">
                    <label for="terms-url">Terms page</label>
                </th>
                <td>
                    <input type="text" name="terms_url" id="terms-url" class="regular-text"
                           value="<?php echo $options['terms_url']; ?>">
                    <p class="description">Enter either a full page URL or a relative URL slug for your Terms of Service
                        page. If you leave this blank, customers will not see a terms of service link during their
                        subscription purchase flow.</p>
                </td>
            </tr>
            </tbody>
        </table>

        <p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary"
                                 value="Save Changes"></p>
    </form>
    <form method="post" action="<?php echo admin_url('admin.php'); ?>">
        <input type="hidden" name="action" value="zype_cookie_key">
        <?php wp_nonce_field('zype_cookie_key'); ?>
        <table class="form-table">
            <tbody>
            <tr>
                <th scope="row">
                    <label for="cookie-key">Secret Cookie Key</label>
                </th>
                <td>
                    <input type="text" disabled="disabled" name="cookie_key" id="cookie-key" class="regular-text"
                           value="<?php echo $options['cookie_key']; ?>">
                    <p class="description"></p>
                </td>
            </tr>
            </tbody>
        </table>
        <p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary"
                                 value="Reset Secret Key"></p>
    </form>
</div>
