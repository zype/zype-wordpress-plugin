<div class='wrap zype-admin' id="customize-ui">
    <h2><?php echo get_admin_page_title(); ?></h2>
    <p>
        Customize the colors for fonts, carousel arrows, buttons, modal
    </p>
    <form method='post' action='<?php echo admin_url('admin.php'); ?>'>
        <input type='hidden' name='action' value='zype_customize_ui'>
        <input type='hidden' name='theme' value='false'>
        <?php wp_nonce_field('zype_customize_ui'); ?>
        <div class="container-fluid">
            <h3>Modal</h3>
            <div class="row middle-xs" id="modal-title">
                <div class="col-xs-6">
                    <div class="row">
                        <div class="col-xs-4">
                            <b>Background</b>
                        </div>
                        <div class="col-xs-1">
                            <input type='color' data-style='background-color' name='modal[background]' id='modal-background-input' value=<?php echo $options['colors']['user']['modal']['background'] ?>>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-4">
                            <b>Title</b>
                        </div>
                        <div class="col-xs-1">
                            <input type='color' data-style='color' name='modal[title]' id='modal-title-input' value=<?php echo $options['colors']['user']['modal']['title'] ?>>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-4">
                            <b>Cross</b>
                        </div>
                        <div class="col-xs-1">
                            <input type='color' data-style='color' name='modal[close-btn]' id='modal-close-button-input' value=<?php echo $options['colors']['user']['modal']['close-btn'] ?>>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-4">
                            <b>Table Item Background</b>
                        </div>
                        <div class="col-xs-1">
                            <input type='color' data-style='background-color' name='modal[price-table][background]' id='modal-table-item-background-input' value=<?php echo $options['colors']['user']['modal']['price-table']['background'] ?>>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-4">
                            <b>Table Item Border</b>
                        </div>
                        <div class="col-xs-1">
                            <input type='color' data-style='border-color' name='modal[price-table][border]' id='modal-table-item-border-input' value=<?php echo $options['colors']['user']['modal']['price-table']['border'] ?>>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-4">
                            <b>Transaction Title</b>
                        </div>
                        <div class="col-xs-1">
                            <input type='color' data-style='color' name='modal[price-table][transaction][title]' id='modal-transaction-title-input' value=<?php echo $options['colors']['user']['modal']['price-table']['transaction']['title'] ?>>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-4">
                            <b>Transaction Description</b>
                        </div>
                        <div class="col-xs-1">
                            <input type='color' data-style='color' name='modal[price-table][transaction][description]' id='modal-transaction-description-input' value=<?php echo $options['colors']['user']['modal']['price-table']['transaction']['description'] ?>>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-4">
                            <b>Transaction Price</b>
                        </div>
                        <div class="col-xs-1">
                            <input type='color' data-style='color' name='modal[price-table][transaction][price]' id='modal-transaction-price-input' value=<?php echo $options['colors']['user']['modal']['price-table']['transaction']['price'] ?>>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-4">
                            <b>Button Border</b>
                        </div>
                        <div class="col-xs-1">
                            <input type='color' data-style='border-color' name='modal[price-table][button][border]' id='modal-button-border-input' value=<?php echo $options['colors']['user']['modal']['price-table']['button']['border'] ?>>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-4">
                            <b>Button Text</b>
                        </div>
                        <div class="col-xs-1">
                            <input type='color' data-style='color' name='modal[price-table][button][text]' id='modal-button-text-input' value=<?php echo $options['colors']['user']['modal']['price-table']['button']['text'] ?>>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-4">
                            <b>Button Background</b>
                        </div>
                        <div class="col-xs-1">
                            <input type='color' data-style='background' name='modal[price-table][button][background]' id='modal-button-background-input' value=<?php echo $options['colors']['user']['modal']['price-table']['button']['background'] ?>>
                        </div>
                    </div>
                </div>
                <div class="col-xs-6" id="modal-sandbox">
                    <div class="row modal-background-sandbox">
                        <i id="zype_video__auth-close" class="fa fa-3x fa-times modal-close-button-sandbox"></i>
                        <div class="player-auth-required-content">
                            <div class="login-sub-section">
                                <div class="content-wrap zype-form-center">
                                    <div class="main-heading inner-heading">
                                        <h1 class="title text-uppercase zype-title modal-title-sandbox">Choose how to unlock your content</h1>
                                    </div>
                                    <div class="user-wrap">
                                        <div class="holder-main">
                                            <div class="row-plan">
                                                <div class="user-form nice-form zype_ajax_form">
                                                    <div class="zype-price-table modal-table-item-background-sandbox">
                                                        <div class="holder">
                                                            <div class="zype-column-plans modal-table-item-border-sandbox">
                                                                <div class="zype-column-plan">
                                                                    <div class="zype-type-plan modal-transaction-title-sandbox">
                                                                        Subscribe
                                                                    </div>
                                                                    <div class="zype-title-plan modal-transaction-description-sandbox">
                                                                        Example Monthly Plan
                                                                    </div>
                                                                </div>
                                                                <div class="zype-column-plan">
                                                                    <div class="zype-price-holder modal-transaction-price-sandbox">
                                                                        $10.00/ mo
                                                                    </div>
                                                                    <a class="zype_auth_markup zype-btn-price-plan">
                                                                        <div
                                                                            class="zype-btn-container-plan
                                                                                    modal-button-background-sandbox
                                                                                    modal-button-text-sandbox
                                                                                    modal-button-border-sandbox"
                                                                        >
                                                                            Continue
                                                                        </div>
                                                                    </a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="container-fluid">
            <h3>Playlist</h3>
            <div class="row middle-xs" id="playlist-sandbox">
                <div class="col-xs-6">
                    <div class="row">
                        <div class="col-xs-4">
                            <b>Arrows</b>
                        </div>
                        <div class="col-xs-1">
                            <input type='color' data-style='color' data-selector='slick-arrow:before' name='playlist[arrow]' id='playlist-arrow-input' value=<?php echo $options['colors']['user']['playlist']['arrow'] ?>>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-4">
                            <b>Playlist Name</b>
                        </div>
                        <div class="col-xs-1">
                            <input type='color' data-style='color' name='playlist[name][normal]' id='playlist-name-input' value=<?php echo $options['colors']['user']['playlist']['name']['normal'] ?>>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-4">
                            <b>Playlist Name on Hover</b>
                        </div>
                        <div class="col-xs-1">
                            <input type='color' data-style='color'
                                data-selector='a.playlist-name-sandbox:focus, a.playlist-name-sandbox:active, a.playlist-name-sandbox:hover' name='playlist[name][hover]' id='playlist-name-input' value=<?php echo $options['colors']['user']['playlist']['name']['hover'] ?>>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-4">
                            <b>Video Name</b>
                        </div>
                        <div class="col-xs-1">
                            <input type='color' data-style='color' name='playlist[video_name]' id='playlist-video-name-normal-input' value=<?php echo $options['colors']['user']['playlist']['video_name'] ?>>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-4">
                            <b>See All</b>
                        </div>
                        <div class="col-xs-1">
                            <input type='color' data-style='color' name='playlist[see_all][normal]' id='playlist-see-all-input' value=<?php echo $options['colors']['user']['playlist']['see_all']['normal'] ?>>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-4">
                            <b>See All on Hover</b>
                        </div>
                        <div class="col-xs-1">
                            <input type='color' data-style='color'
                                data-selector='a.playlist-see-all-sandbox:focus, a.playlist-see-all-sandbox:active, a.playlist-see-all-sandbox:hover' name='playlist[see_all][hover]' id='playlist-see-all-input' value=<?php echo $options['colors']['user']['playlist']['see_all']['hover'] ?>>
                        </div>
                    </div>
                </div>
                <div class="col-xs-6 grid-screen grid_screen-container">
                    <div class="content-box grid_screen-box">
                        <div class="box-with-content">
                            <div class="playlist-with-content">
                                <div class="slider_links">
                                    <div class="slider_links-title">
                                        <a class="playlist-name-sandbox">Playlist Name Example</a>
                                    </div>
                                    <div class="get-all-playlists slider_links-all">
                                        <a class="playlist-see-all-sandbox">See all</a>
                                    </div>
                                </div>
                                <div class="slider-list zype-landscape">
                                    <?php
                                        $background_image = asset_url('images/320x180.png');
                                    ?>
                                    <?php foreach (range(1, 8) as $number): ?>
                                        <div class="slider_slide_second">
                                            <a>
                                                <div class="zype-background-thumbnail"
                                                    style="background-image: url(<?php echo $background_image ?>);">
                                                </div>
                                            </a>
                                            <div title="<?php echo $number ?>" class="playlist-video-name-sandbox item_title_block">
                                                <?php echo "Video {$number}" ?>
                                            </div>
                                        </div>
                                    <?php endforeach ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="container-fluid">
            <div class="row middle-xs" id="buttons">
                <div class="col-xs-2">
                    <input type='submit' name='submit' id='submit' class='button button-primary' value='Save Changes'>
                </div>
                <div class="col-xs-2">
                    <input type='submit' name='light_theme' id='light-theme' class='button button-primary theme-selection' value='Light Theme'>
                </div>
                <div class="col-xs-2">
                    <input type='submit' name='dark_theme' id='dark-theme' class='button button-primary theme-selection' value='Dark Theme'>
                </div>
            </div>
        </div>
    </form>
</div>


<style>
    /* Modal styles */
    .modal-background-sandbox {
        background-color: <?php echo $options['colors']['user']['modal']['background']; ?>;
    }
    #zype_video__auth-close.modal-close-button-sandbox {
        color: <?php echo $options['colors']['user']['modal']['close-btn']; ?>;
    }
    .modal-title-sandbox {
        color: <?php echo $options['colors']['user']['modal']['title']; ?>;
    }
    .modal-table-item-background-sandbox {
        background-color: <?php echo $options['colors']['user']['modal']['price-table']['background']; ?>;
    }
    .modal-table-item-border-sandbox {
        border-color: <?php echo $options['colors']['user']['modal']['price-table']['border']; ?>;
    }
    .modal-transaction-title-sandbox {
        color: <?php echo $options['colors']['user']['modal']['price-table']['transaction']['title']; ?>;
    }
    .modal-transaction-description-sandbox {
        color: <?php echo $options['colors']['user']['modal']['price-table']['transaction']['description']; ?>;
    }
    .modal-transaction-price-sandbox {
        color: <?php echo $options['colors']['user']['modal']['price-table']['transaction']['price']; ?>;
    }
    .modal-button-background-sandbox {
        background-color: <?php echo $options['colors']['user']['modal']['price-table']['button']['background']; ?>;
    }
    .modal-button-text-sandbox {
        color: <?php echo $options['colors']['user']['modal']['price-table']['button']['text']; ?>;
    }
    .modal-button-border-sandbox {
        border-color: <?php echo $options['colors']['user']['modal']['price-table']['button']['border']; ?>;
    }
    /* Playlist styles */
    #playlist-sandbox .slick-arrow:before {
        color: <?php echo $options['colors']['user']['playlist']['arrow']; ?>;
    }
    #playlist-sandbox .slider_links .slider_links-title a {
        color: <?php echo $options['colors']['user']['playlist']['name']['normal']; ?>;
    }
    #playlist-sandbox .slider_links .slider_links-title a:focus,
    #playlist-sandbox .slider_links .slider_links-title a:active,
    #playlist-sandbox .slider_links .slider_links-title a:hover {
        color: <?php echo $options['colors']['user']['playlist']['name']['hover']; ?>;
        cursor: pointer;
    }
    #playlist-sandbox .get-all-playlists.slider_links-all a {
        color: <?php echo $options['colors']['user']['playlist']['see_all']['normal']; ?>;
        cursor: pointer;
    }
    #playlist-sandbox .get-all-playlists.slider_links-all a:focus,
    #playlist-sandbox .get-all-playlists.slider_links-all a:active,
    #playlist-sandbox .get-all-playlists.slider_links-all a:hover {
        color: <?php echo $options['colors']['user']['playlist']['see_all']['hover']; ?>;
    }

    #playlist-sandbox .playlist-video-name-sandbox {
        color: <?php echo $options['colors']['user']['playlist']['video_name']; ?>;
    }
</style>
