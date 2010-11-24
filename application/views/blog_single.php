<div class="main">
  <div class="content">
  <h3 class="title">blog</h3>
  <?php
     foreach ($blogposts as $blog) { 
        $date = date('F j', strtotime($blog->date));

     ?>
  <h1><?php echo $blog->title ?></h1>
  <small class="date">posted by <?php echo $blog->author ?> on <?php echo $date ?></small>
  <?php 
     echo $blog->content;

  $url = $this->config->item('base_url') . "site/blog/" . $blog->name;
        
  ?>

  <div class="share">
        <div style="float: left;">
          <script type="text/javascript">tweetmeme_style = 'compact';</script>
          <script src="http://tweetmeme.com/i/scripts/button.js" type="text/javascript"></script>

        </div>
        <a style="padding-right: 27px; float: left; text-decoration: none;" data-button-style="small-count" href="http://www.google.com/buzz/post" class="google-buzz-button" title="Post on Google Buzz"><span class="buzz-counter-long" dir="ltr" id="buzz-1691991490">0</span></a>
        <script src="http://www.google.com/buzz/api/button.js" type="text/javascript"></script>

        <a style="float: left;" href="mailto:?subject=<?php echo urlencode($blog->title) ?>&amp;body=I thought you would like this article: Mobile Organizing, Your Organization…And The Future - <?php echo $url ?>"><img alt="" src="http://fissionstrategy.com/wp-content/themes/fission/images/share-email.gif"></a>
   </div>


   <div class="comments" id="comments">
	<div id="fb-root"></div>
	<script>
	  window.fbAsyncInit = function() {
	    FB.init({appId: '120434407991867', status: true, cookie: true,
	             xfbml: true});
	  };
	  (function() {
	    var e = document.createElement('script'); e.async = true;
	    e.src = document.location.protocol +
	      '//connect.facebook.net/en_US/all.js';
	    document.getElementById('fb-root').appendChild(e);
	  }());
	</script>
	<fb:comments width="650"></fb:comments>
   </div>

  <?php  } ?>

</div>
