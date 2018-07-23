<div class="wrap zype-admin">
    <h2><?php echo get_admin_page_title(); ?></h2>
    <form method="post" action="<?php echo admin_url('admin.php'); ?>">
        <input type="hidden" name="action" value="zype_zobjects">
        <?php wp_nonce_field('zype_zobjects'); ?>
        <table class="form-table">
            <tbody>
            <tr>
                <th scope="row">
                    <label for="">zObjects</label>
                </th>
                <td>
                    <?php if ($zobjects) { ?>
                        <?php foreach ($zobjects as $i => $zobject) { ?>
                            <label for="zobjects-<?php echo $i; ?>">
                                <input
                                        type="checkbox"
                                        name="zobjects[]"
                                        id="zobjects-<?php echo $i; ?>"
                                        class="regular-checkbox"
                                        value="<?php echo $zobject->title; ?>"
                                    <?php echo in_array($zobject->title, $options['zobjects']) ? 'checked="checked"' : ''; ?>
                                >
                                <?php echo $zobject->title; ?>
                            </label>
                            <br>
                        <?php } ?>
                    <?php } else { ?>
                        <p>No zObjects found. If this is unexpected, check your API keys.</p>
                    <?php } ?>
                    <p class="description">Check all zObjects you would like to display on your site.</p>
                </td>
            </tr>
            </tbody>
        </table>
        <p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary"
                                 value="Save Changes"></p>
    </form>
</div>
