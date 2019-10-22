<div class="content-wrap zype-form-center">
    <div class="main-heading inner-heading">
        <h1 class="title text-uppercase zype-title zype-custom-title">Choose how to unlock your content</h1>
        <p class="checkout_error" style='color: red'></p>
    </div>
    <div class="user-wrap">
        <div class="holder-main">
            <div class="row">
                <div class="">
                    <form action="<?php echo admin_url('admin-ajax.php') ?>" class="user-form nice-form zype_ajax_form"
                          method="post">
                        <input type="hidden" name="action" value="zype_plans">
                        <div class="zype-price-table">
                            <div class="holder">
                                <?php foreach ($monetizations['purchase']['playlists'] as $playlist_prop) { ?>
                                    <section class='purchase'>
                                        <div class="zype-column-plans">
                                            <div class="zype-column-plan only-title">
                                                <div class="zype-type-plan">Purchase</div>
                                                <div class="zype-title-plan">
                                                    All  <?php echo $playlist_prop['playlist_item_count'] . ' ' .  $playlist_prop['name']; ?> videos
                                                </div>
                                            </div>
                                            <div class="zype-column-plan plan-continue-button">
                                                <div class="zype-price-holder">
                                                    $<?php echo $playlist_prop['price']; ?>
                                                </div>
                                                <a href="<?php echo get_permalink() ?>"
                                                    class="zype_monetization_checkout zype-btn-price-plan"
                                                    data-type="cc_form"
                                                    data-transaction-type="<?php echo ZypeMedia\Controllers\Consumer\Monetization::PURCHASE ?>"
                                                    data-video-id="<?php echo esc_attr($video_id) ?>"
                                                    data-playlist-id="<?php echo esc_attr($playlist_prop['id'])?>"
                                                    data-object-type="playlist"
                                                    data-redirect-url="<?php echo esc_attr($redirect_url) ?>"
                                                    data-root-parent="<?php echo esc_attr($root_parent); ?>">
                                                    <div class="zype-btn-container-plan">Continue</div>
                                                </a>
                                            </div>
                                        </div>
                                    </section>
                                <?php } ?>
                                <?php if($monetizations['purchase']['video']['required']): ?>
                                    <section class='purchase' id="purchase-<?php echo $root_parent ?>">
                                        <div class="zype-column-plans">
                                            <div class="zype-column-plan only-title">
                                                <div class="zype-type-plan">Purchase</div>
                                                <div class="zype-title-plan">Video</div>
                                            </div>
                                            <div class="zype-column-plan plan-continue-button">
                                                <div class="zype-price-holder">
                                                    $<?php echo $monetizations['purchase']['video']['price']; ?>
                                                </div>
                                                <a href="<?php echo get_permalink() ?>"
                                                    class="zype_monetization_checkout zype-btn-price-plan"
                                                    data-type="cc_form"
                                                    data-transaction-type="<?php echo ZypeMedia\Controllers\Consumer\Monetization::PURCHASE ?>"
                                                    data-video-id="<?php echo esc_attr($video_id) ?>"
                                                    data-playlist-id="<?php echo esc_attr($playlist_id)?>"
                                                    data-object-type="video"
                                                    data-redirect-url="<?php echo esc_attr($redirect_url) ?>"
                                                    data-root-parent="<?php echo esc_attr($root_parent); ?>">
                                                    <div class="zype-btn-container-plan">Continue</div>
                                                </a>
                                            </div>
                                        </div>
                                    </section>
                                <?php endif; ?>
                                <?php foreach ($monetizations['rental']['playlists'] as $playlist_prop) { ?>
                                    <section class='rental'>
                                        <div class="zype-column-plans">
                                            <div class="zype-column-plan only-title">
                                                <div class="zype-type-plan">Rent</div>
                                                <div class="zype-title-plan">
                                                    All  <?php echo $playlist_prop['playlist_item_count'] . ' ' .  $playlist_prop['name']; ?> videos
                                                </div>
                                            </div>
                                            <div class="zype-column-plan plan-continue-button">
                                                <div class="zype-price-holder">
                                                    $<?php echo "{$playlist_prop['price']} for {$playlist_prop['days']} "; ?>
                                                    <?php
                                                        echo 'day';
                                                        if ($playlist_prop['days'] > 1) {
                                                            echo 's';
                                                        }
                                                    ?>
                                                </div>
                                                <a href="<?php echo get_permalink() ?>"
                                                    class="zype_monetization_checkout zype-btn-price-plan"
                                                    data-type="cc_form"
                                                    data-transaction-type="<?php echo ZypeMedia\Controllers\Consumer\Monetization::PURCHASE ?>"
                                                    data-video-id="<?php echo esc_attr($video_id) ?>"
                                                    data-playlist-id="<?php echo esc_attr($playlist_prop['id'])?>"
                                                    data-object-type="playlist"
                                                    data-redirect-url="<?php echo esc_attr($redirect_url) ?>"
                                                    data-root-parent="<?php echo esc_attr($root_parent); ?>">
                                                    <div class="zype-btn-container-plan">Continue</div>
                                                </a>
                                            </div>
                                        </div>
                                    </section>
                                <?php } ?>
                                <?php if($monetizations['rental']['video']['required']): ?>
                                    <section class='rental' id="rental-<?php echo $root_parent ?>">
                                        <div class="zype-column-plans">
                                            <div class="zype-column-plan only-title">
                                                <div class="zype-type-plan">Rent</div>
                                                <div class="zype-title-plan">Video</div>
                                            </div>
                                            <div class="zype-column-plan plan-continue-button">
                                                <div class="zype-price-holder">
                                                    $<?php echo "{$monetizations['rental']['video']['price']} for {$monetizations['rental']['video']['days']} "; ?>
                                                    <?php
                                                        echo 'day';
                                                        if ($monetizations['rental']['video']['days'] > 1) {
                                                            echo 's';
                                                        }
                                                    ?>
                                                </div>
                                                <a href="<?php echo get_permalink() ?>"
                                                    class="zype_monetization_checkout zype-btn-price-plan"
                                                    data-type="cc_form"
                                                    data-transaction-type="<?php echo ZypeMedia\Controllers\Consumer\Monetization::RENTAL ?>"
                                                    data-video-id="<?php echo esc_attr($video_id) ?>"
                                                    data-playlist-id="<?php echo esc_attr($playlist_id)?>"
                                                    data-object-type="video"
                                                    data-redirect-url="<?php echo esc_attr($redirect_url) ?>"
                                                    data-root-parent="<?php echo esc_attr($root_parent); ?>">
                                                    <div class="zype-btn-container-plan">Continue</div>
                                                </a>
                                            </div>
                                        </div>
                                    </section>
                                <?php endif; ?>
                                <?php if($monetizations['pass']['required'] && !empty($pass_plans)): ?>
                                    <section id="pass-plans-<?php echo $root_parent ?>">
                                        <?php foreach ($pass_plans as $plan) { ?>
                                            <div class="zype-column-plans">
                                                <div class="zype-column-plan plan-continue-button">
                                                    <div class="zype-type-plan">
                                                        Buy Pass
                                                    </div>
                                                    <div class="zype-title-plan"><?php echo $plan->name; ?></div>
                                                </div>
                                                <div class="zype-column-plan plan-continue-button">
                                                    <div class="zype-price-holder">
                                                        $<?php echo $plan->amount, ' for ', $plan->duration_count; ?>
                                                        <?php
                                                            echo $plan->duration;
                                                            if ($plan->duration_count > 1) {
                                                                echo 's';
                                                            }
                                                        ?>
                                                    </div>
                                                    <a href="<?php echo get_permalink() ?>"
                                                        class="zype_monetization_checkout zype-btn-price-plan"
                                                        data-type="cc_form"
                                                        data-transaction-type="<?php echo ZypeMedia\Controllers\Consumer\Monetization::PASS_PLAN ?>"
                                                        data-video-id="<?php echo esc_attr($video_id) ?>"
                                                        data-playlist-id="<?php echo esc_attr($playlist_id)?>"
                                                        data-object-type="video"
                                                        data-plan-id="<?php echo esc_attr($plan->_id) ?>"
                                                        data-redirect-url="<?php echo esc_attr($redirect_url) ?>"
                                                        data-root-parent="<?php echo esc_attr($root_parent); ?>"
                                                    >
                                                        <div class="zype-btn-container-plan">Continue</div>
                                                    </a>
                                                </div>
                                            </div>
                                        <?php } ?>
                                    </section>
                                <?php endif; ?>
                                <?php if($monetizations['subscription']['required'] && !empty($subscription_plans)): ?>
                                    <section id="subscription-plans-<?php echo $root_parent ?>">
                                        <?php foreach ($subscription_plans as $plan) { ?>
                                            <div class="zype-column-plans">
                                                <div class="zype-column-plan plan-continue-button">
                                                    <div class="zype-type-plan">
                                                        Subscribe
                                                    </div>
                                                    <div class="zype-title-plan"><?php echo $plan->name; ?></div>
                                                    <div class="zype-entitlement-type-plan"><?php echo ucfirst($plan->entitlement_type) . ' plan'; ?></div>
                                                </div>
                                                <div class="zype-column-plan plan-continue-button">
                                                    <div class="zype-price-holder">
                                                        <?php echo \Money::format($plan->amount, $plan->currency); ?>/
                                                        <?php if ($plan->interval_count > 1) {
                                                            echo $plan->interval_count . ' ';
                                                        } ?>
                                                        <?php echo substr($plan->interval, 0, 2); ?>
                                                        <?php if ($plan->interval_count > 1) {
                                                            echo 's';
                                                        } ?></div>
                                                    <a href="<?php echo get_permalink() ?>"
                                                    class="zype_monetization_checkout zype-btn-price-plan"
                                                    data-type="cc_form"
                                                    data-transaction-type="<?php echo ZypeMedia\Controllers\Consumer\Monetization::SUBSCRIPTION ?>"
                                                    data-video-id="<?php echo esc_attr($video_id) ?>"
                                                    data-playlist-id="<?php echo esc_attr($playlist_id)?>"
                                                    data-object-type="video"
                                                    data-plan-id="<?php echo esc_attr($plan->_id) ?>"
                                                    data-redirect-url="<?php echo esc_attr($redirect_url) ?>"
                                                    data-root-parent="<?php echo esc_attr($root_parent); ?>">
                                                        <div class="zype-btn-container-plan">Continue</div>
                                                    </a>
                                                </div>
                                            </div>
                                        <?php } ?>
                                    </section>
                                <?php endif; ?>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
