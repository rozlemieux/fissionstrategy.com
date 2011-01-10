// JavaScript Document

var rotation_interval = 4000;

jQuery(document).ready(function()
{
	jQuery(".team").each(function()
	{
		var member = jQuery(this);
		jQuery(this).find(".hideshow").click(function()
		{
			if (jQuery(this).html() == "hide bio")
			{
				member.find(".team-content").slideUp(800);
				member.find(".hideshow").html("show bio");
			}
			else
			{
				member.find(".team-content").slideDown(800);
				member.find(".hideshow").html("hide bio");
			}
			
			return false;
		});
		jQuery(this).find(".hideshowlink").click(function()
		{
			if (member.find(".hideshow").html() == "hide bio")
			{
				member.find(".team-content").slideUp(800);
				member.find(".hideshow").html("show bio");
			}
			else
			{
				member.find(".team-content").slideDown(800);
				member.find(".hideshow").html("hide bio");
			}
			
			return false;
		});
	});
	
	jQuery(".slider .panel, .projects .project, .inner-slider .panel").each(function()
	{
		var sliderPanel = jQuery(this);
		jQuery(this).find("img.image-bordered").hover(
			function()
			{
				sliderPanel.find(".block-text").show();
				sliderPanel.find(".block-image").hide();
			},
			function()
			{
				
			}
		);
		
		jQuery(this).find(".block-text").hover(
			function()
			{
				
			},
			function()
			{
				sliderPanel.find(".block-text").hide();
				sliderPanel.find(".block-image").show();
			}
		);
	});
	
	if (jQuery(".case-studies").length > 0)
	{
		jQuery(".case-studies .case-study:first .rightbar .previous").remove();
		jQuery(".case-studies .case-study:last .rightbar .next").remove();
	};
	
	jQuery(".case-studies .case-study").each(function()
	{
		var caseStudy = jQuery(this);
		jQuery(this).find(".pager a:first").addClass("selected");
		jQuery(this).find(".preview-images img:first").show();
		jQuery(this).find(".pager a").click(function()
		{
			caseStudy.find(".pager a").removeClass("selected");
			jQuery(this).addClass("selected");
			
			var pagerIndex = caseStudy.find(".pager a").index(jQuery(this));
			
			caseStudy.find(".preview-images img").hide();
			caseStudy.find(".preview-images img:eq(" + pagerIndex + ")").fadeIn();
			
			return false;
		});
	});

        if (typeof(total_events) !== 'undefined')
            setTimeout ("rotate_events()", rotation_interval);
        if (typeof(quote_order) !== 'undefined')
            setTimeout ("rotate_quote()", rotation_interval + 1000);
});

var rotate = 0;

var event_width = 700;

function rotate_events() {
    rotate = (rotate < (event_width * total_events)) ? rotate + 700 : 0
    var delay = 1500;

    if (rotate) {
        $('.events_slider ul').animate({left: 0 - rotate}, 
            delay, 
            function() { 
                setTimeout ("rotate_events()", rotation_interval); 
            } 
        );
    }
    else {
        $('.events_slider ul').fadeOut(1000, 
            function() { 
                $('.events_slider ul').animate({left: 0 - rotate}, 
                    0, 
                    function() {
                        $('.events_slider ul').show('fast'); 
                        setTimeout ("rotate_events()", rotation_interval); 
                    } 
                );
            } 
        );
    }
}

var quote_rotation_interval = 8000;

function rotate_quote() {

    $('#quote').fadeOut(2000, function() { 
            $.ajax({
                url: "/site/get_quote",
                        type: 'POST',
                        data: { quote_offset: quote_order},
                        success: function(msg) {
                        $('#quote').html(msg);
                        quote_order++;
                        if (quote_order > max_quotes)
                            quote_order = 0;
                        $('#quote').fadeIn(2000, function() {
                                setTimeout ("rotate_quote()", quote_rotation_interval); }); 
                    },
                        failure: function(msg) {
                        setTimeout ("rotate_quote()", quote_rotation_interval);

                    }
                });
        });
}

