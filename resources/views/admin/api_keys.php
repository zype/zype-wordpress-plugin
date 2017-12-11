<?php if (!defined('ABSPATH')) die(); 

$getvalidation_icon = function($key) use($options){
    if(is_array($options['invalid_key']) && array_search($key,$options['invalid_key']) !== false){
        return '<span alt="f158" style="color:red" class="'.$key.' dashicons dashicons-no red"></span>';
    }
    return '<span alt="f147" style="color:green" class="'.$key.' dashicons dashicons-yes"></span>';
}
?>



<div class="wrap zype-admin">
    <h2><?php echo get_admin_page_title(); ?></h2>

    <p>Proper API keys are required for the plugin to function.</p>

    <p>Your API keys can be found <a href="https://admin.zype.com/api_keys" target="_blank">here</a>.</p>

    <form method="post" action="<?php echo admin_url('admin.php'); ?>">
        <input type="hidden" name="action" value="zype_api_keys">
        <?php wp_nonce_field('zype_api_keys'); ?>
        <table class="form-table">
            <tbody>
            <tr>
                <!--<th scope="row">
                    <label for="zype_environment">Zype Environment</label>
                </th>-->
                <td>
					<input type="hidden" name="zype_environment" id="zype_environment" class="regular-text" value="Production"><!--</?php echo $options['zype_environment']; ?>-->
                    <!--<select name="zype_environment" id="zype_environment" class="regular-text" value="</?php echo $options['zype_environment']; ?>">
                        </?php foreach ($this->zypeEnvironmentSettings as $envName => $environment) { ?>
                            <option value="</?php echo $envName; ?>" </?php echo ($envName == $options['zype_environment']) ? 'selected="selected"' : '';?>></?php echo $envName; ?></option>
                        </?php } ?>
                    </select>-->
                    <p class="description"></p>
                </td>
            </tr>
			<tr>
				<th scope="row">
					 <label for="app-key">App Key*</label>
				</th>
				<td>
					<input type="text" name="app_key" id="app-key" class="regular-text"
							value="<?php echo $options['app_key']; ?>">
                    <?php echo $getvalidation_icon('app_key')?>
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
                    <?php echo $getvalidation_icon('admin_key')?>
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

                    <?php echo $getvalidation_icon('player_key')?>
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
                    <?php echo $getvalidation_icon('read_only_key')?>
                    <p class="description"></p>
                    
                </td>
            </tr>
            </tbody>
        </table>
		<hr>
		<h3>Stripe</h3>
		<table class="form-table">
		  <tbody>
			<tr>
			  <th scope="row">
				<label for="stripe-pk">Public Key</label>
			  </th>
			  <td>
				<input type="text" name="stripe_pk" id="stripe-pk" class="regular-text" value="<?php echo $options['stripe_pk']; ?>">
                <?php /*echo $getvalidation_icon('stripe_pk')*/?>
				<p class="description"></p>
			  </td>
			</tr>
		  </tbody>
		</table>
		<hr>
		<h3>Braintree</h3>
		<table class="form-table">
		  <tbody>
			<tr> 
			  <th scope="row">
				<label for="braintree-environment">Environment</label>
			  </th>
			  <td>
				<input type="text" name="braintree_environment" id="braintree-environment" class="regular-text" value="<?php echo $options['braintree_environment']; ?>">
                <?php /*echo $getvalidation_icon('braintree_environment')*/?>
				<p class="description"></p>
			  </td>
			</tr>
			<tr>
			  <th scope="row">
				<label for="braintree-merchant-id">Merchant ID</label>
			  </th>
			  <td>
				<input type="text" name="braintree_merchant_id" id="braintree-merchant-id" class="regular-text" value="<?php echo $options['braintree_merchant_id']; ?>">
                <?php /*echo $getvalidation_icon('braintree_merchant_id')*/?>
				<p class="description"></p>
			  </td>
			</tr>
			<tr>
			  <th scope="row">
				<label for="braintree-private-key">Private Key</label>
			  </th>
			  <td>
				<input type="text" name="braintree_private_key" id="braintree-private-key" class="regular-text" value="<?php echo $options['braintree_private_key']; ?>">
                <?php /*echo $getvalidation_icon('braintree_private_key')*/?>
				<p class="description"></p>
			  </td>
			</tr>
			<tr>
			  <th scope="row">
				<label for="braintree-public-key">Public Key</label>
			  </th>
			  <td>
				<input type="text" name="braintree_public_key" id="braintree-public-key" class="regular-text" value="<?php echo $options['braintree_public_key']; ?>">
                <?php /*echo $getvalidation_icon('braintree_public_key')*/?>
				<p class="description"></p>
			  </td>
			</tr>
		  </tbody>
		</table>

        <h3>User Authentification</h3>
        <table class="form-table">
          <tbody>
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
           </tbody>
        </table>

        <p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary"
                                 value="Save Changes"></p>
    </form>
</div>
