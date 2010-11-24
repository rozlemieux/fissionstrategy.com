<?php
echo '<div class="breadcrumbs">' . $page_title . '</div>';

if ($id)
    echo form_open_multipart("/CMS/team/edit/$id");
else 
    echo form_open_multipart('/CMS/team/new_team_member');

$data = array('name' => 'submit','value' => 'Save changes','class' => 'submit');
echo form_submit($data);

echo '<div id="admin_edit">';
?>

<div class="sidebar">

  <div class="block cms_blue">
    <div class="status clear">Avatar:&nbsp;
      <div id="blog_photo">
        <?php if ($team_member->photo) { ?>
          <img width="100" class="image" src="/uploads/images/Team/<?php echo $team_member->photo ?>" />
          <div id="photo_upload">
            <h5>Replace Avatar:</h5>
            <input type="file" name="userfile" style="font-size:11px" />
          </div>
        <?php } else { ?>
          <div id="photo_upload">
            <h5>Upload an Avatar:</h5>
            <input type="file" name="userfile" />
          </div>
        <?php } ?>
       </div>
    </div>

    <div class="status clear_only">Name:
       <div class="clear_only"> <?php echo form_input('name', $team_member->name); ?></div>
    </div>

    <div class="status clear_only">Title:
       <div class="clear_only"> <?php echo form_input('team_title', $team_member->team_title); ?></div>
    </div>

    <div class="status clear_only">Email:
       <div class="clear_only"> <?php echo form_input('email', $team_member->email); ?></div>
    </div>

    <div class="status clear_only">Linkedin:
       <div class="clear_only"> <?php echo form_input('linkedin', $team_member->linkedin); ?></div>
    </div>

    <div class="status clear_only">Twitter:
       <div class="clear_only"> <?php echo form_input('twitter', $team_member->twitter); ?></div>
    </div>

       <div class="status clear_only">Skype:
      <div class="clear_only"> <?php echo form_input('skype', $team_member->skype); ?></div>
    </div>
  </div>
</div>

<?php

$data = array(
    'name'        => 'content',
    'value'       => '',
    'id'          => 'content',
    'cols'        => '100',
    'rows'        => '50',
);

echo form_textarea('content', $team_member->content);
echo display_ckeditor($ckeditor); 

$data = array('name' => 'submit','value' => 'Save changes','class' => 'submit');
echo form_submit($data);
echo form_close();
?>

</div>