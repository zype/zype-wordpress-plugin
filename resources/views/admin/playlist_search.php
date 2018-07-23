<div class="wrap zype-admin">
    <h2><?php echo get_admin_page_title(); ?></h2>
    <p>Easily add playlists from your <a href="https://admin.zype.com/playlists" target="_blank">Zype account</a>
        onto any page or post on your WordPress website using a playlist shortcode.
        Enter title keywords to search for playlists in your Zype library.
        You can also scroll down the page to find a playlist manually.
    </p>
    <p>Once you’ve found the playlist you would like to embed,
        copy and paste the associated shortcode, including the [ ] brackets,
        onto any page or post on your website.
    </p>
    <p>If a selected playlist contains multiple nested playlists,
        the shortcode will automatically display all nested playlists as
        well as videos contained within the root playlist.
        Visitors can navigate through each playlist to access and play videos.
    </p>

    <form method="post" action="">
        <p><input type="text" name="search" style="width: 50%">
            <input type="submit" name="search_submit" value="Search">
        </p>
    </form>
    <?php if (empty($playlists)) {
        echo "Please enter search parameters";
    } else { ?>
        <form method="post" action="<?php echo admin_url('admin.php'); ?>">
            <table class="wp-list-table widefat fixed striped">
                <thead>
                <tr>
                    <th class="column-posts">Name</th>
                    <th class="column-posts">Shortcode</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($playlists as $item): ?>
                    <tr>
                        <td><?php echo $item->title; ?></td>
                        <td><?php echo "[zype_playlist id='" . $item->_id . "']"; ?></td>
                    </tr>
                <?php endforeach ?>
                </tbody>
            </table>
        </form>
    <?php } ?>
</div>
