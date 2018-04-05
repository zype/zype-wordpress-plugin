<div class="content-wrap zype-form-center">
    <?php if (empty($plan->stripe_id) && empty($plan->braintree_id)): ?>
        <div id="choose-wrapper">
            <div class="main-heading inner-heading">
                <h1 class="title text-uppercase zype-title">Sorry, but this plan a temporarily unavailable</h1>
            </div>
            <div class="user-wrap">
                <div class="holder-main">
                    <div class="row">
                        <div class="">
                            <button type="button" class="zype_auth_markup zype-button" data-type="plans">Go back</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php else: ?>
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
                                    <input name="plan_id" type="hidden" value="<?php echo $plan->_id; ?>">
                                    <input name="email" type="hidden" value="<?php zype_current_consumer(); ?>">
                                    <input name="stripe_card_token" type="hidden">
                                    <input name="braintree_payment_nonce" type="hidden">

                                    <p class="checkout_error" style='color: red'></p>
                                    
                                    <?php if (!empty($plan->braintree_id)): ?>
                                        <input name="type" type="hidden" value="braintree">
                                        <div id="braintree-form"></div>
                                    <?php elseif (!empty($plan->stripe_id)): ?>
                                        <input name="type" type="hidden" value="stripe">
                                        <div id="stripe-form">
                                            <p class="form-group required-row zype-input-wrap">
                                                <input type="text" maxlength="16" oninput="this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');"  placeholder="Card number" class="zype-input-text" id="zype-card-number">
                                            </p>
                                            <p class="form-group required-row zype-input-wrap">
                                                <input maxlength="4" oninput="this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');" type="text" placeholder="CVC" class="zype-input-text" id="zype-card-cvc">
                                                <input type="text" placeholder="MM/YY" class="zype-input-text" id="zype-card-date">
                                            </p>
                                        </div>
                                    <?php endif ?>
                                    
                                    <div class="zype-buttons-row">
                                        <div class="zype-buttons-column">
                                            <button type="button" class="zype_auth_markup zype-button" data-type="plans">Go back</button>
                                        </div>

                                        <div class="zype-buttons-column">
                                            <button type="submit" class="zype-checkout-button zype-button" data-description="<?php echo $plan->name; ?>" data-interval="<?php echo $plan->interval; ?>" data-amount="<?php echo $plan->amount; ?>" disabled>Continue</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif ?>
</div>

<script>
  jQuery(document).ready(function($){
    <?php if (!empty($plan->braintree_id)): ?>
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

            instance.on('noPaymentMethodRequestable', function (event) {});

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

            $(".zype-checkout-button").click(function(e) {
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

    <?php elseif (!empty($plan->stripe_id)): ?>
        $("#zype-card-date").mask("99/99");
        $("#zype-card-number").mask("9999 9999 9999 9999");
        
        Stripe.setPublishableKey('<?php echo $stripe_pk ?>');
        $(".zype-checkout-button").prop('disabled', false);

        $(".zype-checkout-button").click(function(e) {
            e.preventDefault();

            $(this).prop('disabled', true).append('<i class="zype-spinner"></i>');

            var cardDate = $('#zype-card-date').val();
            Stripe.card.createToken({
                number: $('#zype-card-number').val(),
                cvc: $('#zype-card-cvc').val(),
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

        $.ajax({
            url: "<?php zype_url('subscribe');?>/submit",
            type: 'post',
            data: $('#payment-form').serialize(),
            dataType: 'json',
            encode: true
        }).done(function(data) {
            if (typeof data.errors != 'undefined') {
                $.each(data.errors, function(index, value) {
                    $('.checkout_error').append(value + "<br/>");
                });
                return;
            }

            if (data.success) {
                $('#payment-wrapper .main-heading .title').text('Thanks for your payment!');
                $('#payment-wrapper .payment-row').html('<p class="to-sign-up">You\'ve successflly unlocked your content. Enjoy!</p><button type="submit" class="zype-button" id="zype_modal_close">Let\'s starting watching</button><input type="hidden" class="close_reload" value="reload">');
            }

            $('.zype-checkout-button').prop('disabled', false);
            $('.zype-spinner').remove();
        }).fail(function(data) {
            $('.zype-checkout-button').prop('disabled', false);
            $('.zype-spinner').remove();
            console.log(data.errors);
        });
    }

    $(window).on('popstate', function() {
        handler.close();
    });
  });
</script>
