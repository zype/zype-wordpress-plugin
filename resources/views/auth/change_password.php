<?php get_header(); ?>
<div class="content-wrap user-action-wrap">
  <div class="main-heading inner-heading">
    <strong class="title text-uppercase">My Account | Change Password</strong>
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
            <li class="change-password active">
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
            <li class="link-device">
              <a href="<?php zype_url('device_link'); ?>/">
                <span class="ico"><i class="fa fa-fw fa-link"></i></span>
                <span class="text">Link Device</span>
              </a>
            </li>
          </ul>
        </div>
        <div class="col-sm-6">
          <form action="<?php echo admin_url('admin-ajax.php'); ?>" class="user-form nice-form validate-form" method="post">
            <input type="hidden" name="action" value="zype_update_password">
            <div class="success-section"></div>
            <div class="error-section"></div>
            <div class="zype_flash_messages"></div>
            <fieldset>
              <div class="field-section">
                <div class="form-group">
                  <label class="text-uppercase" for="c-password">current password</label>
                  <input type="password" class="form-control" id="c-password" name="current_password">
                </div>
                <div class="form-group">
                  <label class="text-uppercase" for="n-password">new password</label>
                  <input type="password" class="form-control" id="n-password" name="new_password">
                </div>
                <div class="password-note">Must contain at least 8 characters including a number, an uppercase letter, and a lowercase letter.</div>
                <div class="form-group">
                  <label class="text-uppercase" for="cn-password">confirm new password</label>
                  <input type="password" class="form-control" id="cn-password" name="new_password_confirmation">
                </div>
                <div class="btn-holder">
                  <input type="submit" class="btn btn-primary" value="Submit">
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
