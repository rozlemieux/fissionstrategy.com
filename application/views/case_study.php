<div class="main">
  <div class="content">
  <?php
     $date = date('F j', strtotime($case_study->date));
     ?>

  <h1><?php echo $case_study->title ?></h1>
<!--  <small class="date">posted by <?php echo $case_study->author ?> on <?php echo $date ?></small> -->
  <?php 
     echo $case_study->content;

  $url = $this->config->item('base_url') . "site/case_study/" . $case_study->name;
        
  ?>

  <div class="share">
        <div style="float: left;">
          <script type="text/javascript">tweetmeme_style = 'compact';</script>
          <script src="http://tweetmeme.com/i/scripts/button.js" type="text/javascript"></script>

        </div>
        <a style="padding-right: 27px; float: left; text-decoration: none;" data-button-style="small-count" href="http://www.google.com/buzz/post" class="google-buzz-button" title="Post on Google Buzz"><span class="buzz-counter-long" dir="ltr" id="buzz-1691991490">0</span></a>
        <script src="http://www.google.com/buzz/api/button.js" type="text/javascript"></script>

        <a style="float: left;" href="mailto:?subject=<?php echo urlencode($case_study->title) ?>&amp;body=I thought you would like this article: Mobile Organizing, Your Organization…And The Future - <?php echo $url ?>"><img alt="" src="http://fissionstrategy.com/wp-content/themes/fission/images/share-email.gif"></a>
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

</div>
