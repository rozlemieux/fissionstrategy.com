<div class="main">
  <div class="content">
     <?php 
        if (!$selected_name) {
            $display = '';
            $showhide = 'show bio';
        } else {
            $display = ' style="display:block" ';
            $showhide = 'hide bio';
        }
     ?>


    <?php if (! $selected_name) { ?>
    <h3 class="title">team</h3>
    <div class="organizations">
      <img alt="" src="/img/sun-light.gif">
      <img alt="want to work with our experienced team" src="/img/want-to-work.gif">
      <a href="/contact/">contact us.</a>
    </div>
    <?php } ?>
    <h1>Meet Our Team  <?php if ($selected_name) echo ' Member - ' . $selected_name; ?></h1>
    <?php 
    foreach ($team_members as $team_member) { 
      $name = $team_member->name;
    ?>
    <div class="team">
      <div class="team-main">
        <img width="100" class="image" alt="<?php echo $name ?>" src="/uploads/images/Team/<?php echo $team_member->photo ?>">
        <h3><a class="hideshowlink" href="#"><?php echo $name ?></a> 
          <span class="bio">[ <a class="hideshow" href=""><?php echo $showhide ?></a> ]</span></h3>
        <h4><?php echo $team_member->team_title; ?></h4>
        <div class="social">
          <?php 
             if ($team_member->linkedin)  {
               $l = (strpos($team_member->linkedin, 'http://') !== false) ? $team_member->linkedin : "http://www.linkedin.com/in/" . $team_member->linkedin;
               echo '<a href="' . $l . '"><img alt="" src="/img/icon-in-small.gif"></a>';
             }
             if ($team_member->twitter)   
               echo '<a href="http://twitter.com/' . $team_member->twitter . '"><img alt="" src="/img/icon-twitter-small.gif"></a>';
             if ($team_member->skype) {
                 echo "<a href='javascript:void(0)' title='{$name} can be reached on Skype at {$team_member->skype}'> ";
                 $name = str_replace('"', '\"', $name);
                 echo "<img onclick='javascript:alert(\"{$name} can be reached on Skype at {$team_member->skype}\"); return false;'";
                 echo " src='/img/icon-skype.gif'></a>";
             }
             ?>
          <span> | </span>
          <img alt="" src="/img/icon-mail.gif">
          <?php    
             $email = $team_member->email;
             $email_address = str_replace(' at ' , '@', $team_member->email);
          ?>
          <a href="mailto:<?php echo $email_address ?>"><?php echo $email ?></a>
        </div>
      </div>
      <div class="team-content" <?php echo $display ?> >
      <span><?php echo $team_member->content; ?></span>
        <span class="bio">[ <a class="hideshow" href=""><?php echo $showhide ?></a> ]</span>
      </div>

    <?php if ($selected_name) { ?>
    <br />                            
    <div class="organizations">
      <img alt="" src="/img/sun-light.gif">
      <img alt="want to work with our experienced team" src="/img/want-to-work.gif">
      <a href="/contact/">contact us.</a>
    </div>
    <?php } ?>

    </div>
    <?php  }  ?>

 </div>

