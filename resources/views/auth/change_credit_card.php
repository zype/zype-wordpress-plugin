<?php get_header(); ?>
<div class="content-wrap user-action-wrap user-profile-wrap">
    <div class="main-heading inner-heading">
        <strong class="title text-uppercase">My Account | Change Credit Card</strong>
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
                        <li class="change-credit-card active">
                            <a href="<?php zype_url('profile_url'); ?>/change-credit-card/">
                                <span class="ico"><i class="fa fa-fw fa-credit-card"></i></span>
                                <span class="text">Change Credit Card</span>
                            </a>
                        </li>
                        <li class="subscription">
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
                        <li class="link-device">
                            <a href="<?php echo home_url(\Config::get('zype.logout_url')) ?>/">
                                <span class="ico"><i class="fa fa-fw fa-sign-out"></i></span>
                                <span class="text">Logout</span>
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="user-profile-wrap__block">
                    <form action="<?php zype_url('profile'); ?>/change-credit-card/submit" method="post" id="change-credit-card-form">
                        <div class="field-section">
                            <div class="zype_flash_messages"></div>
                            <div class="form-group user-profile-wrap__field">
                                <p class="request-error" style='color: red'></p>
                                <input name="type" type="hidden" value="stripe">
                                <input name="stripe_card_token" type="hidden">
                                <div id="stripe-form">
                                    <p class="form-group required-row zype-input-wrap">
                                        <input type="text" maxlength="16"
                                                oninput="this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');"
                                                placeholder="Card number"
                                                class="zype-input-text zype-card-number form-control user-profile-wrap__inp">
                                    </p>
                                    <p class="form-group required-row zype-input-wrap">
                                        <input maxlength="4"
                                                oninput="this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');"
                                                type="text" placeholder="CVC"
                                                class="zype-input-text zype-card-cvc form-control user-profile-wrap__inp">
                                        <input type="text" placeholder="MM/YY"
                                                class="zype-input-text zype-card-date form-control user-profile-wrap__inp">
                                    </p>
                                </div>
                            </div>
                            <div class="btn-holder">
                                <input type="submit"
                                        class="btn btn-sm btn-success user-profile-wrap__button user-profile-wrap__button_subscr"
                                        value="Update Credit Card" disabled>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php get_footer(); ?>

<script>
    jQuery(document).ready(function ($) {
        $("#change-credit-card-form .zype-card-date").mask("99/99");
        $("#change-credit-card-form .zype-card-number").mask("9999 9999 9999 9999");

        Stripe.setPublishableKey('<?php echo $stripe_pk ?>');
        $("#change-credit-card-form .btn-holder input[type='submit']").prop('disabled', false);

        $("#change-credit-card-form .btn-holder input[type='submit']").click(function (e) {
            e.preventDefault();
            $('.request-error').text('');
            var stripeForm = $(this).closest('#change-credit-card-form').find('#stripe-form');

            $(this).prop('disabled', true).append('<i class="zype-spinner"></i>');

            var cardDate = stripeForm.find('.zype-card-date').val();
            var cardNumber = stripeForm.find('.zype-card-number').val();
            var cardCVC = stripeForm.find('.zype-card-cvc').val();

            Stripe.card.createToken({
                number: cardNumber,
                cvc: cardCVC,
                exp_month: cardDate.split('/')[0],
                exp_year: cardDate.split('/')[1],
            }, stripeTokenHandler);

            return false;
        });

        function stripeTokenHandler(status, response) {
            if (response.error) {
                $('.request-error').text(response.error.message);
                $('#change-credit-card-form .btn-holder input[type="submit"]').prop('disabled', false);
                $('.zype-spinner').remove();
            } else {
                $('#change-credit-card-form input[name="stripe_card_token"]').val(response.id);
                $("#change-credit-card-form").submit();
            }
        }
    });
</script>
