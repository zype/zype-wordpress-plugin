<? xml version = "1.0" encoding = "UTF-8"?>
<rss xmlns:itunes="http://www.itunes.com/dtds/podcast-1.0.dtd"
     version="2.0">
    <channel>
        <title><?php echo htmlspecialchars($feed_settings->feed_title); ?></title>
        <description><?php echo htmlspecialchars($feed_settings->feed_description); ?></description>
        <itunes:author
                xmlns:itunes="http://www.itunes.com/dtds/podcast-1.0.dtd"><?php echo htmlspecialchars($feed_settings->author); ?></itunes:author>
        <webMaster><?php echo htmlspecialchars($feed_settings->webmaster); ?></webMaster>

        <?php foreach ($feed_settings->itunes_category as $category) { ?>
            <category><?php echo htmlspecialchars($category); ?></category>
            <itunes:category text="<?php echo htmlspecialchars($category); ?>"/>
        <?php } ?>

        <link><?php echo htmlspecialchars($feed_settings->link); ?></link>
        <itunes:image xmlns:itunes="http://www.itunes.com/dtds/podcast-1.0.dtd"
                      href="<?php echo esc_attr($feed_settings->thumbnail_url); ?>"/>
        <pubDate><?php echo htmlspecialchars($feed_settings->published_date); ?></pubDate>
        <language><?php echo htmlspecialchars($feed_settings->language); ?></language>

        <?php if ($feed_settings->explicit) { ?>
            <itunes:explicit xmlns:itunes="http://www.itunes.com/dtds/podcast-1.0.dtd">yes</itunes:explicit>
        <?php } ?>

        <copyright><?php echo htmlspecialchars($feed_settings->copyright_text); ?></copyright>

        <?php foreach ($items as $item) { ?>
            <?php if (isset($item->outputs) && $this->has_m4a($item)) { ?>
                <item>
                    <title><?php echo htmlspecialchars($item->title); ?><?php if (isset($item->episode)) { ?> - Episode <?php echo htmlspecialchars($item->episode);
                        } ?></title>
                    <description><![CDATA[<?php echo htmlspecialchars($item->description); ?>]]></description>
                    <itunes:author
                            xmlns:itunes="http://www.itunes.com/dtds/podcast-1.0.dtd"><?php echo htmlspecialchars($feed_settings->author); ?></itunes:author>

                    <pubDate><?php echo htmlspecialchars(date('r', strtotime($item->published_at))); ?></pubDate>

                    <?php if (isset($item->mature_content) && $item->mature_content == true) { ?>
                        <itunes:explicit xmlns:itunes="http://www.itunes.com/dtds/podcast-1.0.dtd">yes</itunes:explicit>
                    <?php } ?>

                    <itunes:image xmlns:itunes="http://www.itunes.com/dtds/podcast-1.0.dtd"
                                  href="<?php echo esc_attr($item->thumbnail_url); ?>"/>

                    <?php foreach ($feed_settings->itunes_category as $category) { ?>
                        <category><?php echo htmlspecialchars($category); ?></category>
                    <?php } ?>

                    <guid><?php echo htmlspecialchars($item->permalink); ?></guid>
                    <enclosure url="<?php echo esc_attr($this->the_m4a_url($item)); ?>"
                               length="<?php echo esc_attr($this->the_file_size($item)); ?>" type="audio/m4a"/>
                </item>
            <?php } ?>
        <?php } ?>

    </channel>
</rss>
