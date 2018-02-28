<div class="content-wrap zype-form-center">
    <div id="choose-wrapper">
        <div class="main-heading inner-heading">
            <h1 class="title text-uppercase zype-title">Choose your payment method</h1>
        </div>
        <div class="user-wrap">
            <div class="holder-main">
                <div class="row">
                    <div class="col-sm-6">
                        <button type="button" class="payment_method_select" data-method="stripe">Stripe</button>
                        <button type="button" class="payment_method_select" data-method="braintree">Braintree</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="payment-wrapper" style="display:none">
        <div class="main-heading inner-heading">
            <h1 class="title text-uppercase zype-title">Enter your billing info</h1>
        </div>
        <div class="user-wrap">
            <div class="holder-main">
                <div class="row payment-row">
                    <div class="col-sm-6">
                        <input type="hidden" name="action" value="zype_plans">

                        <div class="holder">
                            <input type="hidden" name="action" value="zype_checkout">
                            <form id="payment-form">
                                <input name="plan_id" type="hidden" value="<?php echo $plan->_id; ?>">
                                <input name="email" type="hidden" value="<?php zype_current_consumer(); ?>">
                                <input name="type" type="hidden" value="stripe">
                                <input name="stripe_card_token" type="hidden">
                                <input name="braintree_payment_nonce" type="hidden">

                                <p class="checkout_error" style='color: red'></p>

                                <div id="stripe-form" style="display:none">
                                    <p class="form-group required-row zype-input-wrap">
                                        <input type="text" maxlength="16" oninput="this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');"  placeholder="Card number" class="zype-input-text" id="zype-card-number">
                                    </p>
                                    <p class="form-group required-row zype-input-wrap">
                                        <input maxlength="4" oninput="this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');" type="text" placeholder="CVC" class="zype-input-text" id="zype-card-cvc">
                                        <input type="text" placeholder="MM/YY" class="zype-input-text" id="zype-card-date">
                                    </p>
                                </div>

                                <div id="braintree-form" style="display:none">
                                </div>

                                <button type="submit" class="zype-checkout-button zype-button" data-description="<?php echo $plan->name; ?>" data-interval="<?php echo $plan->interval; ?>" data-amount="<?php echo $plan->amount; ?>">Continue</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
  jQuery(document).ready(function($){
    $(document).on('click', '.payment_method_select', function(e) {
        $('#payment-form').find('input[name="type"]').val($(this).data('method'));
        $('#choose-wrapper').hide();
        $('#payment-wrapper').show();
        $('#'+$(this).data('method')+'-form').show();
    });

    $("#zype-card-date").mask("99/99");
    $("#zype-card-number").mask("9999 9999 9999 9999");
    
    Stripe.setPublishableKey('<?php echo \Config::get('zype.stripe_pk') ?>');

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

        $(".zype-checkout-button").click(function(e) {
            e.preventDefault();

            if ($('#payment-form').find('input[name="type"]').val() == 'braintree') {
                if (payloadNonce) {
                    sendPaymentRequest();
                } else {
                    instance.requestPaymentMethod(function (requestPaymentMethodErr, payload) {
                        payloadNonce = payload.nonce;
                        $('#payment-form').find('input[name="braintree_payment_nonce"]').val(payload.nonce);
                    });
                }
            }
        });
    });

    $(".zype-checkout-button").click(function(e) {
        if ($('#payment-form').find('input[name="type"]').val() == 'stripe') {
            var cardDate = $('#zype-card-date').val();
            Stripe.card.createToken({
                number: $('#zype-card-number').val(),
                cvc: $('#zype-card-cvc').val(),
                exp_month: cardDate.split('/')[0],
                exp_year: cardDate.split('/')[1],
            }, stripeTokenHandler);
        }

        return false;
        e.preventDefault();
    });

    function stripeTokenHandler(status, response) {
        if (response.error) {
            $('.checkout_error').text(response.error.message);
        } else {
            sendPaymentRequest();
            $('#payment-form input[name="stripe_card_token"]').val(response.id);
        }
    }
    
    function sendPaymentRequest() {
        $.ajax({
            url: "<?php zype_url('subscribe');?>/submit",
            type: 'post',
            data: $('#payment-form').serialize(),
            dataType: 'json',
            encode: true
        }).done(function(data) {
            if(data.success) {
                $('#payment-wrapper .main-heading .title').text('Thanks for your payment!');
                $('#payment-wrapper .payment-row').html('<p class="to-sign-up">You\'ve successflly unlocked your content. Enjoy!</p><button type="submit" class="zype-button" id="zype_modal_close">Let\'s starting matching</button><input type="hidden" class="close_reload" value="reload">')
            }
        }).fail(function(data) {
            console.log(data.errors);
        });
    }

    $(window).on('popstate', function() {
        handler.close();
    });
  });
</script>
