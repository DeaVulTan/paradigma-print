(function($) {
    $(function() {
        var jcarousel = $('.jcarousel');
        jcarousel
            .on('jcarousel:reload jcarousel:create', function () {
                
                console.log(jcarousel.innerWidth());
                var width = jcarousel.innerWidth();

                if (width >= 600) {
                    width = width / 6;
                } else if (width >= 350) {
                    width = width / 4;
                }

                jcarousel.jcarousel('items').css('width', width + 'px');
            })
            .jcarousel({
                wrap: 'circular'
            })
	.jcarouselAutoscroll({
            interval: 1000,
            target: '+=1',
            autostart: true
			});

        $('.jcarousel-control-prev')
            .jcarouselControl({
                target: '-=1'
            });

        $('.jcarousel-control-next')
            .jcarouselControl({
                target: '+=1'
            });
    });
})(jQuery);
