<?php get_header(); ?>
<div class="content-wrap signup-wrap user-action-wrap container user-profile-wrap cancellation">
    <div class="main-heading inner-heading">
        <strong class="title text-uppercase">My Account | Cancel Subscription</strong>
    </div>
    <div id="wrapper">
        <div class="content-main">
            <div class="text-head">
                <h1>Are you sure you want to cancel?</h1>
            </div>
            <div class="item-list">
                <div class="slot">
                    <p>
                        If we've changed your mind, click the button below.
                    </p>
                    <a href="/" class="btn btn-primary user-profile-wrap__button" style="text-decoration: none;">Take Me
                        Back!</a>
                </div>
                <div class="slot">
                    <p>
                        If you're still determined to cancel, click the button below.
                    </p>
                    <form action="<?php zype_url('profile'); ?>/subscription/cancel/" method="post">
                        <input type="hidden" name="subscription_id" value="<?php echo $zd['subscription']->_id; ?>">
                        <input type="submit" class="btn btn-primary user-profile-wrap__button zype-custom-button"
                               value="Cancel Subscription"
                               onclick="return confirm('Are you sure you want to cancel your subscription?');">
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php get_footer(); ?>
