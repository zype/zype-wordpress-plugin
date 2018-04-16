<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Cancel Subscription</title>

  <link href='//fonts.googleapis.com/css?family=Open+Sans:400,600,700,300,800' rel='stylesheet'>
  <link href='//fonts.googleapis.com/css?family=EB+Garamond' rel='stylesheet'>
  <link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/css/all.css">
  <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">

  <script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
  <script src="<?php echo get_template_directory_uri(); ?>/js/jquery.main.js"></script>

  <?php do_action('zype_js_wp_env'); ?>
</head>
<body class="thankyou-page">
<div id="wrapper">
  <div class="content-main">
    <div class="text-head">
      <h1>Are you sure you want to cancel?</h1>
//      <p>You'll be missing out on all of the following.</p>
//    </div>
//    <div class="item-list">
//      <div class="slot">
//        <span class="ico"><i class="fa fa-fw fa-video-camera"></i></span>
//        <strong class="title">Exclusive Live Show</strong>
//        <p><a href="<?php zype_url('live'); ?>/">Watch the live show.</a> Every Monday through Thursday, 4pm eastern.</p>
//      </div>
//      <div class="slot">
//        <span class="ico"><i class="fa fa-fw fa-archive"></i></span>
//        <strong class="title">Complete Show Archive</strong>
//        <p><a href="<?php zype_url('video'); ?>/">Browse the archives online.</a> We have over 300 hours of content.</p>
//      </div>
//      <div class="slot">
//        <span class="ico"><i class="fa fa-fw fa-rss"></i></span>
//        <strong class="title">Podcast RSS Feed</strong>
//        <p><a href="<?php zype_url('profile'); ?>/rss-feeds/">Listen on the go.</a> Every episode, available for easy listening.</p>
//      </div>
      <div class="slot">
        <span class="ico"><i class="fa fa-fw fa-smile-o"></i></span>
        <p>If we've changed your mind, click the button below.</p>
        <a href="/" class="btn btn-sm btn-success" style="text-decoration: none;">Take Me Back!</a>
      </div>
      <div class="slot">
        <span class="ico"><i class="fa fa-fw fa-frown-o"></i></span>
        <p>If you're still determined to cancel, click the button below.</p>
        <form action="<?php zype_url('profile'); ?>/subscription/cancel/" method="post">
          <input type="hidden" name="subscription_id" value="<?php echo $zd['subscription']->_id; ?>">
          <input type="submit" class="btn btn-sm btn-danger" value="Cancel Subscription" onclick="return confirm('Are you sure you want to cancel your subscription?');">
        </form>
      </div>
    </div>
  </div>
</div>
</body>
</html>
