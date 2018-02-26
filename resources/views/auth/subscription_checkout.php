<div class="content-wrap zype-form-center">
  <div class="main-heading inner-heading">
    <h1 class="title text-uppercase zype-title">Enter your billing info</h1>
  </div>
  <div class="user-wrap">
    <div class="holder-main">
        <div class="row">
            <div class="col-sm-6">
                <input type="hidden" name="action" value="zype_plans">

                <div class="holder">
                <input type="hidden" name="action" value="zype_checkout">
                    <form id="payment-form">
                        <input name="plan_id" type="hidden" value="<?php echo $plan->_id; ?>">
                        <input name="email" type="hidden" value="<?php zype_current_consumer(); ?>">
                        <input name="type" type="hidden" value="stripe">
                        <input name="stripe_card_token" type="hidden">
                        <p class="checkout_error" style='color: red'></p>
                        <p class="form-group required-row zype-input-wrap">
                            <input type="text" maxlength="16" oninput="this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');"  placeholder="Card number" class="zype-input-text" id="card-number">
                        </p>
                        <p class="form-group required-row zype-input-wrap">
                            <input maxlength="4" oninput="this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');" type="text" placeholder="CVC" class="zype-input-text" id="card-cvc">
                            <input type="text" placeholder="MM/YY" class="zype-input-text" id="card-date">
                        </p>
                        <button type="submit" class="zype-checkout-button zype-button" data-description="<?php echo $plan->name; ?>" data-interval="<?php echo $plan->interval; ?>" data-amount="<?php echo $plan->amount; ?>">Continue</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
  jQuery(document).ready(function($){
    $("#zype-card-date").mask("99/99");
    $("#zype-card-number").mask("9999 9999 9999 9999");
    
    Stripe.setPublishableKey('<?php echo \Config::get('zype.stripe_pk') ?>');

    $(".zype-checkout-button").click(function(e) {
        var cardDate = $('#zype-card-date').val();
        Stripe.card.createToken({
            number: $('#zype-card-number').val(),
            cvc: $('#zype-card-cvc').val(),
            exp_month: cardDate.split('/')[0],
            exp_year: cardDate.split('/')[1],
        }, stripeTokenHandler);
      
        e.preventDefault();
    });

    function stripeTokenHandler(status, response) {
        if(response.error) {
            $('.checkout_error').text(response.error.message);
        } else {
            $('#payment-form input[name="stripe_card_token"]').val(response.id);
            $.ajax({
                url: "<?php zype_url('subscribe');?>/submit",
                type: 'post',
                data: $('#payment-form').serialize(),
                dataType: 'json',
                encode: true
            }).done(function(data) {
                if(data.success) {
                    $('.main-heading .title').text('Thanks for your payment!');
                    $('.row').html('<p class="to-sign-up">You\'ve successflly unlocked your content. Enjoy!</p><button type="submit" class="zype-button" id="zype_modal_close">Let\'s starting matching</button><input type="hidden" class="close_reload" value="reload">')
                }
            }).fail(function(data) {
                console.log(data.errors);
            });
        }
    }

    $(window).on('popstate', function() {
        handler.close();
    });
  });
</script>
