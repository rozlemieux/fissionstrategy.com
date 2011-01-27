<?php 

echo '<div class="breadcrumbs">' . $page_title . '</div>';
echo js_calendar_script('my_form');  

$attributes = array('id' => 'my_form', 'name' => 'my_form');

if ($id)
    echo form_open_multipart("/CMS/case_study/edit/$id", $attributes);
else 
    echo form_open_multipart('/CMS/case_study/new_case_study', $attributes);

$data = array('name' => 'submit','value' => 'Save changes','class' => 'submit');
echo form_submit($data);

echo '<div id="admin_edit">';
echo '<div class="status clear_only">Client Name:</div>';
echo "<div id='admin_title'>";
echo form_input('title', $case_study->title);
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
      echo form_dropdown('status', $options, $case_study->status);

      if ($case_study->id)
              echo "<br /><a href=\"/projects/preview/{$case_study->id}\">preview</a>";

      ?>

    </div>

    <div class="status clear">
          <div class="clear_only"> <?php echo form_checkbox('new', '', ($case_study->new == 'Y')); ?>&nbsp; New Case Study</div>
    </div>

    <div class="status clear" style='float: left; width:100%'>Category:
      <?php

         $options = array();
         foreach ($categories as $i => $cat) 
            $options[$cat['id']] = $cat['name'];

        foreach ($case_study->tags as $tag) 
           if ($tag['id'] != 1)
              $selected[] = $tag['id'];

        echo form_multiselect('category[]', $options, $selected, ' size=8 ');
      ?>
      <div class="status clear_only">New Category:
         <div class="clear_only"> <?php echo form_input('new_category', ''); ?></div>
      </div>

    </div>

         <div class="status clear">Featured Image(s):&nbsp;
      <div id="case_study_photo">
            <?php 
          $i = 0;
         foreach ($case_study->photos as $id => $photo) { ?>
          <div style="background-color: lightgray; padding: 10px 0; margin: 10px 0;">                                         
            <div style="float:right; font-size:9px">
              <input type="checkbox" name="userfile<?php echo $i ?>_delete" style="font-size:11px" />
              <label style="clear: none;padding-right: 4px" for="userfile<?php echo $i ?>_delete">Delete?</label>
              <input type="hidden" name="userfile<?php echo $i ?>_id" style="font-size:11px" value="<?php echo $id ?>"/>
            </div>
          <img width="100" class="image" src="/uploads/images/CaseStudies/<?php echo $photo ?>.sm.jpg" />
          <div id="photo_upload">
            <h5>Replace Featured Image:</h5>
            <input type="file" name="userfile<?php echo $i ?>" style="font-size:11px" />
          </div>
          </div>                                        
          <?php $i++; } ?>
          <div id="photo_upload">
            <h5>Upload a new Featured Image:</h5>
            <input type="file" name="userfile<?php echo $i ?>" />
          </div>
       </div>
    </div>

    <div class="status clear">Date/time:&nbsp;&nbsp;    
     <div class="case_studydate">
       <?php echo js_calendar_write('date', time(), true); ?>
         <input id="case_studydateinput" type="text" name="date" value="<?php echo $case_study->date ?>" onblur="update_calendar(this.name, this.value);" />
      </div>
    </div>
  </div>

</div>
<div style="clear:left; width: 700px;">

  <?php

  echo '<div class="status clear_left">Case Study Description:</div>';
  echo form_textarea('content', $case_study->content);
  echo display_ckeditor($ckeditor); 

  $data = array(
      'name'  => 'excerpt',
      'class' => 'excerpt_textarea',
      'col'   =>  60,
      'rows'  =>  5,
      'value' => $case_study->excerpt,
  );

  ?>
  <div class="status clear_left">Excerpt: (used in small popup over case study)</div>
     <div class="clear_left"> <?php echo form_textarea($data); ?></div>
  <?
  $data = array(
      'name'  => 'quote',
      'class' => 'quote_textarea',
      'col'   =>  80,
      'rows'  =>  5,
      'value' => $case_study->quote,
  );
  ?>
  <div class="status clear_left">Client Quote:</div>
     <div class="clear_left"> <?php echo form_textarea($data); ?></div>

  <?php 
     $data['name'] = 'author';
     $data['value'] = $case_study->author; 
     $data['style'] = 'height: 40px; color: #999999';?>
  <div class="status clear_left">Client Quote Author:</div>
     <div class="clear_left"> <?php echo form_textarea($data); ?></div>

  <?php 
     $data['name'] = 'url';
     $data['value'] = $case_study->url; 
     $data['style'] = 'width: 600px; height: 20px; color: black';?>
     <div class="status clear_left">Client Site URL:</div>
     <div class="clear_left"> <?php echo form_textarea($data); ?></div>

</div>
<?php
$data = array('name' => 'submit','value' => 'Save changes','class' => 'submit');
echo form_submit($data);
echo form_close();

?>

