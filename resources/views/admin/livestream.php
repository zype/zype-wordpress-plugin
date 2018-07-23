<?php if (!defined('ABSPATH')) die(); ?>
<div class="wrap zype-admin">
    <h2><?php echo get_admin_page_title(); ?></h2>
    <form method="post" action="<?php echo admin_url('admin.php'); ?>">
        <input type="hidden" name="action" value="zype_livestream">
        <?php wp_nonce_field('zype_livestream'); ?>
        <table class="form-table">
            <tbody>
            <tr>
                <th scope="row">
                    <label for="livestream-enabled">Enable Live Video</label>
                </th>
                <td>
                    <input type="checkbox" name="livestream_enabled" id="livestream-enabled"
                           class="regular-checkbox" <?php echo $options['livestream_enabled'] ? 'checked="checked"' : ''; ?>>
                    <p class="description"></p>
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="livestream-authentication-required">Authentication Required</label>
                </th>
                <td>
                    <input type="checkbox" name="livestream_authentication_required"
                           id="livestream-authentication-required"
                           class="regular-checkbox" <?php echo $options['livestream_authentication_required'] ? 'checked="checked"' : ''; ?>>
                    <p class="description"></p>
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="livestream-url">Live Video URL</label>
                </th>
                <td>
                    /<input type="text" name="livestream_url" id="livestream-url" class="regular-text"
                            value="<?php echo $options['livestream_url']; ?>">
                    <p class="description"></p>
                </td>
            </tr>
            </tbody>
        </table>
        <p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary"
                                 value="Save Changes"></p>
    </form>
    <form method="post" action="<?php echo admin_url('admin.php'); ?>">
        <input type="hidden" name="action" value="zype_clear_live_cache">
        <?php wp_nonce_field('zype_clear_live_cache'); ?>
        <p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary"
                                 value="Delete Cache"></p>
    </form>
</div>
