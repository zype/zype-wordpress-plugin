<?php get_header(); ?>
<div class="content-wrap user-action-wrap user-profile-wrap">
    <div class="main-heading inner-heading">
        <strong class="title text-uppercase">My Account | Link Device</strong>
    </div>
    <div class="user-wrap">
        <div class="holder-main">
            <div class="user-profile-wrap__content">
                <div class="user-profile-wrap__block">
                    <ul class="user-action user-profile-wrap__menu">
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
                        <!-- <li class="rss-feeds">
                          <a href="<!?php zype_url('profile'); ?>/rss-feeds/">
                            <span class="ico"><i class="fa fa-fw fa-rss"></i></span>
                            <span class="text">RSS Feeds</span>
                          </a>
                        </li> -->
                        <li class="subscription">
                            <a href="<?php zype_url('profile'); ?>/subscription/">
                                <span class="ico"><i class="fa fa-fw fa-dollar"></i></span>
                                <span class="text">Subscription</span>
                            </a>
                        </li>
                        <li class="link-device active">
                            <a href="<?php zype_url('device_link'); ?>/">
                                <span class="ico"><i class="fa fa-fw fa-link"></i></span>
                                <span class="text">Link Device</span>
                            </a>
                        </li>
                        <li class="log-out">
                            <a href="<?php echo home_url($options['logout_url']) ?>/">
                                <span class="ico"><i class="fa fa-fw fa-sign-out"></i></span>
                                <span class="text">Logout</span>
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="user-profile-wrap__block">
                    <form action="<?php zype_url('device_link') ?>/submit/" class="user-form nice-form" method="post">
                        <div class="success-section"></div>
                        <div class="error-section"></div>
                        <div class="field-section">
                            <div class="zype_flash_messages"></div>
                            <div class="form-group user-profile-wrap__field">
                                <input type="text" class="form-control user-profile-wrap__inp" id="pin" name="pin"
                                       placeholder="Device pin">
                            </div>
                            <input type="submit" class="btn btn-primary user-profile-wrap__button" value="Link">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php get_footer(); ?>
