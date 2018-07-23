<?php get_header(); ?>
<div class="archived-shows">
    <div class="guest-head">
        <strong class="title text-uppercase">Archived Shows</strong>
        <ul class="filter-option">
            <li class="header-search"><i class="fa fa-fw fa-search"></i>
                <form action="<?php //echo get_zype_url('video').'/?'.zype_sort_querystring(); ?>" method="get">
                    <?php //if(zype_sort()){ ?>
                    <input type="hidden" name="sort" value="<?php //echo zype_sort(); ?>">
                    <?php //} ?>
                    <input type="text" name="search" class="form-control" placeholder="Search">
                </form>
            </li>
        </ul>
    </div>
    <?php //var_dump($videos) ?>
    <?php if ($videos) { ?>
        <div class="listing">
            <?php foreach ($videos as $video) { ?>
                <div class="slot">
                    <article class="row">
                        <div class="video-col col-sm-5">
                            <div class="image-holder">
                <span data-picture data-alt="image description">
                  <span data-src="<?php echo $video->thumbnail_url; ?>" data-height="300" data-width="458"></span>
                  <span data-src="<?php echo $video->thumbnail_url; ?>" data-height="300" data-width="458"
                        data-media="(max-width:1023px)"></span> <!-- retina 1x tablet -->
                  <span data-src="<?php echo $video->thumbnail_url; ?>" data-height="300" data-width="458"
                        data-media="(max-width:1023px) and (-webkit-min-device-pixel-ratio:1.5), (max-width:1023px) and (min-resolution:144dpi)"></span>
                    <!-- retina 2x tablet -->
                  <noscript><img src="<?php echo $video->thumbnail_url; ?>" height="300" width="458"
                                 alt="image description"></noscript>
                </span>
                                <a href="<?php echo $video->permalink; ?>/" class="btn-play">play</a>
                            </div>
                        </div>
                        <div class="col-sm-7">
                            <strong class="title"><?php if ($video->episode != '') { ?>Episode <?php echo $video->episode; ?><?php } ?></strong>
                            <h2><a href="<?php echo $video->permalink; ?>/"><?php echo $video->title; ?></a></h2>
                            <time datetime="<?php echo date(DATE_W3C, strtotime($video->published_at ?: $video->created_at)); ?>"><?php formatted_time($video->published_at ?: $video->created_at); ?></time>
                            <p><?php echo $video->excerpt; ?></p>
                            <ul class="tags">
                                <?php foreach ($video->keywords as $keyword) { ?>
                                    <li><a href="<?php zype_url('video'); ?>/?search=<?php echo $keyword; ?>"
                                           class="btn btn-sm"><?php echo $keyword; ?></a></li>
                                <?php } ?>
                            </ul>
                            <?php the_share_buttons(trailingslashit($video->permalink)); ?>
                        </div>
                    </article>
                </div>
            <?php } ?>
        </div>
        <nav>
            <?php if (isset($zype_pagination) && ($zype_pagination->previous || $zype_pagination->next)) { ?>
                <ul class="pagination">
                    <?php if ($zype_pagination->previous) { ?>
                        <li class="next">
                            <a href="<?php zype_url('video'); ?>/page-<?php echo $zype_pagination->previous . zype_search_and_sort_querystring(); ?>"
                               aria-label="Previous">
                                <i class="fa fa-fw fa-angle-left"></i>
                            </a>
                        </li>
                    <?php } ?>
                    <?php foreach ($zype_pagination->links as $link) { ?>
                        <?php if ($zype_pagination->current == $link['url']) {
                            echo '<strong>';
                        } ?>
                        <li>
                            <a href="<?php zype_url('video'); ?>/page-<?php echo $link['url'] . zype_search_and_sort_querystring(); ?>"><?php echo $link['title']; ?></a>
                        </li>
                        <?php if ($zype_pagination->current == $link['url']) {
                            echo '</strong>';
                        } ?>
                    <?php } ?>
                    <?php if ($zype_pagination->next) { ?>
                        <li class="previous">
                            <a href="<?php zype_url('video'); ?>/page-<?php echo $zype_pagination->next . zype_search_and_sort_querystring(); ?>"
                               aria-label="Next">
                                <i class="fa fa-fw fa-angle-right"></i>
                            </a>
                        </li>
                    <?php } ?>
                </ul>
            <?php } ?>
        </nav>
    <?php } else { ?>
        <div class="col-sm-8">
            <?php get_template_part('partials/content', 'nothing_found'); ?>
        </div>
        <?php get_sidebar('events'); ?>
    <?php } ?>
</div>
<?php get_footer(); ?>
