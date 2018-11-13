(function ($) {
    $(document).on('ready', function () {
        var sliderList = $(".slider-list");

        function slickCalculateResponsive(slider_list) {
            var responsive = [];

            if (slider_list.hasClass('zype-landscape')) {
                var variableWidth = 320;
            } else {
                var variableWidth = 200;
            }

            for (var i = variableWidth; i <= 4096; i += variableWidth) {
                responsive.push({
                    breakpoint: i,
                    settings: {
                        slidesToShow: Math.ceil(slider_list.first().width() / variableWidth)
                    }
                });
            }

            return responsive;
        }

        $.each(sliderList, function () {
            $(this).on('setPosition', function (event, slick) {
                var slick_slides = $(this).find('.slick-slide');
                var variableWidth = slick_slides.first().width();

                if ($(this).hasClass('zype-landscape')) {
                    var heightRatio = 0.5625;
                } else {
                    var heightRatio = 1.5;
                }

                var resizeHeight = variableWidth * heightRatio;

                $.each($(this).find('.slick-slide'), function () {
                    $(this).find('.zype-background-thumbnail').height(resizeHeight);
                });
            });

            $(this).slick({
                dots: false,
                infinite: true,
                arrows: true,
                adaptiveHeight: false,
                responsive: slickCalculateResponsive($(this)),
                prevArrow: '<button type="button" class="slick-prev fa fa-fw fa-arrow-left"></button>',
                nextArrow: '<button type="button" class="slick-next fa fa-fw fa-arrow-right"></button>'
            });
        });

        var boxContentContainer = $('.box-with-content');

        function resizeViewAllGrid() {
            var boxContentContainerWidth = boxContentContainer.width();

            $.each(boxContentContainer.children('.view_all_images'), function () {
                if ($(this).hasClass('zype-landscape')) {
                    var variableWidth = 320;
                    var heightRatio = 0.5625;
                } else {
                    var variableWidth = 200;
                    var heightRatio = 1.5;
                }

                var columnsPerContainer = Math.ceil(boxContentContainerWidth / variableWidth);
                var resizeWidth = Math.floor((boxContentContainerWidth / columnsPerContainer)) - 6;

                var resizeHeight = resizeWidth * heightRatio;
                $(this).css('max-width', resizeWidth);
                $(this).find('.zype-background-thumbnail').width(resizeWidth);
                $(this).find('.zype-background-thumbnail').height(resizeHeight);
            });
        }

        resizeViewAllGrid();

        $(window).on('resize orientationChange', function () {
            resizeViewAllGrid();

            $.each(sliderList, function () {
                $(this).slick('slickSetOption', "responsive", slickCalculateResponsive($(this)), true);
            });
        });
    });
})(jQuery);
