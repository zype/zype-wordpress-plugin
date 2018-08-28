<?php if (!defined('ABSPATH')) die(); ?>
<div class="wrap zype-admin">
    <h2><?php echo get_admin_page_title(); ?></h2>
    <form method="post" action="<?php echo admin_url('admin.php'); ?>">
        <input type="hidden" name="action" value="zype_general">
        <?php wp_nonce_field('zype_general'); ?>
        <table class="form-table">
            <tbody>
            <tr>
                <th scope="row">
                    <label for="login-url">Cache time</label>
                </th>
                <td>
                    <input type="text" name="cache_time" id="cache-time" class="regular-text"
                           value="<?php echo $options['cache_time']; ?>"> <span>s</span>
                    <p class="description"></p>
                </td>
            </tr>
            </tbody>
        </table>
        <p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary"
                                 value="Save Changes"></p>
    </form>
</div>
