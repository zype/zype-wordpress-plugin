<?php get_header(); ?>
<div class="content-wrap signup-wrap user-action-wrap container">
    <div class="main-heading inner-heading">
        <strong class="title text-uppercase">My Account | Profile</strong>
    </div>
    <div class="user-wrap">
        <div class="holder-main">
            <div class="row">
                <div class="">
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
                        <li class="rss-feeds active">
                            <a href="<?php zype_url('profile'); ?>/rss-feeds/">
                                <span class="ico"><i class="fa fa-fw fa-rss"></i></span>
                                <span class="text">RSS Feeds</span>
                            </a>
                        </li>
                        <li class="subscription">
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
                <div class="">
                    <div class="row">
                        <p>Please take a moment to read the RSS section of our FAQ if you are unfamiliar with RSS Feeds
                            &amp; Podcasts.</p>
                    </div>
                    <div class="row">
                        <div class="col-sm-3">
                            <strong>iTunes</strong>
                        </div>
                        <div class="col-sm-8">
                            <p>Click the link below to open the RSS the feed in iTunes.</p>
                            <?php foreach ($zype_rss_links as $zype_rss_name => $zype_rss_link) { ?>
                                <?php if ($zype_rss_name == 'default') { ?>
                                    <p><a href="<?php echo $zype_rss_link['itunes']; ?>">Everything</a></p>
                                <?php } else { ?>
                                    <p>
                                        <a href="<?php echo $zype_rss_link['itunes']; ?>"><?php echo explode('%%', $zype_rss_name)[1]; ?></a>
                                    </p>
                                <?php } ?>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-3">
                            <strong>Default</strong>
                        </div>
                        <div class="col-sm-8">
                            <p>Click the link below if you are unsure... This feed will attempt to launch in your
                                default podcast app if you have one.</p>
                            <?php foreach ($zype_rss_links as $zype_rss_name => $zype_rss_link) { ?>
                                <?php if ($zype_rss_name == 'default') { ?>
                                    <p><a href="<?php echo $zype_rss_link['feed']; ?>">Everything</a></p>
                                <?php } else { ?>
                                    <p>
                                        <a href="<?php echo $zype_rss_link['feed']; ?>"><?php echo explode('%%', $zype_rss_name)[1]; ?></a>
                                    </p>
                                <?php } ?>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-3">
                            <strong>Copy/Paste</strong>
                        </div>
                        <div class="col-sm-8">
                            <p>Use this feed if you know what you are doing or nothing else is working. This feed is for
                                copying and pasting. Right click if you are on a desktop or click and hold on the link
                                from a mobile device to bring up the auxiliary menu.</p>
                            <?php foreach ($zype_rss_links as $zype_rss_name => $zype_rss_link) { ?>
                                <?php if ($zype_rss_name == 'default') { ?>
                                    <p><a href="<?php echo $zype_rss_link['http']; ?>">Everything</a></p>
                                <?php } else { ?>
                                    <p>
                                        <a href="<?php echo $zype_rss_link['http']; ?>"><?php echo explode('%%', $zype_rss_name)[1]; ?></a>
                                    </p>
                                <?php } ?>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php get_footer(); ?>
