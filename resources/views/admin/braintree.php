<?php
if (!defined('ABSPATH'))
    die();

$plans = \Zype::get_all_plans();
?>

<div class="wrap zype-admin">
    <h2><?php echo get_admin_page_title();?></h2>
    <p>Any active <a href ="https://admin.zype.com/plans" target="_blank">subscription
    plans created in your Zype account</a> can be made available for
    purchase on your WordPress website.
    Simply click below on each plan you would like to feature for sale on
     any subscription video content on your website. (Note, you may select multiple
     plans by cmd+click / ctrl+click on multiple selections.)</p>
    <p>You can <a href="https://admin.zype.com/plans/new" target="_blank">easily create additional
    subscription plans</a> in Zype
    if you don’t see an appropriate one below.
    </p>
    <form method="post" action="<?php echo admin_url('admin.php');?>">
        <input type="hidden" name="action" value="zype_braintree"/>
        <?php wp_nonce_field('zype_braintree');?>

        <select multiple="multiple" name="subscribe[]">
            <?php foreach ($plans as $plan) : ?>
                <option
            <?php if ( isset($options['subscribe_select']) && is_array( $options['subscribe_select'] ) && in_array( $plan->_id, $options['subscribe_select'] ) ) {
                echo 'selected="selected"';
            }?>

            id="<?php echo  $plan->_id ?>" value="<?php echo  $plan->_id ?>"><?php echo  $plan->name; ?></option>
            <?php endforeach;?>
        </select>
        <h2>Subscription Short Code</h2>
        <p>
            The Subscription Short Code can be added to any page or post on your WordPress website to
            provide a dedicated account creation and subscription plan sign up flow for your visitors.
            The Subscription Short Code will first prompt a user to create an account, then select and
            purchase a plan, and then redirect the user to any page of your choosing.
        </p>
        <table class="form-table">
          <tbody>
            <tr>
              <th scope="row">
                <label for="sub-short-code-btn-text">Subscribe Short Code Button Text:</label>
              </th>
              <td>
                <input type="text" name="sub_short_code_btn_text" id="sub-short-code-btn-text" class="regular-text" value="<?php echo $options['sub_short_code_btn_text']; ?>" maxlength="25">
                <p class="description">
                    Enter the text you would like to display on the 'Subscribe' button
                    that's added to your webpage using the susbscription short code.
                    The default text is "SIGN UP" however you can add any text you would
                    like up to limit of 25 characters.
                </p>
              </td>
            </tr>
            <tr>
              <th scope="row">
                <label for="sub-short-code-redirect-url">Redirect URL Slug</label>
              </th>
              <td>
                <input type="text" name="sub_short_code_redirect_url" id="sub-short-code-redirect-url" class="regular-text" value="<?php echo $options['sub_short_code_redirect_url']; ?>" maxlength="25">
                <p class="description">
                    You can set the URL that a user will be redirected to following
                    successful purchase of their subscription plan. This URL can be
                    added as a URL slug. If no URL is set, the default will be to redirect users to your home page.
                </p>
              </td>
            </tr>
            <tr>
              <th scope="row">
                <label for="sub-short-code">Subscribe Short Code</label>
              </th>
              <td>
                <p>
                    <b>[subscribe]</b>
                </p>
                <p class="description">
                    Copy the '[subscribe]' shortcode above and paste it onto any page or post
                    on your WordPress website. It will add a button to that page that allows
                    your users to go through the subscription sign up flow.
                </p>
              </td>
            </tr>
          </tbody>
        </table>
        <p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="Save Changes"></p>
    </form>
</div>
