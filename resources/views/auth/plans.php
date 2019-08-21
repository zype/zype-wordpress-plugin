<?php
    $id = 'plans-' . (time() * rand(1, 1000000));
    $continueId = empty($root_parent) ? $id : $root_parent . ' #' . $id;
?>

<div id="<?php echo $id; ?>">
    <div class="content-wrap zype-form-center">
        <div class="main-heading inner-heading">
            <h1 class="title text-uppercase zype-title zype-custom-title">Choose how to unlock your content</h1>
        </div>
        <div class="user-wrap">
            <div class="holder-main">
                <div class="row-plan">
                    <div class="">
                        <form action="<?php echo admin_url('admin-ajax.php') ?>" class="user-form nice-form zype_ajax_form"
                            method="post">
                            <input type="hidden" name="action" value="zype_plans">
                            <div class="zype-price-table">
                                <div class="holder">
                                    <?php foreach ($plans as $plan) { ?>
                                        <div class="zype-column-plans">
                                            <div class="zype-column-plan">
                                                <div class="zype-type-plan">
                                                    Subscribe
                                                </div>
                                                <div class="zype-title-plan"><?php echo $plan->name; ?></div>
                                                <div class="zype-entitlement-type-plan"><?php echo ucfirst($plan->entitlement_type) . ' plan'; ?></div>
                                            </div>
                                            <div class="zype-column-plan">
                                                <div class="zype-price-holder">
                                                    <?php echo \Money::format($plan->amount, $plan->currency); ?>/
                                                    <?php if ($plan->interval_count > 1) {
                                                        echo $plan->interval_count . ' ';
                                                    } ?>
                                                    <?php echo substr($plan->interval, 0, 2); ?>
                                                    <?php if ($plan->interval_count > 1) {
                                                        echo 's';
                                                    } ?></div>
                                                <a href="<?php echo get_permalink() . "?zype_auth_type=checkout&planid=" . esc_attr($plan->_id) . "&redirect_url=" . esc_attr($redirect_url) . "&root_parent=" . esc_attr($root_parent) ?>"
                                                class="zype_auth_markup zype-btn-price-plan"
                                                data-type="checkout"
                                                data-planid="<?php echo esc_attr($plan->_id) ?>"
                                                data-redirect-url="<?php echo esc_attr($redirect_url) ?>"
                                                data-root-parent="<?php echo esc_attr($continueId); ?>">
                                                    <div class="zype-btn-container-plan zype-custom-button">Continue</div>
                                                </a>
                                            </div>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
