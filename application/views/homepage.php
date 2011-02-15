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
             $repeat = '';
             if ($event->repeat) 
                 $repeat .= ' - ' . date("F j", strtotime($event->date) + (($event->repeat - 1) * (24 * 60 * 60)));

             echo '<b>' . date('F j', strtotime($event->date)) . $repeat . '</b>  --  ';
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

