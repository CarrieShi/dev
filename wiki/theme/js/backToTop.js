;(function ($,window,document,undefined) {
    $.fn.backToTop = function (options) {
        $element = this;
        if ($element.length) {
            var scrollTrigger = 100, // px
                backToTop = function () {
                    var scrollTop = $(window).scrollTop();
                    if (scrollTop > scrollTrigger) {
                        $element.addClass('show');
                    } else {
                        $element.removeClass('show');
                    }
                };
            backToTop();
            $(window).on('scroll', function () {
                backToTop();
            });
            $element.on('click', function (e) {
                e.preventDefault();
                $('html,body').animate({
                    scrollTop: 0
                }, 700);
            });
        }
    }
})(jQuery,window,document);