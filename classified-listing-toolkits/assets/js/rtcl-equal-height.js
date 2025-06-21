(function ($) {
    // equalHeight();
    $(".rtcl-equal-height").each(function () {
        var $equalItemWrap = $(this),
            equalItems = $equalItemWrap.find('.equal-item');
        equalItems.height('auto');
        if ($(window).width() > 767) {
            var maxH = 0;
            equalItems.each(function () {
                var itemH = $(this).outerHeight();
                if (itemH > maxH) {
                    maxH = itemH;
                }
            });
            equalItems.height(maxH + 'px');
        } else {
            equalItems.height('auto');
        }

    });

})(jQuery);