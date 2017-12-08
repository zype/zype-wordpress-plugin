<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <?php wp_head() ?>

  <link href='//fonts.googleapis.com/css?family=Open+Sans:400,600,700,300,800' rel='stylesheet'>
  <link href='//fonts.googleapis.com/css?family=EB+Garamond' rel='stylesheet'>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
  <script src="<?php echo get_template_directory_uri(); ?>/js/jquery.main.js"></script>

  <?php do_action('zype_js_wp_env'); ?>

  <script src="https://js.braintreegateway.com/v2/braintree.js"></script>
  <script src="https://checkout.stripe.com/checkout.js"></script>
  <script type="text/javascript">
    var braintree_token = '<?php echo $braintree_token; ?>';
  </script>

	<style>
		body {
			opacity: 1 !important;
		}
	</style>
</head>
<body class="thankyou-page pricing-page">
  <div id="wrapper">
    <div class="content-main price-main">
      <div class="text-head">
        <h1>Select a Payment Method</h1>
        <p>Checkout with your Credit Card or PayPal.</p>
      </div>
      <div class="zype_flash_messages"></div>
      <div class="price-table row">

      <div class="col-md-2"></div>

      <div class="col-md-4">
        <strong class="title"><?php echo $plan->name; ?></strong>
        <div class="price-holder">
          <span class="btn btn-primary btn-sm">$<?php echo $plan->amount; ?>/<?php if($plan->interval_count >1){echo $plan->interval_count.' '; }?><?php echo $plan->interval; ?><?php if($plan->interval_count >1){echo 's'; }?></span>
        </div>
        <ul class="feature-list">
          <li><?php echo $plan->description; ?></li>
        </ul>
        <footer>

        </footer>
      </div>




        <div class="col-md-4">
          <strong class="title">Pay With</strong>
          <ul class="feature-list">
            <li>
              <form action="<?php zype_url('subscribe');?>/submit/" method="post" id="stripe-<?php echo $plan->_id; ?>" class="button-disableable">
                <input name="plan_id" type="hidden" value="<?php echo $plan->_id; ?>">
                <input name="email" type="hidden">
                <input name="type" type="hidden" value="stripe">
                <input name="stripe_card_token" type="hidden">
                <button class="customButton btn btn-sm btn-primary button-stripe" data-description="<?php echo $plan->name; ?>" data-interval="<?php echo $plan->interval; ?>" data-amount="<?php echo $plan->amount; ?>">
                  <i class="fa fa-fw fa-credit-card"></i> Credit Card
                </button>
                <div class="button-form-disabler"><i class="fa fa-circle-o-notch fa-spin fa-2x"></i></div>
              </form>
            </li>
            <li>
              <form action="<?php zype_url('subscribe');?>/submit/" method="post" id="braintree-<?php echo $plan->_id; ?>"  class="button-disableable">
                <input name="plan_id" type="hidden" value="<?php echo $plan->_id; ?>">
                <input name="email" type="hidden" value="<?php zype_current_consumer(); ?>">
                <input name="type" type="hidden" value="braintree">
                <input id="braintree_payment_nonce" name="braintree_payment_nonce" type="hidden">
                <div id="braintree-container" class="button-paypal"></div>
                <div class="button-form-disabler"><i class="fa fa-circle-o-notch fa-spin fa-2x"></i></div>
              </form>
            </li>
          </ul>
        </div>

      </div>
    </div>
  </div>
<script>

  jQuery(document).ready(function($){
    $('.button-disableable').submit(function(){
      $(this).children('.button-form-disabler').show();
    });

    //braintree
    braintree.setup(braintree_token, 'paypal',{
      container: 'braintree-container',
      paymentMethodNonceInputField: 'braintree_payment_nonce',
      onSuccess: function(){
        $('#braintree-<?php echo $plan->_id; ?>').submit();
      }
    });

    //stripe
    var amount;
    var description;
    var form_id;
    var interval;
    var handler = StripeCheckout.configure({
      key: '<?php echo \Config::get('zype.stripe_pk') ?>',
      image: '<?php echo site_url(); ?>/mstile-70x70.png',
      token: function(token) {
        $(form_id+' input[name="email"]').val(token.email);
        $(form_id+' input[name="stripe_card_token"]').val(token.id);
        $(form_id).submit();
      }
    });

    $('.customButton').on('click', function(e) {
      form_id = '#'+$(this).parents('form:first').attr('id');

      amount = $(this).data('amount') * 100;
      interval = $(this).data('interval');

      description = $(this).data('description');

      if ( description === '6 Month Subscription' ) {
        var panel_label = 'Total: {{amount}} per 6 '+interval+'s';
      } else {
        var panel_label = 'Total: {{amount}} per '+interval;
      }

      handler.open({
        name: '<?php bloginfo('name'); ?>',
        description: description,
        allowRememberMe: false,
        panelLabel: panel_label,
        email: '<?php zype_current_consumer(); ?>',
        amount: amount
      });
      e.preventDefault();
    });

    // Close Checkout on page navigation
    $(window).on('popstate', function() {
      handler.close();
    });
  });
</script>
</body>
</html>
