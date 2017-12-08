<?php get_header(); ?>
<div class="content-wrap signup-wrap user-action-wrap container">
  <div class="main-heading inner-heading">
    <strong class="title text-uppercase">My Account | Subscription</strong>
  </div>
  <div class="user-wrap">
    <div class="holder-main">
      <div class="row">
        <div class="col-sm-6">
          <ul class="user-action">
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
            <li class="rss-feeds">
              <a href="<?php zype_url('profile'); ?>/rss-feeds/">
                <span class="ico"><i class="fa fa-fw fa-rss"></i></span>
                <span class="text">RSS Feeds</span>
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
          </ul>
        </div>
        <div class="col-sm-6">
          <div class="subscription-content">
            <div class="zype_flash_messages"></div>
            <?php if(!empty($zd['subscription'])){ ?>
              <div class="slot">
                <strong class="title">Subscription Plan: </strong>
                <p><?php echo $zd['current_plan']->name; ?>, $<?php echo $zd['current_plan']->amount; ?> every <?php if($zd['current_plan']->interval_count >1){echo $zd['current_plan']->interval_count.' '; }?><?php echo $zd['current_plan']->interval; ?><?php if($zd['current_plan']->interval_count >1){echo 's'; }?></p>
                <br>
                <?php if(!empty($zd['subscription']->stripe_id)){ ?>
                  <p><strong>Status:</strong> <?php echo $zd['subscription']->status; ?></p>
                  <p><strong>Start Date:</strong> <?php formatted_time(date('c', $zd['plan_start'])); ?></p>
                  <?php if(!$zd['cancel_at_period_end']){ ?>
                    <p><strong>Next Billing Date:</strong> <?php formatted_time(date('c', $zd['plan_end'])); ?></p>
                  <?php } ?>
                  <p><strong>Automatic Renewal:</strong> <?php echo $zd['cancel_at_period_end']? 'Disabled' : 'Enabled';?></p>
                <?php } ?>
              </div>
              <?php if(!empty($zd['subscription']->stripe_id)){ ?>
              <div class="slot">
                <strong class="title">Change Plan: </strong>
                <p>Note: After changing your plan your billing will automatically update to reflect the new cost and billing period using the payment information you currently have on file.</p>
                <br>
                <p>
                  <form action="<?php zype_url('profile'); ?>/subscription/change/" method="post">
                    <fieldset>
                      <div class="field-section">
                        <input type="hidden" name="subscription_id" value="<?php echo $zd['subscription']->_id; ?>">
                        <div class="form-group">
                        <select name="new_plan_id" class="form-control">
                          <?php foreach($zd['plans'] as $a_plan){ ?>
                            <option value="<?php echo $a_plan->_id; ?>" <?php if($a_plan->_id == $zd['current_plan']->_id){?>selected="selected"<?php } ?>>
                            <?php echo $a_plan->name; ?>, $<?php echo $a_plan->amount; ?>/<?php if($a_plan->interval_count >1){echo $a_plan->interval_count.' '; }?><?php echo $a_plan->interval; ?><?php if($a_plan->interval_count >1){echo 's'; }?>
                            </option>
                          <?php } ?>
                        </select>
                        </div>
                        <div class="btn-holder">
                          <input type="submit" class="btn btn-sm btn-success" value="Update Subscription">
                        </div>
                      </div>
                    </fieldset>
                  </form>
                </p>
              </div>
              <?php } ?>
              <?php if(!empty($zd['subscription']->stripe_id)){ ?>
                <div class="slot">
                  <strong class="title">payment method</strong>
                  <p><?php echo $zd['card']->brand; ?> Ending in <?php echo $zd['card']->last4; ?></p>
                  <div class="btn-holder">
                    <form id="update_card_form" action="<?php zype_url('profile'); ?>/subscription/change-card/" method="post" class="button-disableable">
                      <input type="hidden" name="consumer_id" value="<?php echo $zd['consumer_id']; ?>">
                      <input type="hidden" name="email" value="">
                      <input type="hidden" name="stripe_card_token" value="">
                      <button class="customButton btn btn-sm btn-info">Update Credit Card</button>
                      <div class="button-form-disabler"><i class="fa fa-circle-o-notch fa-spin fa-2x"></i></div>
                    </form>
                  </div>
                </div>
              <?php } ?>
              <div class="slot">
                <strong class="title">Cancel Subscription: </strong>
                <?php if(empty($zd['subscription']->stripe_id)){ ?>
                  <p>If you cancel your subscription your subscription will terminate immediately and you will not be refunded a prorated amount.</p>
                  <p>You cannot undo this action.</p>
                <?php } ?>
                <p></p>
                <div class="btn-holder">
                  <a class="btn btn-sm btn-danger" href="<?php zype_url('profile'); ?>/subscription/cancel/">Cancel Subscription</a>
                </div>
              </div>
            <?php } else { ?>
              <p>You do not currently have a subscription.</p>
              <p><a href="<?php zype_url('subscribe'); ?>/">Click here to subscribe.</a></p>
            <?php } ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<script src="https://checkout.stripe.com/checkout.js"></script>
<script type="text/javascript">
(function($){
  //stripe
  var form_id;
  var handler = StripeCheckout.configure({
    key: '<?php echo $zd['stripe_pk']; ?>',
    image: '<?php echo site_url(); ?>/mstile-70x70.png',
    token: function(token) {
      $(form_id+' input[name="email"]').val(token.email);
      $(form_id+' input[name="stripe_card_token"]').val(token.id);
      $(form_id).submit();
    }
  });

  $('.customButton').on('click', function(e) {
    form_id = '#'+$(this).parents('form:first').attr('id');
    
    handler.open({
      name: '<?php bloginfo('name'); ?>',
      description: 'Update Card Details',
      panelLabel: 'Update Card Details',
      email: '<?php echo $zd['email']; ?>',
    });
    e.preventDefault();
  });

  // Close Checkout on page navigation
  $(window).on('popstate', function() {
    handler.close();
  });

  $(document).ready(function(){
    $('.button-disableable').submit(function(){
      $(this).children('.button-form-disabler').show();
    });
  });

})(jQuery); 
</script>
<?php get_footer(); ?>
