<?php get_header(); ?>
<div class="content-wrap user-action-wrap">
  <div class="main-heading inner-heading">
    <strong class="title text-uppercase">My Account | Link Device</strong>
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
            <li class="rss-feeds">
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
            <li class="link-device active">
              <a href="<?php zype_url('device_link'); ?>/">
                <span class="ico"><i class="fa fa-fw fa-link"></i></span>
                <span class="text">Link Device</span>
              </a>
            </li>
          </ul>
        </div>
        <div class="col-sm-6">
          <form action="<?php zype_url('device_link') ?>/submit/" class="user-form nice-form" method="post">
            <div class="success-section"></div>
            <div class="error-section"></div>
            <div class="zype_flash_messages"></div>
            <fieldset>
              <div class="field-section">
                <div class="form-group">
                  <label class="text-uppercase" for="pin">device pin</label>
                  <input type="text" class="form-control" id="pin" name="pin">
                </div>
                <div class="btn-holder">
                  <input type="submit" class="btn btn-primary" value="Link">
                </div>
              </div>
            </fieldset>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
<?php get_footer(); ?>
