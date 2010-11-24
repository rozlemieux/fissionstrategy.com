<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:fb="http://www.facebook.com/2008/fbml" xmlns:og="http://opengraph.org/schema/" lang="en">
  <head profile="http://gmpg.org/xfn/11">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"> 
    <meta content="<?php echo $SEO_description ?>" name="description">
    <?php if ($facebook_image) { ?>
    <meta content="<?php echo $facebook_image ?>" property="og:image">
    <meta content="<?php echo $facebook_image ?>" name="image_src">
    <?php } ?>
      <title>Fission Strategy</title>
      <link rel="shortcut icon" href="/favicon.ico" /> 
      <link rel="stylesheet" href="<?php echo base_url();?>/css/style.css" type="text/css" media="screen" />

      <link rel="stylesheet" type="text/css" href="<?php echo base_url();?>/js/fancybox/jquery.fancybox-1.3.1.css" media="screen" />
      
      <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js" type="text/javascript" charset="utf-8"></script>	
      <script type="text/javascript" src="<?php echo base_url();?>/js/jquery.jcarousel.js"></script>
      <script type="text/javascript" src="<?php echo base_url();?>/js/scripts.js"></script>
      <script type="text/javascript" src="<?php echo base_url();?>/js/fancybox/jquery.fancybox-1.3.1.js"></script>
      <script type="text/javascript" src="<?php echo base_url();?>/js/jcarousel_init.js"></script>

      <script type="text/javascript">
	$(document).ready(function()
	{
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
      </script>
    </head>

    <body>

      <div class="wrapper">
	<div class="header">
	  <a href="/" class="logo"></a>

          <?php
            if (isset($main_content)) {
               $home_active = ($main_content == 'homepage') ? " class='page_item current_page_item' " : '';
               $team_active = ($main_content == 'team') ? " class='current_page_item' " : '';
               $services_active = ($main_content == 'services') ? " class='current_page_item' " : '';
               $clients_active = ($main_content == 'clients') ? " class='current_page_item' " : '';
               $blog_active = ($main_content == 'blog') ? " class='current_page_item' " : '';
            }
          ?>
          <div class="navigation">
	    <ul>
	      <li <?php echo $home_active ?>><a title="home" href="/">home</a></li>
              <li <?php echo $team_active ?>><a title="team" href="/team">team</a></li>
              <li <?php echo $services_active ?>><a title="services" href="/services/">services</a></li>
              <li <?php echo $clients_active ?>><a title="clients" href="/clients/">clients</a></li>
              <li <?php echo $blog_active ?>><a title="blog" href="/blog/">blog</a></li>
	    </ul>
	  </div>
	</div>
