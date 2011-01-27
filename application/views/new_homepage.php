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
      <link rel="stylesheet" href="<?php echo base_url();?>/css/style.css?1" type="text/css" media="screen" />

      <link rel="stylesheet" type="text/css" href="<?php echo base_url();?>/js/fancybox/jquery.fancybox-1.3.1.css" media="screen" />
      
      <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js" type="text/javascript" charset="utf-8"></script>	
      <script type="text/javascript" src="<?php echo base_url();?>/js/jquery.jcarousel.js"></script>
      <script type="text/javascript" src="<?php echo base_url();?>/js/scripts.js?1"></script>
      <script type="text/javascript" src="<?php echo base_url();?>/js/fancybox/jquery.fancybox-1.3.1.js"></script>
      <script type="text/javascript" src="<?php echo base_url();?>/js/jcarousel_init.js"></script>

<style>
 .navigation {
        float: right;
       padding-top: 20px;
 }
.header .logo {
	background-image: url(/img/logo.gif);
	background-repeat: repeat-x;
	width: 407px;
	height: 85px;
	float: left;
        margin-top: -20px;
}
</style>

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
        <a href="http://smsadvocacy.com" id="sms-advocacy" target="_blank"></a>
	<div class="header">
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
               $clients_active = ($main_content == 'clients') ? " class='current_page_item' " : '';
               $blog_active = ($main_content == 'blog') ? " class='current_page_item' " : '';
               $events_active = ($main_content == 'events') ? " class='current_page_item' " : '';
               $projects_active = ($main_content == 'projects') ? " class='current_page_item' " : '';
               $contact_active = ($main_content == 'contact_form') ? " class='current_page_item' " : '';
            }
          ?>
          <div class="navigation">
	    <ul>
	      <li <?php echo $home_active ?>><a title="home" href="/">home</a></li>
              <li <?php echo $team_active ?>><a title="team" href="/team">team</a></li>
              <li <?php echo $services_active ?>><a title="services" href="/services/">services</a></li>
              <li <?php echo $clients_active ?>><a title="clients" href="/clients/">clients</a></li>
              <li <?php echo $blog_active ?>><a title="blog" href="/blog/">blog</a></li>
              <li <?php echo $events_active ?>><a title="events" href="/events">events</a></li>
              <li <?php echo $projects_active ?>><a title="projects" href="/projects/">projects</a></li>
              <li <?php echo $contact_active ?>><a title="contact" href="/contact/">contact</a></li>
	    </ul>
	  </div>
	</div>


<div id="main_content">



<div class="main home">
  <a href="/projects/" class="view-all alignright">view more projects</a>
  <img src="/img/love-our-clients.gif" alt="" />
  <?php
     $slider_class = 'slider';
     $image_height = '227px';
     $carousel = true;
     include "includes/case_studies.php";
     ?>

  <div style="background-color: white; padding: 0px;" class="organizations">
    <a href="/contact/">
      <img style="float: left; margin-right: 10px;" alt="we help organizations inspire social good through social media" src="/img/we-help-organizations.gif">
    </a>
  </div>
  <?php $display_events = ($events) ? 'block' : 'none'; ?>
  <div style="margin-top: 40px;display:<?php echo $display_events ?>">
     <div style="width=300px;float:right"><img src="/img/events-see-us.gif" /></div>
     <div class="events_slider">
         <ul>
           <?php 
           $i = 0;
           foreach ($events as $event) {
             $time = date('g:i a', strtotime($event->date));
             $time = ($time != '12:00 am') ? ' at ' . $time : '';
             $year = date('Y', strtotime($event->date));
             $month = date('m', strtotime($event->date));
             echo '<li><a href="/events/calendar/' . $year . '/' . $month . '">';
             echo '<b>' . date('F j', strtotime($event->date)) . $time . '</b>  --  ';
             echo strip_tags($event->title, '<b>') . '</a></li>';
             if ($i++ > 2) break;
           }
           ?>
        </ul>
        <script type="text/javascript">var total_events = '<?php echo ($i - 1) ?>';</script>
     </div>
  </div>
  <div class="triple" style="padding-top:20px;">
			
    <div class="column">
      <h2>
        <img src="/img/h-fission-blog.gif" alt="Fission Blog" style="padding-top:4px;" /> 
        <a href="/feed/">
          <img src="/img/icon-rss.gif" alt="" />
        </a>
      </h2>
      <h3 class="fission"><a href="/blog/<?php echo $latest_blog->name ?>/"><?php echo $latest_blog->title ?></a></h3>
      <?php
        $latest_blog->date = date('F j', strtotime($latest_blog->date));
      ?>
      <small class="date sidebar_blog_date">posted by <?php echo $latest_blog->author ?> on <?php echo $latest_blog->date ?></small>
      <a class="more" href="/blog/">more from the blog</a>

    </div>

    <div class="column">
      <h2><img src="/img/h-latest-tweet.gif" alt="Latest Tweet" /> 
        <a href="http://twitter.com/fissionstrategy">
          <img src="/img/icon-twitter.gif" alt="" />
        </a>
      </h2>
      <?php 
         include "includes/twitter.php";  ?>
    </div>

    <div class="column">
      <h2><img src="/img/h-from-our-clients.gif" alt="from our clients" style="padding-top:7px;" /></h2>
      <div id="quote"><?php echo $quote; ?>
        <div class="author">
  	  <?php echo $author; 
               $quote_url =  $this->config->item('base_url') . 'projects/preview/' . $case_study->id;
           ?>
        </div>
        <br/><a href="<?php echo $quote_url ?>" class="more">meet more of our clients</a>
      </div>
      <script> 
               var quote_order = '<?php echo $quote_order ?>'; 
               var max_quotes = '<?php echo $max_quotes ?>'; 
      </script>
      
    </div>
  </div>
</div>

</div>
</div>
<div class="footer">
  <div class="wrapper">
    <div class="strategy">
      Fission Strategy 
      <a href="/contact/">Washington DC</a>
      <a href="/contact/">San Francisco</a>
      <a href="/contact/">New York</a>
      <a href="/contact/">Atlanta</a>
      <a href="/contact/">Detroit</a>
      <a href="/contact/">Boston</a>
    </div>
    <div class="social">
      <span class="facebook"><img src="/img/facebook.gif" alt="" /></span>
      <a href="http://www.facebook.com/FissionStrategy"><img src="/img/icon-facebook.gif" alt="" /></a>
      <a href="http://twitter.com/fissionstrategy"><img src="/img/icon-twitter.gif" alt="" /></a>
      <a href="http://www.linkedin.com/companies/339832"><img src="/img/icon-in.gif" alt="" /></a>
      <span class="terrapass">
        <a href="http://www.terrapass.com/partners/fission-strategy/?utm_source=fission_strategy&amp;utm_campaign=smb-partner">
            <img target="_blank" src="http://www.terrapass.com/images/partners/white-badge-small.gif" 
                        style="border: medium none;" alt="Fission Strategy - carbon balanced with TerraPass">
        </a>
      </span>
    </div>

  </div>
</div>

<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-16658069-1']);
  _gaq.push(['_setDomainName', '.fissionstrategy.com']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>
</body>
</html>
