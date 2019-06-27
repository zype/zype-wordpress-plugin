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
                        <li class="log-out">
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
                                        <input type="text"
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
                                        class="btn btn-sm btn-success user-profile-wrap__button user-profile-wrap__button_subscr zype-custom-button"
                                        value="Update Credit Card" disabled>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>


<script>
    function mask() {
        return [
            // american express
            {
                mask: '0000 000000 00000',
                regex: '^3[47]\\d{0,13}',
                lazy: false
            },
            // discover
            {
                mask: '0000 0000 0000 0000',
                regex: '^(?:6011|65\\d{0,2}|64[4-9]\\d?)\\d{0,12}',
                lazy: false
            },
            // diners
            {
                mask: '0000 000000 0000',
                regex: '^3(?:0([0-5]|9)|[689]\\d?)\\d{0,11}',
                lazy: false
            },
            // mastercard
            {
                mask: '0000 0000 0000 0000',
                regex: '^(5[1-5]\\d{0,2}|22[2-9]\\d{0,1}|2[3-7]\\d{0,2})\\d{0,12}',
                lazy: false
            },
            // jcb15
            {
                mask: '0000 000000 00000',
                regex: '^(?:2131|1800)\\d{0,11}',
                lazy: false
            },
            // jcb
            {
                mask: '0000 0000 0000 0000',
                regex: '^(?:35\\d{0,2})\\d{0,12}',
                lazy: false
            },
            // maestro
            {
                mask: '0000 0000 0000 0000',
                regex: '^(?:5[0678]\\d{0,2}|6304|67\\d{0,2})\\d{0,12}',
                lazy: false
            },
            // visa
            {
                mask: '0000 0000 0000 0000',
                regex: '^4\\d{0,15}',
                lazy: false
            },
            // unionpay
            {
                mask: '0000 0000 0000 0000',
                regex: '^62\\d{0,14}',
                lazy: false
                // Unknown
            },
            {
                mask: '0000 0000 0000 0000',
                lazy: false
            }
        ];
    };

    jQuery(document).ready(function ($) {
        var cardnumberMask = new IMask($("#change-credit-card-form .zype-card-date")[0], {
            mask: 'MM/YY',
            blocks: {
                MM: {
                    mask: IMask.MaskedRange,
                    from: 1,
                    to: 12
                },
                YY: {
                    mask: '00',
                }
            }
        });
        var cardnumberMask = new IMask($("#change-credit-card-form .zype-card-number")[0], {
            mask: mask(),
            dispatch: function (appended, dynamicMasked) {
                var number = (dynamicMasked.value + appended).replace(/\D/g, '');

                return dynamicMasked.compiledMasks.find(function (m) {
                    var re = new RegExp(m.regex);
                    return number.match(re) !== null;
                });
            }
        });

        Stripe.setPublishableKey('<?php echo $stripe_pk ?>');
        $("#change-credit-card-form .btn-holder input[type='submit']").prop('disabled', false);

        $("#change-credit-card-form .btn-holder input[type='submit']").click(function (e) {
            e.preventDefault();
            $('.request-error').text('');
            var stripeForm = $(this).closest('#change-credit-card-form').find('#stripe-form');

            $(this).prop('disabled', true).append('<div class="zype-spinner"></div>');

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

<?php get_footer(); ?>
