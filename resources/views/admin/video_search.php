<div class="wrap zype-admin">
    <h2><?php echo get_admin_page_title(); ?></h2>
    <p>Easily embed a video from your <a href="https://admin.zype.com/videos" target="_blank">
            Zype library</a> onto any page
        or post on your WordPress website using a video shortcode.
        Enter title keywords to search for videos in your Zype library.
        You can also scroll down the page to find a video manually.
    </p>
    <p>Once you’ve found the video you would like to embed, copy and paste
        the associated shortcode, including the [ ] brackets,
        onto any page or post on your website. The shortcode will automatically
        render a video player, along with the video title, description, and duration.</p>

    <form method="post" action="">
        <p><input type="text" name="search" style="width: 50%">
            <input type="submit" name="search_submit" value="Search"></p>
    </form>
    <?php if (empty($videos)): ?>
        Please enter search parameters
    <?php else: ?>
        <form method="post" action="<?php echo admin_url('admin.php'); ?>">
            <table class="wp-list-table widefat fixed striped">
                <thead>
                <tr>
                    <th class="column-posts">Name</th>
                    <th class="column-posts">Shortcode</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($videos as $item): ?>
                    <?php if (!preg_match("/Playlist/i", $item->title)): ?>
                        <tr>
                            <td><?php echo $item->title; ?></td>
                            <td><?php echo "[zype_video id='" . $item->_id . "']"; ?></td>
                        </tr>
                    <?php endif ?>
                <?php endforeach ?>
                </tbody>
            </table>
        </form>
    <?php endif ?>
</div>
