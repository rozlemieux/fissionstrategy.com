<?php 

echo '<div class="breadcrumbs">' . $page_title . '</div>';
echo js_calendar_script('my_form');  
?> 

<?php

$attributes = array('id' => 'my_form', 'name' => 'my_form');

if ($id)
    echo form_open_multipart("/CMS/blog/edit/$id", $attributes);
else 
    echo form_open_multipart('/CMS/blog/new_blog', $attributes);

$data = array('name' => 'submit','value' => 'Save changes','class' => 'submit');
echo form_submit($data);

echo '<div id="admin_edit">';
echo "<div id='admin_title'>";
echo form_label('Title:', 'title');
echo form_input('title', $blog->title);
echo "</div>";

?>


<div class="sidebar">

  <div class="block cms_blue">
    <div class="status clear">Status:
      <?php
      $options = array(
                  'draft'  => 'Draft',
                  'publish'    => 'Publish',
                  'private'   => 'Private',
                  'trash'   => 'Trash',
                );

             echo form_dropdown('status', $options, $blog->status); 

      if ($blog->id)
              echo "<br /><a href=\"/blog/preview/{$blog->name}\">preview</a>";

      ?>
    </div>

    <div class="status clear">Thumbnail:&nbsp;
      <div id="blog_photo">
        <?php if ($blog->thumb) { ?>
          <img width="100" class="image" src="/uploads/images/Blog/<?php echo $blog->thumb ?>" />
          <div id="photo_upload">
            <h5>Replace Thumbnail:</h5>
            <input type="file" name="userfile" style="font-size:11px" />
          </div>
        <?php } else { ?>
          <div id="photo_upload">
            <h5>Upload a Thumbnail:</h5>
            <input type="file" name="userfile" />
          </div>
        <?php } ?>
       </div>
    </div>

    <div class="status clear">Author:
        <?php echo form_dropdown('author', $authors, $blog->author);      ?>
    </div>

    <div class="status clear">Date/time:&nbsp;&nbsp;    
     <div class="blogdate">
       <?php echo js_calendar_write('date', time(), true); ?>
         <input id="blogdateinput" type="text" name="date" value="<?php echo $blog->date ?>" onblur="update_calendar(this.name, this.value);" />
      </div>
    </div>
  </div>
</div>

<div id="edit_template" style="clear:left; ">

  <?php

  echo form_textarea('content', $blog->content);
  echo display_ckeditor($ckeditor); 

echo "</div>";

$data = array('name' => 'submit','value' => 'Save changes','class' => 'submit');
echo form_submit($data);

echo form_close();

?>

