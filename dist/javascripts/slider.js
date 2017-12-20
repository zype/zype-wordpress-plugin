(function($) {
    $(document).on('ready', function() {
        var sliderList = $(".slider-list");

        function slickCalculateResponsive() {
            var responsive = [];

            for (var i = 300; i <= 4096; i += 300) {
                responsive.push({
                    breakpoint: i,
                    settings: {
                        slidesToShow: Math.ceil(sliderList.first().width() / 300)
                    }
                });
            }

            return responsive;
        }

        sliderList.slick({
            dots: false,
            infinite: true,
            arrows: true,
            adaptiveHeight: false,
            responsive: slickCalculateResponsive()
        });

        $(window).on('resize orientationChange', function() {
            sliderList.slick('slickSetOption', "responsive", slickCalculateResponsive(), true);
        });
    });
})(jQuery);