<?php get_header(); ?>
<div class="archived-shows">
    <div class="guest-head">
        <strong class="title text-uppercase">Highlights</strong>
        <ul class="filter-option">
            <li class="header-search"><i class="fa fa-fw fa-search"></i>
                <form action="<?php zype_category_url('Highlight', 'true'); ?>" method="get">
                    <input type="text" name="search" class="form-control" placeholder="Search">
                </form>
            </li>
        </ul>
    </div>
    <?php if ($videos): ?>
        <div class="listing">
            <?php foreach ($videos as $video): ?>
                <div class="slot">
                    <div class="row">
                        <div class="video-col col-sm-5">
                            <?php zype_player_free_embed($video); ?>
                        </div>
                    </div>
                </div>
            <?php endforeach ?>
        </div>
        <nav>
            <?php if (isset($zype_pagination) && ($zype_pagination->previous || $zype_pagination->next)): ?>
                <ul class="pagination">
                    <?php if ($zype_pagination->previous): ?>
                        <li class="next">
                            <a href="<?php zype_category_url('Highlight', 'true'); ?>/page-<?php echo $zype_pagination->previous . zype_search_and_sort_querystring(); ?>"
                               aria-label="Previous">
                                <i class="fa fa-fw fa-angle-left"></i>
                            </a>
                        </li>
                    <?php endif ?>

                    <?php foreach ($zype_pagination->links as $link): ?>
                        <li>
                            <?php if ($zype_pagination->current == $link['url']) {
                                echo '<strong>';
                            } ?>
                            <a href="<?php zype_category_url('Highlight', 'true'); ?>/page-<?php echo $link['url'] . zype_search_and_sort_querystring(); ?>"><?php echo $link['title']; ?></a>
                            <?php if ($zype_pagination->current == $link['url']) {
                                echo '</strong>';
                            } ?>
                        </li>
                    <?php endforeach ?>

                    <?php if ($zype_pagination->next): ?>
                        <li class="previous">
                            <a href="<?php zype_category_url('Highlight', 'true'); ?>/page-<?php echo $zype_pagination->next . zype_search_and_sort_querystring(); ?>"
                               aria-label="Next">
                                <i class="fa fa-fw fa-angle-right"></i>
                            </a>
                        </li>
                    <?php endif ?>
                </ul>
            <?php endif ?>
        </nav>
    <?php else: ?>
        <div class="col-sm-8">
            <h3>Nothing found</h3>
        </div>
        <?php get_sidebar('events'); ?>
    <?php endif ?>
</div>
<?php get_footer(); ?>
