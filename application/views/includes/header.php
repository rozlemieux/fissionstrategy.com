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
      <link rel="stylesheet" href="<?php echo base_url();?>/css/style.css?9" type="text/css" media="screen" />

      <link rel="stylesheet" type="text/css" href="<?php echo base_url();?>/js/fancybox/jquery.fancybox-1.3.1.css" media="screen" />
      
      <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js" type="text/javascript" charset="utf-8"></script>	
      <script type="text/javascript" src="<?php echo base_url();?>/js/jquery.jcarousel.js"></script>
      <script type="text/javascript" src="<?php echo base_url();?>/js/scripts.js?6"></script>
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
        <a href="/contact" id="contact-us" ></a>
        <span class="hdr-facebook"><img src="/img/facebook.gif" alt="" /></span>
	<div class="header">
          <a href="http://smsadvocacy.com" id="sms-advocacy" target="_blank"></a>
	  <a href="/" class="logo"></a>

         <?php if ( $this->session && $this->session->userdata('username')) {    ?>
          <div id="logout">
          Welcome back <strong><?php echo $this->session->userdata('username'); ?></strong>&nbsp;-&nbsp;
          <?php                                                                               
             echo anchor('/CMS/login/logout', 'Logout'); 
             if ($this->session->userdata('id') > 9)  
                echo '&nbsp; - <a href="/notes">notes</a>';
             else 
                echo '&nbsp; - <a href="/CMS">CMS</a>';
          ?>
          </div>                                                                               
          <?php } ?>

          <?php
            if (isset($main_content)) {
               $home_active = ($main_content == 'homepage') ? " class='page_item current_page_item' " : '';
               $team_active = ($main_content == 'team') ? " class='current_page_item' " : '';
               $services_active = ($main_content == 'services') ? " class='current_page_item' " : '';
               $projects_active = ($main_content == 'projects') ? " class='current_page_item' " : '';
               $events_active = ($main_content == 'events') ? " class='current_page_item' " : '';
               $blog_active = ($main_content == 'blog') ? " class='current_page_item' " : '';
            }
          ?>
          <div class="navigation">
	    <ul>
	      <li id="nav-home" <?php echo $home_active ?>><a alt="home" href="/"><img src="/img/nav-home.gif" /></a></li>
              <li id="nav-team" <?php echo $team_active ?>><a alt="team" href="/team"><img src="/img/nav-team.gif" /></a></li>
              <li id="nav-services" <?php echo $services_active ?>><a alt="services" href="/services/"><img src="/img/nav-services.gif" /></a></li>
              <li id="nav-projects" <?php echo $projects_active ?>><a alt="projects" href="/projects/"><img src="/img/nav-projects.gif" /></a></li>
              <li id="nav-events" <?php echo $events_active ?>><a alt="events" href="/events/"><img src="/img/nav-events.gif" /></a></li>
              <li id="nav-blog" <?php echo $blog_active ?>><a alt="blog" href="/blog/"><img src="/img/nav-blog.gif" /></a></li>
	    </ul>
	  </div>
	</div>

