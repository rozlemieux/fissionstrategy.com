<?php 

echo '<div class="breadcrumbs">' . $page_title . '</div>';

     if ($calendar)
         echo $calendar; 
     
     if ($events) {

         //         echo js_calendar_script('my_form');  

         $event = reset($events);
         $attributes = array('id' => 'my_form', 'name' => 'my_form');
         $hidden = array('id' => $event->id);

         echo form_open_multipart('/CMS/events/save/', $attributes, $hidden);

         $data = array('name' => 'submit','value' => 'Save changes','class' => 'submit');
         echo form_submit($data);

         echo '<div id="admin_edit">';
         echo "<div id='admin_title'>";
         echo form_label('Title:', 'title');
         echo form_input('title', $event->title);

         echo "</div>";

         echo '<div class="sidebar">';

         if (strpos($event->date, '00:00:00') > 0)
             $date = date("F j, Y", strtotime($event->date));
         else
             $date = date("F j, Y, g:i a", strtotime($event->date));

         $repeat = 0;
         if ($event->repeat > 0)
             $repeat = date("F j, Y", strtotime($event->date) + (($event->repeat - 1) * (24 * 60 * 60)));

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
         echo '</div>';

         echo '<div class="block cms_blue">';
         echo '<div class="status clear_only">Date:&nbsp;<br/>';
         $data = array('name' => 'date','value' => $date);
         echo form_input($data);
         echo '</div>';
         echo '</div>';

         echo '<div class="block cms_blue">';
         echo '<div class="status clear_only">End date :<small>(if more than one day</small>&nbsp;<br/>';
         $data = array('name' => 'repeat', 'value' => $repeat);
         echo form_input($data);
         echo '</div>';
         echo '</div>';
?>
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


