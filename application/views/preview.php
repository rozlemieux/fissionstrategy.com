<div class="main home projects_main">

  <h3 class="title">
    <a class="alignright" href="/contact/"><img class="tell-us" alt="" src="/img/tell-us.gif"></a>
    <img class="alignright" alt="we achieve results for your organization" src="/img/we-achieve.gif">
    projects
  </h3>

  <div class="projects">
    <?php
       $slider_class = 'project';
       $image_height = '227px';
       $carousel = false;
       include "includes/case_studies.php";
       ?>

  </div>
  <div class="pager">
     <?php 
     $p = 0;
     while ($p < $pages) {
        $selected = ($p == $page) ? ' class="selected" ' : '';
        echo "<a $selected href='/projects/page/$p'>" . ($p + 1) . "</a>";
        $p++;
     } ?>
  </div>



</div>
<script>
jQuery(document).ready(function() { 

        //alert(" clicking <?php echo $preview ?>");
       $('a[href=#slice-<?php echo $preview?>]').click();

});
</script>
