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
  <div class="triple">
			
    <div class="column">
      <h2>
        <img src="/img/h-fission-blog.gif" alt="Fission Blog" style="padding-top:4px;" /> 
        <a href="/feed/">
          <img src="/img/icon-rss.gif" alt="" />
        </a>
      </h2>
      <h3 class="fission"><a href="/blog/post/<?php echo $latest_blog->name ?>/"><?php echo $latest_blog->title ?></a></h3>
      <?php
        $latest_blog->date = date('F j', strtotime($latest_blog->date));
      ?>
      <small class="date sidebar_blog_date">posted by <?php echo $latest_blog->author ?> on <?php echo $latest_blog->date ?></small>
      <a class="more" href="/blog/post/<?php echo $latest_blog->name ?>/">more from the blog</a>

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
      <?php echo $quote; ?>
      <div class="author">
	<?php echo $author; ?>
      </div>
      <a href="/clients/" class="more">meet more of our clients</a>
    </div>
  </div>
</div>
