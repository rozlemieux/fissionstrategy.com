<div class="sidebar">


   <?php if ($blogroll) { ?>
   <h2><img alt="blog roll" src="/img/h-blog-roll.gif"></h2>
   <div class="block">
     <ul>
       <?php foreach ($blogroll as $roll) { ?>
        <li><a target="_blank" href="<?php echo $roll->url ?>"><?php echo $roll->name ?></a></li>
       <?php } ?>
     </ul>
   </div>
   <?php } ?>

   <?php 
     $slider_class = 'inner-slider';
     $image_height = '168px';
     $carousel = true;
     include "case_studies.php"; ?>

  <div class="block">
    <a href="/projects" class="view-all">view more projects</a>
  </div><!-- end of block -->
  <div class="block">
    <div class="organizations" style="background-color: white;padding:0">
       <a href="/contact/">
          <img alt="we help organizations inspire social good through social media" src="/img/we-help-orgs-side.gif">
       </a>
    </div>
  </div>

  <div class="block">
     <h2>
        <img alt="Latest tweet" src="/img/h-latest-tweet.gif"> 
          <a href="http://twitter.com/fissionstrategy">
            <img alt="" src="/img/icon-twitter.gif">
         </a>
      </h2>
      <?php include "twitter.php";  ?>
  </div>

  <div class="block">
    <h2><img src="/img/h-fission-blog.gif" alt="Fission Blog" /> 
         <a  href="/feed/">
           <img src="/img/icon-rss.gif" alt="" />
         </a>
    </h2>
    <?php
      $latest_blog->date = date('F j', strtotime($latest_blog->date));
    ?>
    <p style="padding-top: 3px;">
      <a href="/blog/<?php echo $latest_blog->name ?>/"><img src="/uploads/images/Blog/<?php echo $latest_blog->thumb  ?>" width="240" alt="" /></a>
    </p>
    <h3 class="fission"><a href="/blog/<?php echo $latest_blog->name ?>/"><?php echo $latest_blog->title ?></a></h3>
    <small class="date sidebar_blog_date">posted by <?php echo $latest_blog->author ?> on <?php echo $latest_blog->date ?></small>
    <div class="clear:both"></div>
    <a href="/blog/" class="more">more from the blog</a>	
  </div>

</div>
