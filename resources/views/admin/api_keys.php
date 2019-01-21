<?php if (!defined('ABSPATH')) die();

$getvalidation_icon = function ($key) use ($options) {
    if (is_array($options['invalid_key']) && array_search($key, $options['invalid_key']) !== false) {
        return '<span alt="f158" style="color:red" class="' . $key . ' dashicons dashicons-no red"></span>';
    }
    elseif (!empty($options[$key])) {
        return '<span alt="f147" style="color:green" class="' . $key . ' dashicons dashicons-yes"></span>';
    }
    return;
}
?>
<div class="wrap zype-admin">
    <h2><?php echo get_admin_page_title(); ?></h2>

    <h3>Initial Setup</h3>
    <p>Welcome to the Zype plugin for WordPress. In order to use the Zype plugin, please enter API keys,
        as well as other credentials and settings from your Zype account.</p>

    <p>First, if you haven’t already done so, you’ll want to create a
        <a href="https://admin.zype.com/apps/new?type=wordpress" target="_blank">WordPress app profile</a>
        in Zype’s admin. Please follow the instructions in Zype to complete the app profile creation.</p>

    <h3>API Keys</h3>
    <p>Your WordPress App Key and all your other API keys can be found in Zype’s admin in the API keys section.
        Please copy and paste each API key listed below from your Zype account admin.</p>

    <form method="post" action="<?php echo admin_url('admin.php'); ?>">
        <input type="hidden" name="action" value="zype_api_keys">
        <?php wp_nonce_field('zype_api_keys'); ?>
        <table class="form-table">
            <tbody>
            <tr>
                <th scope="row">
                    <label for="app-key">App Key*</label>
                </th>
                <td>
                    <input type="text" name="app_key" id="app-key" class="regular-text"
                           value="<?php echo $options['app_key']; ?>">
                    <?php echo $getvalidation_icon('app_key') ?>
                    <p class="description"></p>
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="admin-key">Admin Key*</label>
                </th>
                <td>
                    <input type="text" name="admin_key" id="admin-key" class="regular-text"
                           value="<?php echo $options['admin_key']; ?>">
                    <?php echo $getvalidation_icon('admin_key') ?>
                    <p class="description"></p>

                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="player-key">Player Key*</label>
                </th>
                <td>
                    <input type="text" name="player_key" id="player-key" class="regular-text"
                           value="<?php echo $options['player_key']; ?>">

                    <?php echo $getvalidation_icon('player_key') ?>
                    <p class="description"></p>
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="read-only-key">Read-only Key*</label>
                </th>
                <td>
                    <input type="text" name="read_only_key" id="read-only-key" class="regular-text"
                           value="<?php echo $options['read_only_key']; ?>">
                    <?php echo $getvalidation_icon('read_only_key') ?>
                    <p class="description"></p>

                </td>
            </tr>
            </tbody>
        </table>
        <hr>

        <h3>Payment Setup</h3>
        <p>If you plan on monetizing videos using subscriptions or other paywall settings,
            you must set up an account with a supported payment process, configure payment settings in Zype,
            and enter your payment provider details below.
        </p>
        </p>Visit <a href="https://admin.zype.com/monetization" target="_blank">Zype’s Monetization menu</a>
        to learn more about setting up subscriptions and other paywalls.
        </p>
        <p>If you’ve already created a subscription plan, visit
            <a href="https://admin.zype.com/site/edit" target="_blank">Zype’s Settings > Monetization tab </a>
            to access your Stripe and/or Braintree information to enter below.</p>

        <h4>Stripe</h4>
        <table class="form-table">
            <tbody>
            <tr>
                <th scope="row">
                    <label for="stripe-pk">Public Key*</label>
                </th>
                <td>
                    <input type="text" name="stripe_pk" id="stripe-pk" class="regular-text"
                           value="<?php echo $options['stripe_pk']; ?>">
                    <?php echo $getvalidation_icon('stripe_pk') ?>
                    <p class="description"></p>
                </td>
            </tr>
            </tbody>
        </table>
        <hr>
        <h4>Braintree</h4>
        <table class="form-table">
            <tbody>
            <tr>
                <th scope="row">
                    <label for="braintree-environment">Environment</label>
                </th>
                <td>
                    <input type="text" name="braintree_environment" id="braintree-environment" class="regular-text"
                           value="<?php echo $options['braintree_environment']; ?>">
                    <?php /*echo $getvalidation_icon('braintree_environment')*/ ?>
                    <p class="description"></p>
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="braintree-merchant-id">Merchant ID</label>
                </th>
                <td>
                    <input type="text" name="braintree_merchant_id" id="braintree-merchant-id" class="regular-text"
                           value="<?php echo $options['braintree_merchant_id']; ?>">
                    <?php /*echo $getvalidation_icon('braintree_merchant_id')*/ ?>
                    <p class="description"></p>
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="braintree-private-key">Private Key</label>
                </th>
                <td>
                    <input type="text" name="braintree_private_key" id="braintree-private-key" class="regular-text"
                           value="<?php echo $options['braintree_private_key']; ?>">
                    <?php /*echo $getvalidation_icon('braintree_private_key')*/ ?>
                    <p class="description"></p>
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="braintree-public-key">Public Key</label>
                </th>
                <td>
                    <input type="text" name="braintree_public_key" id="braintree-public-key" class="regular-text"
                           value="<?php echo $options['braintree_public_key']; ?>">
                    <?php /*echo $getvalidation_icon('braintree_public_key')*/ ?>
                    <p class="description"></p>
                </td>
            </tr>
            </tbody>
        </table>

        <h3>Consumer Account Management Setup</h3>

        <p>If you plan on monetizing videos using subscriptions or other paywalls, you should also provide your
            consumers
            with the ability to manage their accounts after purchasing your video products.
            This way they can update their email addresses, change their subscription settings, and more.</p>

        <p>In order to support Consumer Account Management Pages in WordPress you must enter
            OAuth credentials below. To find your OAuth credentials, please visit
            <a href="https://admin.zype.com/apps" target="_blank">Zype’s Apps dashboard</a> and look for your “Wordpress
            App.”
            Click into the Wordpress App and look for the “OAuth Credentials:” link. Click on “View Credentials” to
            expose your Client ID and Client secret.</p>
        <table class="form-table">
            <tbody>
            <tr>
                <th scope="row">
                    <label for="oauth-client-id">OAuth Client ID</label>
                </th>
                <td>
                    <input type="text" name="oauth_client_id" id="oauth-client-id" class="regular-text"
                           value="<?php echo $options['oauth_client_id']; ?>">
                    <p class="description"></p>
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="oauth-client-secret">OAuth Client Secret</label>
                </th>
                <td>
                    <input type="text" name="oauth_client_secret" id="oauth-client-secret" class="regular-text"
                           value="<?php echo $options['oauth_client_secret']; ?>">
                    <p class="description"></p>
                </td>
            </tr>
            </tbody>
        </table>

        <h3>Hosting Compatibility</h3>
        <table class="form-table">
            <tbody>
            <tr>
                <th scope="row">
                    <label for="zype-saas-compatability">WordPress.com Hosting Compatibility*</label>
                </th>
                <td>
                    <input type="checkbox" name="zype_saas_compatability" id="zype-saas-compatability"
                           class="regular-checkbox"<?php echo $options['zype_saas_compatability'] ? 'checked="checked"' : ''; ?>>
                    <p class="description">Leave checked if you would like to create WordPress users for each consumer
                        sign up. Required if using WordPress.com for hosting.</p>
                </td>
            </tr>
            </tbody>
        </table>

        <h3>Pagination</h3>
        <table class="form-table">
            <tbody>
            <tr>
                <th scope="row">
                    <label for="playlist-pagination">Pagination</label>
                </th>
                <td>
                    <input type="checkbox" name="playlist_pagination" id="playlist-pagination"
                           class="regular-checkbox"<?php echo $options['playlist_pagination'] ? 'checked="checked"' : ''; ?>>
                    <p class="description">Leave checked if you would like to paginate your playlist.</p>
                </td>
            </tr>
            </tbody>
        </table>

        <p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary"
                                 value="Save Changes"></p>
    </form>
</div>
