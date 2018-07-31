<?php get_header(); ?>
<div class="content-wrap signup-wrap user-action-wrap container user-profile-wrap">
    <div class="main-heading inner-heading">
        <strong class="title text-uppercase">My Account | Profile</strong>
    </div>
    <div class="user-wrap">
        <div class="holder-main">
            <div class="user-profile-wrap__content">
                <div class="user-profile-wrap__block">
                    <ul class="user-action user-profile-wrap__menu">
                        <li class="profile active">
                            <a href="<?php echo home_url(\Config::get('zype.profile_url')) ?>/">
                                <span class="ico"><i class="fa fa-fw fa-user"></i></span>
                                <span class="text">Profile</span>
                            </a>
                        </li>
                        <li class="change-password">
                            <a href="<?php echo home_url(\Config::get('zype.profile_url')) ?>/change-password/">
                                <span class="ico"><i class="fa fa-fw fa-lock"></i></span>
                                <span class="text">Change Password</span>
                            </a>
                        </li>
                        <!-- <li class="rss-feeds">
                          <a href="<!?php echo home_url(\Config::get('zype.profile_url')) ?>/rss-feeds/">
                            <span class="ico"><i class="fa fa-fw fa-rss"></i></span>
                            <span class="text">RSS Feeds</span>
                          </a>
                        </li> -->
                        <li class="subscription">
                            <a href="<?php echo home_url(\Config::get('zype.profile_url')) ?>/subscription/">
                                <span class="ico"><i class="fa fa-fw fa-dollar"></i></span>
                                <span class="text">Subscription</span>
                            </a>
                        </li>
                        <li class="link-device">
                            <a href="<?php echo home_url(\Config::get('zype.device_link_url')) ?>/">
                                <span class="ico"><i class="fa fa-fw fa-link"></i></span>
                                <span class="text">Link Device</span>
                            </a>
                        </li>
                        <li class="link-device">
                            <a href="<?php echo home_url(\Config::get('zype.logout_url')) ?>/">
                                <span class="ico"><i class="fa fa-fw fa-sign-out"></i></span>
                                <span class="text">Logout</span>
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="user-profile-wrap__block">
                    <form action="<?php echo admin_url('admin-ajax.php'); ?>" class="user-form nice-form validate-form"
                          method="post">
                        <input type="hidden" name="action" value="zype_update_profile">
                        <div class="success-section"></div>
                        <div class="error-section"></div>
                        <div class="field-section">
                            <div class="zype_flash_messages"></div>
                            <div class="form-group user-profile-wrap__field">
                                <input type="text" class="form-control user-profile-wrap__inp" id="full-name"
                                       name="name" value="<?php echo $consumer->name; ?>" placeholder="Full name">
                            </div>
                            <div class="form-group user-profile-wrap__field">
                                <input type="email" class="form-control user-profile-wrap__inp" id="email" name="email"
                                       value="<?php echo $consumer->email; ?>" placeholder="Email">
                            </div>
                            <input type="submit" class="btn btn-primary user-profile-wrap__button" value="Update">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php get_footer(); ?>
