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

        setTimeout ("rotate_images()", rotation_interval);
});

var rotate = 0;
var last_event = 3 * 700;

function rotate_images() {
    rotate = (rotate < last_event) ? rotate + 700 : 0
    var delay = 1500;

    if (rotate) {
        $('.events_slider ul').animate({left: 0 - rotate}, 
            delay, 
            function() { 
                setTimeout ("rotate_images()", rotation_interval); 
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
                        setTimeout ("rotate_images()", rotation_interval); 
                    } 
                );
            } 
        );
    }
}

