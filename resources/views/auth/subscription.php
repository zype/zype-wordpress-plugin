<?php get_header(); ?>
<?php
    $id = 'zype-profile-sub-' . $zd['consumer_id'] . '-' . (time() * rand(1, 1000000));
?>
<div class="signup-wrap user-action-wrap container user-profile-wrap" id="<?php echo $id; ?>">
    <div class="main-heading inner-heading">
        <strong class="title text-uppercase">My Account | Subscription</strong>
    </div>
    <div class="user-wrap">
        <div class="holder-main">
            <div class="user-profile-wrap__content">
                <div class="user-profile-wrap__block">
                    <ul class="user-action user-profile-wrap__menu">
                        <li class="profile">
                            <a href="<?php zype_url('profile'); ?>/">
                                <span class="ico"><i class="fa fa-fw fa-user"></i></span>
                                <span class="text">Profile</span>
                            </a>
                        </li>
                        <li class="change-password">
                            <a href="<?php zype_url('profile'); ?>/change-password/">
                                <span class="ico"><i class="fa fa-fw fa-lock"></i></span>
                                <span class="text">Change Password</span>
                            </a>
                        </li>
                        <li class="change-credit-card">
                            <a href="<?php zype_url('profile'); ?>/change-credit-card/">
                                <span class="ico"><i class="fa fa-fw fa-credit-card"></i></span>
                                <span class="text">Change Credit Card</span>
                            </a>
                        </li>
                        <li class="subscription active">
                            <a href="<?php zype_url('profile'); ?>/subscription/">
                                <span class="ico"><i class="fa fa-fw fa-dollar"></i></span>
                                <span class="text">Subscription</span>
                            </a>
                        </li>
                        <li class="link-device">
                            <a href="<?php zype_url('device_link'); ?>/">
                                <span class="ico"><i class="fa fa-fw fa-link"></i></span>
                                <span class="text">Link Device</span>
                            </a>
                        </li>
                        <li class="log-out">
                            <a href="<?php echo home_url($options['logout_url']) ?>/">
                                <span class="ico"><i class="fa fa-fw fa-sign-out"></i></span>
                                <span class="text">Logout</span>
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="user-profile-wrap__block user-profile-wrap__block_subscribe">
                    <div class="subscription-content">
                        <?php if (!empty($zd['subscription'])) { ?>
                            <div class="slot">
                                <strong class="title">Subscription Plan: </strong>
                                <p><?php echo $zd['current_plan']->name; ?>, $<?php echo $zd['current_plan']->amount; ?>
                                    every <?php if ($zd['current_plan']->interval_count > 1) {
                                        echo $zd['current_plan']->interval_count . ' ';
                                    } ?><?php echo $zd['current_plan']->interval; ?><?php if ($zd['current_plan']->interval_count > 1) {
                                        echo 's';
                                    } ?></p>
                                <br>
                            </div>
                            <?php if (!empty($zd['subscription']->stripe_id) || !empty($zd['subscription']->braintree_id)) { ?>
                                <div class="slot">
                                    <strong class="title">Change Plan: </strong>
                                    <p>Note: After changing your plan your billing will automatically update to reflect
                                        the new cost and billing period using the payment information you currently have
                                        on file.</p>
                                    <br>
                                    <p>
                                    <form action="<?php zype_url('profile'); ?>/subscription/change/" method="post">
                                        <div class="field-section">
                                            <div class="zype_flash_messages"></div>
                                            <input type="hidden" name="subscription_id"
                                                   value="<?php echo $zd['subscription']->_id; ?>">
                                            <div class="form-group">
                                                <select name="new_plan_id" class="form-control">
                                                    <?php foreach ($zd['plans'] as $a_plan) { ?>
                                                        <option value="<?php echo $a_plan->_id; ?>"
                                                                <?php if ($a_plan->_id == $zd['current_plan']->_id){ ?>selected="selected"<?php } ?>>
                                                            <?php echo $a_plan->name; ?>,
                                                            $<?php echo $a_plan->amount; ?>
                                                            /<?php if ($a_plan->interval_count > 1) {
                                                                echo $a_plan->interval_count . ' ';
                                                            } ?><?php echo $a_plan->interval; ?><?php if ($a_plan->interval_count > 1) {
                                                                echo 's';
                                                            } ?>
                                                        </option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                            <div class="btn-holder">
                                                <input type="submit"
                                                       class="btn btn-sm btn-success user-profile-wrap__button user-profile-wrap__button_subscr zype-custom-button"
                                                       value="Update Subscription">
                                            </div>
                                        </div>
                                    </form>
                                    </p>
                                </div>
                            <?php } ?>
                            <div class="slot">
                                <strong class="title">Cancel Subscription: </strong>
                                <?php if (empty($zd['subscription']->stripe_id)) { ?>
                                    <p>If you cancel your subscription your subscription will terminate immediately and you will not be refunded a prorated amount.</p>
                                    <p>You cannot undo this action.</p>
                                <?php } ?>
                                <br>
                                <a class="btn btn-sm btn-danger user-profile-wrap__button user-profile-wrap__button_subscr zype-custom-button"
                                   href="<?php zype_url('profile'); ?>/subscription/cancel/">Cancel Subscription</a>
                            </div>
                        <?php } else { ?>
                            <p>You do not currently have a subscription.</p>
                            <p>
                                <a href="" class="zype_auth_markup zype-signin-button" data-type="plans" data-root-parent="<?php echo $id; ?>">Click here to subscribe.</a>
                            </p>

                            <div class="player-auth-required zype-custom-modal">
                                <i id="zype_video__auth-close" class="fa fa-3x fa-times"></i>
                                <div class="player-auth-required-content">
                                    <div class="login-sub-section">
                                        <?php if (!\Auth::logged_in()): ?>
                                            <?php echo do_shortcode('[zype_auth]'); ?>
                                            <?php echo do_shortcode('[zype_signup]'); ?>
                                        <?php else: ?>
                                            <div id="plans">
                                                <?php echo do_shortcode('[zype_auth type="plans" root_parent='. $id . ']'); ?>
                                            </div>
                                        <?php endif ?>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    (function ($) {
        //stripe
        var form_id;
        var handler = StripeCheckout.configure({
            key: '<?php echo $zd['stripe_pk']; ?>',
            token: function (token) {
                $(form_id + ' input[name="email"]').val(token.email);
                $(form_id + ' input[name="stripe_card_token"]').val(token.id);
                $(form_id).submit();
            }
        });

        $('.customButton').on('click', function (e) {
            form_id = '#' + $(this).parents('form:first').attr('id');

            handler.open({
                name: '<?php bloginfo('name'); ?>',
                description: 'Update Card Details',
                panelLabel: 'Update Card Details',
                email: '<?php echo $zd['email']; ?>',
            });
            e.preventDefault();
        });

        // Close Checkout on page navigation
        $(window).on('popstate', function () {
            handler.close();
        });

        $(document).ready(function () {
            $(document).on('click', '.zype-signin-button', function (e) {
                e.preventDefault();
                $('#zype-modal-auth').show();
                $('#zype-modal-signup').hide();
                $('#zype-modal-forgot').hide();
            });

            $('.button-disableable').submit(function () {
                $(this).children('.button-form-disabler').show();
            });
        });

        $(document).on('click', '.zype-join-button, .zype-signin-button', function () {
            $('.player-auth-required').fadeIn();
            $('.player-auth-required-content').css('top', '10%');
        });

        $(document).on('click', '#zype_video__auth-close, #zype_modal_close', function (e) {
            $('.player-auth-required-content').css('top', '-50%');
            $('.player-auth-required').fadeOut();

            if ($('.close_reload').val() === 'reload') {
                location.reload();
            }
        });

    })(jQuery);
</script>
<?php get_footer(); ?>
