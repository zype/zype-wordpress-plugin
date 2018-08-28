<?php get_header(); ?>
<div class="row guest-main">
    <div class="col-md-8">
        <div class="guest-head">
            <strong class="title text-uppercase">Guest List</strong>
            <ul class="filter-option">
                <li class="<?php if (zype_sort() == 'alphabetical') {
                    echo 'active';
                } ?>"><a href="<?php zype_zobject_url('guest'); ?>?sort=alphabetical<?php if (zype_search()) {
                        echo '&search=' . zype_search();
                    } ?>">Alphabetical</a></li>
                <li class="<?php if (zype_sort() == 'recent') {
                    echo 'active';
                } ?>"><a href="<?php zype_zobject_url('guest'); ?>?sort=recent<?php if (zype_search()) {
                        echo '&search=' . zype_search();
                    } ?>">Recent</a></li>
                <li class="header-search"><i class="fa fa-fw fa-search"></i>
                    <form action="<?php zype_zobject_url('guest') . zype_sort_querystring(); ?>" method="get">
                        <?php if (zype_sort()) { ?>
                            <input type="hidden" name="sort" value="<?php echo zype_sort(); ?>">
                        <?php } ?>
                        <input type="text" name="search" class="form-control" placeholder="Search">
                    </form>
                </li>
            </ul>
        </div>
        <div class="ajax-area">
            <?php if ($guests) { ?>
                <div class="guest-list" id="ajax-holder">
                    <?php foreach ($guests as $guest) { ?>
                        <article class="slot">
                            <div class="img-holder">
                <span data-picture data-alt="image description">
                  <span data-src="<?php echo $guest->thumbnail_url; ?>" data-height="80" data-width="80"></span>
                  <span data-src="<?php echo $guest->thumbnail_url; ?>" data-height="80" data-width="80"
                        data-media="(max-width:1023px)"></span> <!-- retina 1x tablet -->
                  <span data-src="<?php echo $guest->thumbnail_url; ?>" data-height="80" data-width="80"
                        data-media="(max-width:1023px) and (-webkit-min-device-pixel-ratio:1.5), (max-width:1023px) and (min-resolution:144dpi)"></span>
                    <!-- retina 2x tablet -->
                  <noscript><img src="<?php echo $guest->thumbnail_url; ?>" height="80" width="80"
                                 alt="image description"></noscript>
                </span>
                            </div>
                            <div class="des">
                                <h3><a href="<?php echo $guest->permalink; ?>"><?php echo $guest->title; ?></a></h3>
                                <p><?php echo $guest->excerpt; ?></p>
                                <aside class="frame">
                                    <ul class="social-networks">
                                        <?php if ($guest->facebook != '') { ?>
                                            <li><a href="<?php echo $guest->facebook; ?>" target="_blank"><i
                                                            class="fa fa-fw fa-facebook-official"></i></a></li>
                                        <?php } ?>
                                        <?php if ($guest->twitter != '') { ?>
                                            <li><a href="<?php echo $guest->twitter; ?>" target="_blank"><i
                                                            class="fa fa-fw fa-twitter"></i></a></li>
                                        <?php } ?>
                                        <?php if ($guest->youtube != '') { ?>
                                            <li><a href="<?php echo $guest->youtube; ?>" target="_blank"><i
                                                            class="fa fa-fw fa-youtube-play"></i></a></li>
                                        <?php } ?>
                                    </ul>
                                    <div class="btn-holder">
                                        <a href="<?php echo $guest->permalink; ?>" class="btn btn-sm btn-primary">Read
                                            More</a>
                                    </div>
                                </aside>
                            </div>
                        </article>
                    <?php } ?>
                </div>
                <nav>
                    <?php if (isset($zype_pagination) && ($zype_pagination->previous || $zype_pagination->next)) { ?>
                        <ul class="pagination">
                            <?php if ($zype_pagination->previous) { ?>
                                <li class="next">
                                    <a href="<?php zype_zobject_url('guest'); ?>/page-<?php echo $zype_pagination->previous . zype_search_and_sort_querystring(); ?>"
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
                                    <a href="<?php zype_zobject_url('guest'); ?>/page-<?php echo $link['url'] . zype_search_and_sort_querystring(); ?>"><?php echo $link['title']; ?></a>
                                </li>
                                <?php if ($zype_pagination->current == $link['url']) {
                                    echo '</strong>';
                                } ?>
                            <?php } ?>
                            <?php if ($zype_pagination->next) { ?>
                                <li class="previous">
                                    <a href="<?php zype_zobject_url('guest'); ?>/page-<?php echo $zype_pagination->next . zype_search_and_sort_querystring(); ?>"
                                       aria-label="Next">
                                        <i class="fa fa-fw fa-angle-right"></i>
                                    </a>
                                </li>
                            <?php } ?>
                        </ul>
                    <?php } ?>
                </nav>
            <?php } else { ?>
                <?php get_template_part('partials/content', 'nothing_found'); ?>
            <?php } ?>
        </div>
    </div>
    <?php get_sidebar(); ?>
</div>
<?php get_footer(); ?>
