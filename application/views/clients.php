<div class="main projects_main">

    <h3 class="title"><?php echo $page_model->title ?></h3>

    <?php echo $page_model->content ?>

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
         if ($pages > 1) {
           while ($p < $pages) {
                     $selected = ($p == $page) ? ' class="selected" ' : '';
                     echo "<a $selected href='/clients/page/$p'>" . ($p + 1) . "</a>";
                     $p++;
               } 
         }?>
         </div>




