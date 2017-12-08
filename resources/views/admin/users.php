<?php if(!defined('ABSPATH')) die(); ?>
<div class="wrap zype-admin">
    <h2><?php echo get_admin_page_title(); ?></h2>
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
              <input type="checkbox" name="authentication_enabled" id="authentication-enabled" class="regular-checkbox" <?php echo $options['authentication_enabled'] ? 'checked="checked"' : ''; ?>>
              <p class="description"></p>
            </td>
          </tr>
          <tr>
            <th scope="row">
              <label for="logout-url">Logout URL</label>
            </th>
            <td>
              /<input type="text" name="logout_url" id="logout-url" class="regular-text" value="<?php echo $options['logout_url']; ?>">
              <p class="description"></p>
            </td>
          </tr>
          <tr>
            <th scope="row">
              <label for="profile-url">Profile URL</label>
            </th>
            <td>
              /<input type="text" name="profile_url" id="profile-url" class="regular-text" value="<?php echo $options['profile_url']; ?>">
              <p class="description"></p>
            </td>
          </tr>
          <tr>
            <th scope="row">
              <label for="device-link-enabled">Enable Device Linking</label>
            </th>
            <td>
              <input type="checkbox" name="device_link_enabled" id="device-link-enabled" class="regular-checkbox" <?php echo $options['device_link_enabled'] ? 'checked="checked"' : ''; ?>>
              <p class="description"></p>
            </td>
          </tr>
          <tr>
            <th scope="row">
              <label for="device-link-url">Device Linking URL</label>
            </th>
            <td>
              /<input type="text" name="device_link_url" id="device-link-url" class="regular-text" value="<?php echo $options['device_link_url']; ?>">
              <p class="description"></p>
            </td>
          </tr>
          <tr>
            <th scope="row">
              <label for="oauth-client-id">OAuth Client ID</label>
            </th>
            <td>
              <input type="text" name="oauth_client_id" id="oauth-client-id" class="regular-text" value="<?php echo $options['oauth_client_id']; ?>">
              <p class="description"></p>
            </td>
          </tr>
          <tr>
            <th scope="row">
              <label for="oauth-client-secret">OAuth Client Secret</label>
            </th>
            <td>
              <input type="text" name="oauth_client_secret" id="oauth-client-secret" class="regular-text" value="<?php echo $options['oauth_client_secret']; ?>">
              <p class="description"></p>
            </td>
          </tr>
          <tr>
            <th scope="row">
              <label for="subscriptions-enabled">Enable Subscription Management</label>
            </th>
            <td>
              <input type="checkbox" name="subscriptions_enabled" id="subscriptions-enabled" class="regular-checkbox" <?php echo $options['subscriptions_enabled'] ? 'checked="checked"' : ''; ?>>
              <p class="description"></p>
            </td>
          </tr>
          <tr>
            <th scope="row">
              <label for="subscribe-url">Subscribe URL</label>
            </th>
            <td>
              /<input type="text" name="subscribe_url" id="subscribe-url" class="regular-text" value="<?php echo $options['subscribe_url']; ?>">
              <p class="description"></p>
            </td>
          </tr>
          <tr>
            <th scope="row">
              <label for="subscribe-url">Rental URL</label>
            </th>
            <td>
              /<input type="text" name="rental_url" id="rental-url" class="regular-text" value="<?php echo $options['rental_url']; ?>">
              <p class="description"></p>
            </td>
          </tr>
          <tr>
            <th scope="row">
              <label for="subscribe-url">Pass URL</label>
            </th>
            <td>
              /<input type="text" name="pass_url" id="pass-url" class="regular-text" value="<?php echo $options['pass_url']; ?>">
              <p class="description"></p>
            </td>
          </tr>
        </tbody>
      </table>
      <p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="Save Changes"></p>
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
              <input type="text" disabled="disabled" name="cookie_key" id="cookie-key" class="regular-text" value="<?php echo $options['cookie_key']; ?>">
              <p class="description"></p>
            </td>
          </tr>
        </tbody>
      </table>
      <p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="Reset Secret Key"></p>
    </form>
</div>
