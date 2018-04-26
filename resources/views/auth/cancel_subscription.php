<?php get_header(); ?>
<div class="content-wrap signup-wrap user-action-wrap container user-profile-wrap cancellation">
  <div class="main-heading inner-heading">
    <strong class="title text-uppercase">My Account | Cancel Subscription</strong>
  </div>
  <div id="wrapper">
  <div class="content-main">
    <div class="text-head">
      <h1>Are you sure you want to cancel?</h1>
<!--       <p>You'll be missing out on all of the following.</p>
 -->    </div>
    <div class="item-list">
<!--       <div class="slot">
        <span class="ico"><i class="fa fa-fw fa-video-camera"></i></span>
        <strong class="title">Exclusive Live Show</strong>
        <p><a href="<?php zype_url('live'); ?>/">Watch the live show.</a> Every Monday through Thursday, 4pm eastern.</p>
      </div>
      <div class="slot">
        <span class="ico"><i class="fa fa-fw fa-archive"></i></span>
        <strong class="title">Complete Show Archive</strong>
        <p><a href="<?php zype_url('video'); ?>/">Browse the archives online.</a> We have over 300 hours of content.</p>
      </div>
 -->      <!-- <div class="slot">
        <span class="ico"><i class="fa fa-fw fa-rss"></i></span>
        <strong class="title">Podcast RSS Feed</strong>
        <p><a href="<!?php zype_url('profile'); ?>/rss-feeds/">Listen on the go.</a> Every episode, available for easy listening.</p>
      </div> -->
      <div class="slot">
        <p><span class="ico"><i class="fa fa-fw fa-smile-o"></i></span>
        If we've changed your mind, click the button below.</p>
        <a href="/" class="btn btn-primary user-profile-wrap__button" style="text-decoration: none;">Take Me Back!</a>
      </div>
      <div class="slot">
        <p><span class="ico"><i class="fa fa-fw fa-frown-o"></i></span>
        If you're still determined to cancel, click the button below.</p>
        <form action="<?php zype_url('profile'); ?>/subscription/cancel/" method="post">
          <input type="hidden" name="subscription_id" value="<?php echo $zd['subscription']->_id; ?>">
          <input type="submit" class="btn btn-primary user-profile-wrap__button" value="Cancel Subscription" onclick="return confirm('Are you sure you want to cancel your subscription?');">
        </form>
      </div>
    </div>
  </div>
</div>
</div>
<?php get_footer(); ?>
