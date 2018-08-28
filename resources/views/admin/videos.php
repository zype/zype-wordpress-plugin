<?php if (!defined('ABSPATH')) die(); ?>
<div class="wrap zype-admin">
    <h2><?php echo get_admin_page_title(); ?></h2>
    <p>For more settings visit your Zype Platform Dashboard at <a href="https://admin.zype.com" target="_blank">https://admin.zype.com</a>.
    <p>
        <form method="post" action="<?php echo admin_url('admin.php'); ?>">
            <input type="hidden" name="action" value="zype_videos">
            <?php wp_nonce_field('zype_videos'); ?>
            <table class="form-table">
                <tbody>
                <tr>
                    <th scope="row">
                        <label for="audio-only-enabled">Enable Audio Only</label>
                    </th>
                    <td>
                        <input type="checkbox" name="audio_only_enabled" id="audio-only-enabled"
                               class="regular-checkbox" <?php echo $options['audio_only_enabled'] ? 'checked="checked"' : ''; ?>>
    <p class="description"></p>
    </td>
    </tr>
    <tr>
        <th scope="row">
            <label for="rss_enabled">Enable RSS Feed</label>
        </th>
        <td>
            <?php if ($allow_rss_feed) { ?>
                <input type="checkbox" name="rss_enabled" id="rss_enabled"
                       class="regular-checkbox" <?php echo $options['rss_enabled'] ? 'checked="checked"' : ''; ?>>
                <p class="description"></p>
            <?php } else { ?>
                <p>To enable the RSS feed you must configure an RSS Feed Setting with the title <strong>default</strong>
                    in the <a href="https://admin.zype.com/" target="_blank">Zype Platform</a>.</p>
            <?php } ?>
        </td>
    </tr>
    <tr>
        <th scope="row">
            <label for="rss_url">RSS Fed URL</label>
        </th>
        <td>
            <input type="text" name="rss_url" id="rss_url" class="regular-text"
                   value="<?php echo stripslashes($options['rss_url']); ?>">
            <p class="description"></p>
        </td>
    </tr>
    <tr>
        <th scope="row">
            <label for="">Excluded Categories</label>
        </th>
        <td>
            <?php if ($categories) { ?>
                <?php foreach ($categories as $i => $category) { ?>
                    <label for="excluded_categories-<?php echo $i; ?>">
                        <input
                                type="checkbox"
                                name="excluded_categories[]"
                                id="excluded_categories-<?php echo $i; ?>"
                                class="regular-checkbox"
                                value="<?php echo $category->title; ?>"
                            <?php echo in_array($category->title, $options['excluded_categories']) ? 'checked="checked"' : ''; ?>
                        >
                        <?php echo $category->title; ?>
                    </label>
                    <br>
                <?php } ?>
                <p class="description">Check all categories that you would like to exclude from the main videos
                    query.</p>
            <?php } else { ?>
                <p>No categories found. If this is unexpected, check your API keys.</p>
            <?php } ?>
        </td>
    </tr>
    </tbody>
    </table>
    <p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="Save Changes">
    </p>
    </form>
</div>
