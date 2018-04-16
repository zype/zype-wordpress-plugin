<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Thank you for subscribing!</title>

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
      <h1>Thank you for Subscribing</h1>
      <p>You now have access to all content available within the subscription plan you purchased.</p>
    </div>
    <div class="item-list">
      <div class="slot">
        <span class="ico"><i class="fa fa-fw fa-desktop"></i></span>
        <strong class="title">Start Watching Now</strong>
<!--         <p><a href="<?php zype_url('video'); ?>/">Browse the archives online</a> We have over 300 hours of content.</p>
 -->      </div>
    </div>
  </div>
</div>
<!-- include jQuery library -->
<script src="js/jquery-1.11.2.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<!-- include custom JavaScript -->
<script src="js/jquery.main.js"></script>
<script src="js/ss-social.js"></script>
</body>
</html>
