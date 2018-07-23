<div class="wrap zype-admin">
    <h2><?php echo get_admin_page_title(); ?></h2>
    <p>You can enable and disable automatic inclusion of categories here and their RSS feeds here.</p>
    <p>To enable an RSS Feed you must configure a corresponding RSS Feed Setting (Category Name and Category Value) in
        the <a href="https://admin.zype.com/" target="_blank">Zype Platform</a>.</p>
    <form method="post" action="<?php echo admin_url('admin.php'); ?>">
        <input type="hidden" name="action" value="zype_categories">
        <?php wp_nonce_field('zype_categories'); ?>
        <table class="wp-list-table widefat fixed striped">
            <thead>
            <tr>
                <th class="column-slug">Category</th>
                <th class="column-posts">Index</th>
                <th class="column-posts">Detail</th>
                <th class="column-posts">RSS</th>
                <th>/URL/</th>
            </tr>
            </thead>
            <tbody>
            <?php if ($categories) { ?>
                <?php foreach ($categories as $i => $category) { ?>
                    <tr>
                        <td colspan="5"><strong><?php echo $category->title; ?></strong></td>
                    </tr>
                    <?php foreach ($category->values as $j => $val) { ?>
                        <tr>
                            <td><?php echo $val; ?></td>
                            <td>
                                <?php
                                $index = false;
                                $detail = false;
                                $rss = false;
                                $url = false;
                                $placeholder = zype_to_permalink($category->title) . '/' . zype_to_permalink($val);

                                if (
                                    isset($options['categories']) && is_array($options['categories']) &&
                                    isset($options['categories'][$category->title]) && is_array($options['categories'][$category->title]) &&
                                    isset($options['categories'][$category->title][$val]) && is_array($options['categories'][$category->title][$val])
                                ) {
                                    if (isset($options['categories'][$category->title][$val]['index'])) {
                                        $index = $options['categories'][$category->title][$val]['index'];
                                    }
                                    if (isset($options['categories'][$category->title][$val]['detail'])) {
                                        $detail = $options['categories'][$category->title][$val]['detail'];
                                    }
                                    if (isset($options['categories'][$category->title][$val]['rss'])) {
                                        $rss = $options['categories'][$category->title][$val]['rss'];
                                    }
                                    if (isset($options['categories'][$category->title][$val]['url'])) {
                                        $url = $options['categories'][$category->title][$val]['url'];
                                    }
                                }
                                ?>
                                <input
                                        type="checkbox"
                                        name="categories[<?php echo $category->title; ?>][<?php echo $val; ?>][index]"
                                        <?php if ($index){ ?>checked="checked"<?php } ?>
                                >
                            </td>
                            <td>
                                <input
                                        type="checkbox"
                                        name="categories[<?php echo $category->title; ?>][<?php echo $val; ?>][detail]"
                                        <?php if ($detail){ ?>checked="checked"<?php } ?>
                                >
                            </td>
                            <td>
                                <?php if (array_key_exists($category->title, $available_feeds) && in_array($val, $available_feeds[$category->title])) { ?>
                                    <input
                                            type="checkbox"
                                            name="categories[<?php echo $category->title; ?>][<?php echo $val; ?>][rss]"
                                            <?php if ($rss){ ?>checked="checked"<?php } ?>
                                    >
                                <?php } else { ?>
                                    <input type="checkbox" disabled="disabled" alt="butt">
                                <?php } ?>
                            </td>
                            <td>
                                <input
                                        type="text"
                                        name="categories[<?php echo $category->title; ?>][<?php echo $val; ?>][url]"
                                        style="width: 100%;"
                                    <?php if ($url) { ?>
                                        value="<?php echo $url; ?>"
                                    <?php } ?>
                                    <?php if ($placeholder) { ?>
                                        placeholder="<?php echo $placeholder; ?>"
                                    <?php } ?>
                                >
                            </td>
                        </tr>
                    <?php } ?>
                <?php } ?>
            <?php } else { ?>
                <tr colspan="4"><p>No categories found. If this is unexpected, check your API keys.</p></tr>
            <?php } ?>
            </tbody>
        </table>
        <p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary"
                                 value="Save Changes"></p>
    </form>
</div>
