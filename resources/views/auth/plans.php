<?php get_header(); ?>
    <div class="content-main price-main">
      <div class="text-head">
        <h1>Choose from our Pricing Plans</h1>
      </div>
      <div class="zype_flash_messages"></div>
      <div class="price-table">
        <?php foreach($plans as $plan){ ?>
          <div class="holder">
            <div class="col">
              <strong class="title"><?php echo $plan->name; ?></strong>
              <div class="price-holder">
                <span class="btn btn-primary btn-sm">$<?php echo $plan->amount; ?>/<?php if($plan->interval_count >1){echo $plan->interval_count.' '; }?><?php echo $plan->interval; ?><?php if($plan->interval_count >1){echo 's'; }?></span>
              </div>
              <ul class="feature-list">
                <li><?php echo $plan->description; ?></li>
              </ul>
              <footer>
                <a class="btn btn-sm btn-primary" href="<?php zype_url('subscribe'); ?>/checkout/?plan_id=<?php echo esc_attr($plan->_id); ?>"><i class="fa fa-fw fa-shopping-cart"></i> Checkout</a>
              </footer>
            </div>
          </div>
        <?php } ?> 
      </div>
    </div>
<?php get_footer() ?>
