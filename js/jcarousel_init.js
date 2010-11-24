
$(document).ready(function() {
        $("a.lightbox").fancybox({
                'width'				: '945px',
                    'height'				: '730px',
                    'padding'			: 0,
                    'autoScale'			: false,
                    'transitionIn'		: 'none',
                    'transitionOut'	: 'none',
                    'modal' 				: true,
                    'showNavArrows'	: false
                    });
    });

function mycarousel_initCallback(carousel) {
    jQuery(".arrow-right").bind('click', function()
        {
            carousel.next();
            return false;
        });
		
    jQuery(".arrow-left").bind('click', function()
        {
            carousel.prev();
            return false;
        });
};
	
jQuery(document).ready(function() {
        jQuery(".inner-slider .belt").jcarousel({
            scroll: 1,
                    wrap: "both",
                    initCallback: mycarousel_initCallback,
                    buttonNextHTML: null,
                    buttonPrevHTML: null
                    });
		
        jQuery(".slider .belt").jcarousel({
            scroll: 3,
                    wrap: "both",
                    initCallback: mycarousel_initCallback,
                    buttonNextHTML: null,
                    buttonPrevHTML: null
                    });
    });

