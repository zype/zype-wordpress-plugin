<!-- start zype_wp_js_env -->
<script type="text/javascript">
    var zype_js_wp_env = {
        plugin_url: '<?php print plugins_url('', __FILE__) ?>',
        home_url: '<?php print home_url('/') ?>',
        logout_url: '<?php print home_url($options['logout_url']) ?>',
        profile_url: '<?php print home_url($options['profile_url']) ?>',
        auth_url: '<?php print home_url($options['auth_url']) ?>',
        ajax_endpoint: '<?php print admin_url('admin-ajax.php') ?>',
        logged_in: <?php print (new \ZypeMedia\Services\Auth)->logged_in() ? 'true' : 'false' ?>,
        estWidgetEnabled: true,
    };
</script>
<!-- end zype_wp_js_env -->
