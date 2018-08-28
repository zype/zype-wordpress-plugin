(function ($) {
    $(document).ready(function () {
        var leftUIEl = $('.slider-arrow-left');
        var rightUIEl = $('.slider-arrow-right');
        var elementsList = $('.slider-list');

        var pixelsOffset = 125;
        var currentLeftValue = 0;
        var elementsCount = elementsList.find('li').length;
        var minimumOffset = -((elementsCount - 5) * pixelsOffset);
        var maximumOffset = 0;

        leftUIEl.click(function () {
            if (currentLeftValue != maximumOffset) {
                currentLeftValue += 125;
                elementsList.animate({left: currentLeftValue + "px"}, 500);
            }
        });

        rightUIEl.click(function () {
            if (currentLeftValue != minimumOffset) {
                currentLeftValue -= 125;
                elementsList.animate({left: currentLeftValue + "px"}, 500);
            }
        });
    });
})(jQuery); 