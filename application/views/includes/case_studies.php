<?php 
    // preload hover images ?>
    <div style="display:none">
             <img src="/img/arrow-right-hover.gif" />
             <img src="/img/arrow-left-hover.gif" />
   </div>

<?php
if ($carousel) { 
    echo "<div class=\"$slider_class\">";
    echo '<a href="" class="arrow-left"></a>';
    echo '<ul class="belt">';
} 


foreach ($case_studies as $case_study) {  
    $content = $case_study->content;
    $content = (strlen($content) > 80) ? substr($content, 0, 80) : $content;

    $image_style = ' style="height:270px;width:270px;overflow:hidden;background-color:white" ';
    $image_width =  ' ';
    if (($main_content == 'services') || ($main_content == 'team') || ($main_content == 'clients') || ($main_content == 'blog')  || ($main_content == 'blog_single') || ($main_content == 'contact_form') || ($main_content == 'case_study')) {
        $image_width =  ' width="206px" ';
        $image_style = ' style="height:160px;width:190px;overflow:hidden;border-right: 4px solid white; border-bottom: 4px solid white; background-color:white" ';
    }
    if ($carousel) 
        echo '<li class="panel">'; 
    else echo '<div class="project">'; 

?>
      <div class="block-image">
           <?php if ($case_study->new == 'Y') { ?>
        <span class="new"></span>
        <?php } ?>

        <?php foreach ($case_study->photos as $photo) { ?>
          <div <?php echo $image_style ?>>
           <img <?php echo $image_width ?> src="/uploads/images/CaseStudies/<?php echo $photo ?>.sm.jpg" alt="" class="image-bordered" />
                  </div>
                  <?php break; } ?>
        <h1><?php echo $case_study->title ?></h1>
        <?php foreach ($case_study->tags as $tag) { 
           echo "<a href='/case_studies/page/0/{$tag['id']}' title='View all posts in {$tag['name']}' rel='category tag'>{$tag['name']}</a>&nbsp;";
          } ?>
      </div>
      <div class="block-text">
        <h1><?php echo $case_study->title ?></h1>
        <p>
	  <a href="/case_studies/" title="View all posts in Case Studies" rel="category tag">Case Studies</a>
        </p>
        <p>
	  <?php echo $content ?>
        </p>
        <a href="#slice-<?php echo $case_study->id ?>" rel="casestudy" id="inline" class="more lightbox">read the case study</a>
      </div>
           <?php if ($carousel) echo '</li>'; else echo '</div>'; ?>

    <?php } ?>

  <?php if ($carousel) {
    echo '</ul>';
    echo '<a href="" class="arrow-right"></a>';			
  } ?>

</div>

<div style="display: none" class="case-studies">
  
  <?php 
     foreach ($case_studies as $case_study) {  
       $content = $case_study->content;
       $content = (strlen($content) > 80) ? substr($content, 0, 80) : $content;
       $name = $case_study->name;
       $id = $case_study->id;
       $title = $case_study->title;
       $site_url = $this->config->site_url();
  ?>
  <div class="case-study" id="slice-<?php echo $case_study->id ?>">
    <span class="casestydyid" style="display: none"><?php echo $case_study->id ?></span>
    <h2><a href="#" class="close" onclick="parent.$.fancybox.close();">
        <img src="/img/close.gif" alt="" /></a>
      <img src="/img/h2-case-study.gif" alt="" /></h2>
    <div class="leftbar">
      <div class="preview-images">
        <?php foreach ($case_study->photos as $photo) { ?>
           <img src="/uploads/images/CaseStudies/<?php echo $photo ?>.jpg" alt="" />
         <?php } ?>
      </div>

      <div class="pager">
        <?php 
         if (count($case_study->photos) > 1) {
          $selected = ' class="selected" ';
          $i = 1;
          foreach ($case_study->photos as $photo) { ?>
             <a href="#" class=""><?php echo $i ?></a>
        <?php $i++; $selected = ' class="" '; } }?>
      </div>

      <div class="share">
	share this project:
	<a href="http://www.facebook.com/sharer.php?u=<?php echo $site_url ?>case_studies/study/<?php echo $id ?>/&t=<?php echo $title ?>" target="_blank"><img src="/img/icon-facebook.gif" alt="" /></a>
       <?php $tw_status = urlencode("Check out Fission Strategy's work with: $title - "); ?>
	<a href="http://twitter.com/home?status=<?php echo $tw_status ?><?php echo $site_url ?>case_studies/study/<?php echo $id ?>/" target="_blank"><img src="/img/icon-twitter.gif" alt="" /></a>
	<a href="http://www.linkedin.com/shareArticle?mini=true&url=<?php echo $site_url ?>case_studies/study/<?php echo $id ?>/&title=<?php echo $title ?>" target="_blank"><img src="/img/icon-in.gif" alt="" /></a>
	<a href="mailto:?subject=FW: <?php echo $title ?>&body=I thought this would interest you. Check out Fission Strategy's work with: <?php echo $title ?> - <?php echo $site_url ?>case_studies/study/<?php echo $id ?>/" target="_blank"><img src="/img/icon-letter.gif" alt="" /></a>
      </div>
    </div>
    <div class="rightbar">
      <h3><?php echo $case_study->title ?></h3>
      <div class="links"><a href="/case_studies/" title="View all posts in Case Studies" rel="category tag">Case Studies</a>
      </div>
      <div class="case-text">
	<div class="case-desc">
          <?php echo $case_study->content ?>
	</div>
      </div>

      <?php if ($case_study->url) { ?>
      <div class="live-site-link">
	  <a href="<?php echo $case_study->url ?>" target="_blank">visit the live site</a>
      </div>
      <?php } ?>

      <?php if ($case_study->quote) { ?>
      <div class="spacer15px">&nbsp;</div>
      <h4>message from our client</h4>                                
      <div class="message">
         <p><?php echo $case_study->quote; ?></p>
         <div class="author"><?php echo '<p>' . str_replace("\r\n", '</p><p>', $case_study->author) . '</p>'?></div>
      </div>
      <?php } ?>

      <div class="spacer15px">&nbsp;</div>
      <div class="case"><a href="#" class="previous" onclick="$.fancybox.prev(); return false;"><img src="/img/case-study-previous.gif" alt="" /></a><a href="#" class="next" onclick="$.fancybox.next(); return false;"><img src="/img/case-study-next.gif" alt="" /></a>
      </div>
    </div>
  </div>

  <?php } ?>
  
</div>
