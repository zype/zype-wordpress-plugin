<div class="content-wrap zype-form-center">
    <div id="payment-wrapper">
        <div class="main-heading inner-heading">
            <h1 class="title text-uppercase zype-title">Enter your billing info</h1>
        </div>
        <div class="user-wrap">
            <div class="holder-main">
                <div class="row payment-row">
                    <div class="">
                        <input type="hidden" name="action" value="zype_plans">
                        <div class="holder">
                            <input type="hidden" name="action" value="zype_checkout">
                            <form id="payment-form">
                                <input name="transaction_type" type="hidden" value="<?php echo $transaction_type; ?>">
                                <input name="video_id" type="hidden" value="<?php echo $video_id; ?>">
                                <input name="plan_id" type="hidden" value="<?php echo $plan->_id; ?>">
                                <input name="pass_plan_id" type="hidden" value="<?php echo $pass_plan->_id; ?>">                                    
                                <input name="email" type="hidden" value="<?php zype_current_consumer(); ?>">
                                <input name="stripe_card_token" type="hidden">
                                <input name="braintree_payment_nonce" type="hidden">

                                <p class="checkout_error" style='color: red'></p>
                                <?php if (!empty($braintree_token)): ?>
                                    <input name="type" type="hidden" value="braintree">
                                    <div id="braintree-form"></div>
                                <?php elseif ((!empty($plan->stripe_id)) || ($transaction_type !== ZypeMedia\Controllers\Consumer\Monetization::SUBSCRIPTION)): ?>
                                    <input name="type" type="hidden" value="stripe">
                                    <div id="stripe-form">
                                        <p class="form-group required-row zype-input-wrap">
                                            <input type="text" maxlength="16"
                                                    oninput="this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');"
                                                    placeholder="Card number"
                                                    class="zype-input-text zype-card-number">
                                        </p>
                                        <p class="form-group required-row zype-input-wrap">
                                            <input maxlength="4"
                                                    oninput="this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');"
                                                    type="text" placeholder="CVC"
                                                    class="zype-input-text zype-card-cvc">
                                            <input type="text" placeholder="MM/YY"
                                                    class="zype-input-text zype-card-date">
                                        </p>
                                    </div>
                                <?php endif ?>

                                <div class="zype-buttons-row">
                                    <div class="zype-buttons-column">
                                        <button type="button" class="zype_monetization_checkout zype-button"
                                                data-type="paywall"
                                                data-video-id="<?php echo esc_attr($video_id) ?>"
                                                data-root-parent="<?php echo $root_parent; ?>">Go back
                                        </button>
                                    </div>

                                    <div class="zype-buttons-column">
                                        <button type="submit" class="zype-checkout-button zype-button"
                                            data-transaction-type="<?php echo $transaction_type; ?>"
                                            data-description="<?php echo $plan->name; ?>"
                                            data-interval="<?php echo $plan->interval; ?>"
                                            data-amount="<?php echo $plan->amount; ?>"
                                            disabled
                                        >
                                            Continue
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

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
        <?php if (!empty($braintree_token)): ?>
            var ifFastPay = true;
            var payloadNonce = false;
            braintree.dropin.create({
                authorization: '<?php echo $braintree_token ?>',
                container: '#braintree-form',
                paypal: {
                    flow: 'vault'
                }
            }, function (createErr, instance) {
                if (createErr) {
                    console.error(createErr);
                    return;
                }

                $(".zype-checkout-button").prop('disabled', false);

                instance.on('noPaymentMethodRequestable', function (event) {
                });

                instance.on('paymentOptionSelected', function (event) {
                    if (event.paymentOption) {
                        payloadNonce = false;
                        ifFastPay = false;
                    }
                });

                instance.on('paymentMethodRequestable', function (event) {
                    if (!event.paymentMethodIsSelected) {
                        payloadNonce = false;
                    }
                });

                $(".zype-checkout-button").click(function (e) {
                    e.preventDefault();

                    $(this).append('<i class="zype-spinner"></i>');
                    $(".zype-checkout-button").prop('disabled', true);

                    if (ifFastPay && instance.isPaymentMethodRequestable() && !payloadNonce) {
                        ifFastPay = false;
                        instance.requestPaymentMethod(function (requestPaymentMethodErr, payload) {
                            if (payload && typeof payload.nonce != 'undefined') {
                                payloadNonce = payload.nonce;
                                $('#payment-form').find('input[name="braintree_payment_nonce"]').val(payload.nonce);
                                sendPaymentRequest();
                            } else {
                                $(".zype-checkout-button").prop('disabled', false);
                                $('.zype-spinner').remove();
                            }
                        });

                        return;
                    }

                    ifFastPay = false;

                    if (payloadNonce) {
                        sendPaymentRequest();
                    } else {
                        instance.requestPaymentMethod(function (requestPaymentMethodErr, payload) {
                            if (payload && typeof payload.nonce != 'undefined') {
                                payloadNonce = payload.nonce;
                                $('#payment-form').find('input[name="braintree_payment_nonce"]').val(payload.nonce);
                            }

                            $(".zype-checkout-button").prop('disabled', false);
                            $('.zype-spinner').remove();
                        });
                    }
                });
            });

        <?php elseif ((!empty($plan->stripe_id)) || ($transaction_type !== ZypeMedia\Controllers\Consumer\Monetization::SUBSCRIPTION)): ?>
            var currentCCMask = "9999 9999 9999 9999";
            $(".zype-card-date").mask("99/99");
            $(".zype-card-number").mask("9999 9999 9999 9999", { autoclear: false });
            $(".zype-card-number").on('keyup', function (e) {
                cc = e.currentTarget.value.replace(/\D/g, '')
                mask = getCardType(cc).mask;
                if(mask !== currentCCMask) {
                    $(".zype-card-number").mask(mask, { autoclear: false });
                    e.currentTarget.setSelectionRange(cc.length, cc.length);
                    currentCCMask = mask;
                }
            });

            Stripe.setPublishableKey('<?php echo $stripe_pk ?>');
            $(".zype-checkout-button").prop('disabled', false);

            $(".zype-checkout-button").click(function (e) {
                e.preventDefault();
                var stripeForm = $(this).closest('#payment-form').children('#stripe-form');

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
                    $('.checkout_error').text(response.error.message);
                    $('.zype-checkout-button').prop('disabled', false);
                    $('.zype-spinner').remove();
                } else {
                    $('#payment-form input[name="stripe_card_token"]').val(response.id);
                    sendPaymentRequest();
                }
            }
        <?php endif ?>

        function sendPaymentRequest() {
            $('.checkout_error').text('');
            var transaction_type = $(".zype-checkout-button").data('transaction-type');
            var subscription_type = "<?php echo ZypeMedia\Controllers\Consumer\Monetization::SUBSCRIPTION;?>";
            var url = '';
            if(subscription_type == transaction_type) {
                url = "<?php zype_url('subscribe');?>/submit";
            }
            else {
                url = "<?php zype_url('transaction');?>/submit"
            }

            $.ajax({
                url: url,
                type: 'post',
                data: $('#payment-form').serialize(),
                dataType: 'json',
                encode: true
            }).done(function (data) {
                if (typeof data.errors != 'undefined') {
                    $.each(data.errors, function (index, value) {
                        $('.checkout_error').append(value + "<br/>");
                    });
                    $('.zype-checkout-button').prop('disabled', false);
                    $('.zype-spinner').remove();                    
                    return;
                }
                if (data.success) {
                    $('#payment-wrapper .main-heading .title').text('Thanks for your payment!');
                    $('#payment-wrapper .payment-row').html('<p class="to-sign-up">You\'ve successfully unlocked your content. Enjoy!</p><button class="zype-button" id="zype_modal_close">Let\'s starting watching</button><input type="hidden" class="close_reload" value="reload">');
                }
                $('.zype-checkout-button').prop('disabled', false);
                $('.zype-spinner').remove();
             }).fail(function (data) {
                $('.zype-checkout-button').prop('disabled', false);
                $('.zype-spinner').remove();
                console.log(data.errors);
            });
        }

        $(document).on('click', '#zype_modal_close', function(e) {
            e.preventDefault();
            var url = '<?php echo $redirect_url ?>';
            if (url.length > 0) {
                window.location.replace(url);
            } else {
                window.location.reload();
            }
        });

        $(window).on('popstate', function () {
            handler.close();
        });
    });
</script>
