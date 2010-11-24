<div class="main">
  <div class="content">
      <h1>Blog
        <a style="padding-left:550px" href="/feed">
          <img src="/img/icon-rss.gif" alt="" />
        </a>
      </h1>
    <?php
       foreach ($blogposts as $blog) { 

        $date = date('F j', strtotime($blog->date));

    ?>
    <div class="blog-preview">
      <a class="thumb" href="/blog/post/<?php echo $blog->name?>/">
        <img width="200px" alt="" src="/uploads/images/Blog/<?php echo $blog->thumb; ?>" /></a>
      <h3><a href="/blog/post/<?php echo $blog->name?>/"><?php echo $blog->title ?></a></h3>
      <small class="date">posted by <?php echo $blog->author ?> on <?php echo $date ?></small>
      <?php 
      $content = strip_tags($blog->content);
      $content = myTruncate($content, 350, ' ', "&nbsp;<a href=\"/blog/post/{$blog->name}\">[...]</a>");
      echo $content;
      ?>
    </div>
    <?php  } ?>

    <div class="pager">
       <?php 
       $p = 0;
       $pages = ($pages > 0) ? $pages - 1 : $pages;
       while ($p < $pages) {
           $selected = ($p == $page) ? ' class="selected" ' : '';
           echo "<a $selected href='/blog/page/$p'>" . ($p + 1) . "</a>";
           $p++;
       } ?>
    </div>

  </div>

  <?php $this->load->view("includes/sidebar"); ?>
</div>



<?php
function myTruncate($string, $limit, $break=".", $pad=" [...]") {

    if(strlen($string) <= $limit) return $string;

    if (false !== ($breakpoint = strpos($string, $break, $limit))) {
        if($breakpoint < strlen($string) - 1) {
            $string = substr($string, 0, $breakpoint) . $pad;
        }
    }
    
    return $string;
}

?>

