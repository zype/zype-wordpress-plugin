<?php if (!defined('ABSPATH')) die(); ?>
<div class="wrap zype-admin">
    <form method="post" action="<?php echo admin_url('admin.php'); ?>">
        <input type="hidden" name="action" value="zype_grid_screen">
        <?php wp_nonce_field('zype_grid_screen'); ?>
        <div class="parent-id-for-grid-screen">
            <b>Parent id: </b> <input type="text" name="grid_screen_parent"
                                      value="<?php echo $options['grid_screen_parent']; ?>">
        </div>
        <p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary"
                                 value="Save Changes"></p>
    </form>
</div>
