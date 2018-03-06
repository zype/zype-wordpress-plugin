<div class="content-wrap zype-form-center">
  <div class="main-heading inner-heading">
    <h1 class="title text-uppercase zype-title">Choose how to unlock your content</h1>
  </div>
  <div class="user-wrap">
    <div class="holder-main">
      <div class="row">
        <div class="col-sm-6">
          <form action="<?php echo admin_url('admin-ajax.php') ?>" class="user-form nice-form zype_ajax_form" method="post">
            <input type="hidden" name="action" value="zype_plans">
            <div class="zype-price-table">
              <div class="holder">
              <?php foreach($plans as $plan){ ?>
                <div class="zype-column-plans">
                  <div class="zype-column-plan">
                    <div class="zype-type-plan">
                      Subscribe
                    </div>
                    <div class="zype-title-plan"><?php echo $plan->name; ?></div>
                  </div>
                  
                  <div class="zype-column-plan">
                    <div class="zype-price-holder">$<?php echo $plan->amount; ?>/<?php if($plan->interval_count >1){echo $plan->interval_count.' '; }?><?php echo substr($plan->interval, 0, 2);  ?><?php if($plan->interval_count >1){echo 's'; }?></div>
                    <a href="<?php echo get_permalink() . "?zype_auth_type=checkout&planid=" . esc_attr($plan->_id) ?>" class="zype_auth_markup zype-btn-price-plan" data-type="checkout" data-planid="<?php echo esc_attr($plan->_id) ?>">
                        <div class="zype-btn-container-plan">Continue</div>
                    </a>
                  </div>
                </div>
                <a href="<?php echo get_permalink() . "?zype_auth_type=checkout&planid=" . esc_attr($plan->_id) ?>" class="zype_auth_markup zype-btn-price-plan-mob" data-type="checkout" data-planid="<?php echo esc_attr($plan->_id) ?>">
                  <div class="zype-btn-container-plan-mob">Continue</div>
                </a>
              <?php } ?> 
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>