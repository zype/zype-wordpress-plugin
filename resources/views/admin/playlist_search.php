<div class="wrap zype-admin">
<h2><?php echo get_admin_page_title(); ?></h2>

<form method="post" action="">
  <p><input type="text" name="search" style="width: 50%">
  <input type="submit" name="search_submit" value="Search playlist"></p>
</form>
<? if (empty($playlists)){
  echo "Please enter search parameters";
}
else{ ?>
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
      <td><?php echo "[zype_playlist id='".$item->_id."']";?></td>
    </tr>
    <?php endforeach ?> 
    </tbody>
  </table>
</form>
<? }?> 
</div>
