<?php if (!defined('ABSPATH')) die(); ?>
<div id="videoEmbed"></div>
<script>
    jQuery(document).ready(function () {
        zype.host = '<?php echo $this->estWidgetHost; ?>';
        zype.siteId = '<?php echo $this->siteId; ?>';
        zype.videoId = '<?php echo $this->video->_id; ?>';
        zype.authData = '<?php echo $this->generateAuthData(); ?>';

        zype.onLogin = function (authData) {
            jQuery.ajax({
                url: zype_js_wp_env.ajax_endpoint,
                type: 'post',
                data: {
                    action: 'zype_authorize_from_widget',
                    authData: authData
                },
                success: function (data) {
                    try {
                        var res = JSON.parse(data);

                        if (res.logged_in === true) {
                            window.location.reload();
                        }

                    } catch (e) {
                    }
                },
                error: function (data) {
                }
            });
        };
        zype.onLogout = function (authData) {
            window.location.href = '/<?php echo $options['logout_url']; ?>';
        };
        zype.videoEmbed('<?php echo $this->tagId; ?>');
    });
</script>

