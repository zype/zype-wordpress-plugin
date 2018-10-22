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
    function getCardType(number)
    {

        // visa
        var re = new RegExp("^4");
        if (number.match(re) != null)
            return {
                type: "Visa",
                mask: "9999 9999 9999 9999"
            };

        // Mastercard
        // Updated for Mastercard 2017 BINs expansion
        if (/^(5[1-5][0-9]{14}|2(22[1-9][0-9]{12}|2[3-9][0-9]{13}|[3-6][0-9]{14}|7[0-1][0-9]{13}|720[0-9]{12}))$/.test(number))
            return {
                type: "Mastercard",
                mask: "9999 9999 9999 9999"
            };

        // AMEX
        re = new RegExp("^3[47]");
        if (number.match(re) != null)
            return {
                type: "AMEX",
                mask: "9999 999999 99999"
            };

        // Discover
        re = new RegExp("^(6011|622(12[6-9]|1[3-9][0-9]|[2-8][0-9]{2}|9[0-1][0-9]|92[0-5]|64[4-9])|65)");
        if (number.match(re) != null)
            return {
                type: "Discover",
                mask: "9999 9999 9999 9999"
            };

        // Diners
        re = new RegExp("^36");
        if (number.match(re) != null)
            return {
                type: "Diners",
                mask: "9999 9999 9999 99"
            };

        // Diners - Carte Blanche
        re = new RegExp("^30[0-5]");
        if (number.match(re) != null)
            return {
                type: "Diners - Carte Blanche",
                mask: "9999 9999 9999 99"
            };

        // JCB
        re = new RegExp("^35(2[89]|[3-8][0-9])");
        if (number.match(re) != null)
            return {
                type: "JCB",
                mask: "9999 9999 9999 9999"
            };

        // Union Pay
        re = new RegExp("^(62[0-9]{14,17})$");
        if (number.match(re) != null)
            return {
                type: "Union Pay",
                mask: "9999 9999 9999 9999"
            };

        return "";
    }

    jQuery(document).ready(function ($) {
        var currentCCMask = "9999 9999 9999 9999";
        $("#change-credit-card-form .zype-card-date").mask("99/99");
        $("#change-credit-card-form .zype-card-number").mask("9999 9999 9999 9999", { autoclear: false });
        $("#change-credit-card-form .zype-card-number").on('keyup', function (e) {
            cc = e.currentTarget.value.replace(/\D/g, '');
            mask = getCardType(cc).mask;
            if(mask !== currentCCMask) {
                $(".zype-card-number").mask(mask, { autoclear: false });
                e.currentTarget.setSelectionRange(cc.length, cc.length);
                currentCCMask = mask;
            }
        });

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
