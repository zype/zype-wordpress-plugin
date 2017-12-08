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
            dots: true,
            infinite: true,
            arrows: true,
            adaptiveHeight: true,
            responsive: slickCalculateResponsive()
        });

        $(window).on('resize orientationChange', function() {
            sliderList.slick('slickSetOption', "responsive", slickCalculateResponsive(), true);
        });
    });
})(jQuery);