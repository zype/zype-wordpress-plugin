<?php
if (!defined('ABSPATH'))
    die();
?>

<div class="wrap zype-admin">
    <h2><?php echo get_admin_page_title(); ?></h2>
    <p>Any active <a href="https://admin.zype.com/plans" target="_blank">subscription
        plans created in your Zype account</a> can be made available for
        purchase on your WordPress website.
        Simply click below on each plan you would like to feature for sale on
        any subscription video content on your website. (Note, you may select multiple
        plans by cmd+click / ctrl+click on multiple selections.)</p>
    <form method="post" action="<?php echo admin_url('admin.php'); ?>">
        <input type="hidden" name="action" value="zype_braintree"/>
        <?php wp_nonce_field('zype_braintree'); ?>

        <h3>Subscription Plans</h3>
        <p>
          You can <a href="https://admin.zype.com/plans/new" target="_blank">easily create additional subscription plans</a>
          in Zype if you don’t see an appropriate one below.
        </p>
        <select multiple="multiple" name="subscribe[]">
            <?php foreach ($plans as $plan) : ?>
                <option
                    <?php if (isset($options['subscribe_select']) && is_array($options['subscribe_select']) && in_array($plan->_id, $options['subscribe_select'])) {
                        echo 'selected="selected"';
                    } ?>

                        id="<?php echo $plan->_id ?>"
                        value="<?php echo $plan->_id ?>"><?php echo $plan->name; ?></option>
            <?php endforeach; ?>
        </select>

        <h3>Pass Plans</h3>
        <p>
            You can <a href="https://admin.zype.com/pass_plans/new" target="_blank">easily create additional pass plans</a>
            in Zype if you don’t see an appropriate one below.
        </p>
        <select multiple="multiple" name="pass_plans[]">
            <?php foreach ($pass_plans as $plan) : ?>
                <option
                    <?php if (isset($options['pass_plans_select']) && is_array($options['pass_plans_select']) && in_array($plan->_id, $options['pass_plans_select'])) {
                        echo 'selected="selected"';
                    } ?>

                        id="<?php echo $plan->_id ?>"
                        value="<?php echo $plan->_id ?>"><?php echo $plan->name; ?></option>
            <?php endforeach; ?>
        </select>
        <section class='subscription-short-code-section'>
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
                            that's added to your webpage using the subscription short code.
                            The default text is "SIGN UP" however you can add any text you would
                            like up to limit of 25 characters.
                        </p>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="sub-short-code-redirect-url">Subscription Purchase Redirect URL Slug</label>
                    </th>
                    <td>
                        <input type="text" name="sub_short_code_redirect_url" id="sub-short-code-redirect-url" class="regular-text" value="<?php echo $options['sub_short_code_redirect_url']; ?>" maxlength="256">
                        <p class="description">
                            You can set the URL that a user will be redirected to following
                            successful purchase of their subscription plan. This URL can be
                            added as a URL slug. If no URL is set, the default will be to reload the current page.
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
                <tr>
                    <th scope="row">
                        <label for="sub-short-code-text-after-sub">Post Subscription Button Text</label>
                    </th>
                    <td>
                        <input type="text" name="sub_short_code_text_after_sub" id="sub-short-code-text-after-sub" class="regular-text" value="<?php echo $options['sub_short_code_text_after_sub']; ?>" maxlength="25">
                        <p class="description">
                            After successfully subscribing, the button will be updated so users are sent to your /profile page.
                            Enter the text you would like to display on the button AFTER a consumer successfully completes a subscription.
                            This text will replace the initial "Subscribe Short Code Button Text" upon successful subscription purchase.
                            The default text is "MY ACCOUNT" however you can add any text up to a limit of 25 characters.
                        </p>
                    </td>
                </tr>
                </tbody>
            </table>
        </section>
        <section class='my-library-short-code-section'>
            <h2>My Library Short Code</h2>
            <p>
                The 'My Library' short code allows you to showcase all videos that a consumer has purchased or rented in a single view.
            </p>
            <table class="form-table">
                <tbody>
                    <tr>
                        <th scope="row">
                            <label>Short Code</label>
                        </th>
                        <td>
                            <p>
                                <b>[zype_my_library]</b>
                            </p>
                            <p class="description">
                                Copy the '[zype_my_library]' shortcode above and paste it onto any page or post
                                on your WordPress website.
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="my-library-sort">Select the order you would like to display videos on the "My Library" page for viewers:</label>
                        </th>
                        <td>
                            <select multiple="multiple" name="my_library_sort">
                                <?php foreach ($options['my_library_sort_options'] as $key => $value) : ?>
                                    <option
                                        <?php if (isset($options['my_library']['sort']) && $key == $options['my_library']['sort']) {
                                            echo 'selected="selected"';
                                        } ?>
                                            id="<?php echo $key ?>"
                                            value="<?php echo $key ?>"><?php echo $value['title']; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="my-library-pagination">Pagination:</label>
                        </th>
                        <td>
                            <input
                                type="checkbox"
                                name="my_library_pagination"
                                <?php echo $options['my_library']['pagination'] ? 'checked="checked"' : ''; ?>
                            >
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="my-library-sign-in-text">
                                Sign in text
                            </label>
                        </th>
                        <td>
                            <input
                                type="text"
                                name="my_library_sign_in_text"
                                value="<?php echo $options['my_library']['sign_in_text']; ?>"
                            >
                            <p class="description">
                                If the user is not logged in, a "Sign In" button and call to action will be displayed with the text typed above.
                            </p>
                        </td>
                    </tr>
                </tbody>
            </table>
        </section>
        <section class='stripe-section'>
            <h2>Stripe</h2>
            <table class="form-table">
                <tbody>
                    <tr>
                    <th scope="row">
                        <label for="stripe-coupon-enabled">Coupons:</label>
                    </th>
                    <td>
                        <input type="checkbox" name="stripe_coupon_enabled" id="stripe-coupon-enabled"
                                class="regular-checkbox"<?php echo $options['stripe']['coupon_enabled'] ? 'checked="checked"' : ''; ?>>
                        <p class="description">
                            Leave checked if you would like to enabled Stripe coupons.
                        </p>
                    </td>
                    </tr>
                </tbody>
            </table>
        </section>
        <p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="Save Changes"></p>
    </form>
</div>
