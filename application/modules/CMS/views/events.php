<?php 

echo '<div class="breadcrumbs">' . $page_title . '</div>';

     if ($calendar)
         echo $calendar; 
     
     if ($events) {

         echo js_calendar_script('my_form');  

         $event = reset($events);
         if (strpos($event->date, '00:00:00') > 0)
             $date = date("F j, Y", strtotime($event->date));
         else
             $date = date("F j, Y, g:i a", strtotime($event->date));
         //         echo '<div style="font-size:12px;font-weight:bold">' . $date . "</div>";
         //         echo '<div style="font-size:13px">' . $event->description . "</div>";

         $attributes = array('id' => 'my_form', 'name' => 'my_form');

         if ($id)
             echo form_open_multipart("/CMS/events/edit/$id", $attributes);
         else 
             echo form_open_multipart('/CMS/events/new', $attributes);

         $data = array('name' => 'submit','value' => 'Save changes','class' => 'submit');
         echo form_submit($data);

         echo '<div id="admin_edit">';
         echo "<div id='admin_title'>";
         echo form_label('Title:', 'title');
         echo form_input('title', $event->title);
         echo "</div>";

         echo '<div class="sidebar">';

         echo '<div class="block cms_blue">';
         echo '<div class="status clear">Status:&nbsp;';
         echo '<div id="blog_photo">';
         $options = array(
             'draft'  => 'Draft',
             'publish'    => 'Publish',
             'private'   => 'Private',
             'trash'   => 'Trash',
         );
         echo form_dropdown('status', $options, $event->status);
         echo '</div>';
         echo '</div>';
?>
        <div class="status clear">Date/time:&nbsp;&nbsp;    
          <div class="blogdate">
        <?php echo js_calendar_write('date', time(), true); ?>
         <input id="blogdateinput" type="text" name="date" value="<?php echo $event->date ?>" onblur="update_calendar(this.name, this.value);" />
      </div>
    </div>
  </div>
  </div>
  <div style="clear:left; width: 700px;">
<?php


         $data = array(
             'name'        => 'content',
             'value'       => '',
             'id'          => 'content',
         );

         echo form_textarea('content', $event->description);
         echo display_ckeditor($ckeditor); 

         echo "</div>";

         $data = array('name' => 'submit','value' => 'Save changes','class' => 'submit');
         echo form_submit($data);
         echo form_close();

     }

     ?>


