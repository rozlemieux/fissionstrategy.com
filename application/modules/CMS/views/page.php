<?php
echo '<div class="breadcrumbs">' . $page_title . '</div>';

$attributes = array('id' => 'my_form', 'name' => 'my_form');

if ($id)
    echo form_open_multipart("/CMS/page/edit/$id", $attributes);
else 
    echo form_open_multipart('/CMS/page/new_page', $attributes);

$data = array('name' => 'submit','value' => 'Save changes','class' => 'submit');
echo form_submit($data);

echo '<div id="admin_edit">';
echo "<div id='admin_title'>";
echo form_label('Title:', 'title');
echo form_input('title', $page->title);
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
      echo form_dropdown('status', $options, $page->status);
    echo '</div>';

    echo '<div class="status clear">URL:&nbsp;&nbsp;';
      if ($page->menu) {
         $name = (strpos($page->menu, ':sub') > 0) ? $page->name : $page->menu;
         echo '<a href="/about/' . $name . '">/about/' . $name . '</a>';
      }
    echo '</div>';

    echo '<div class="status clear">Page:&nbsp;';
      $options = array(
          //                  'about' => 'about',
          //      'homepage' => 'homepage',
          //       'services' => 'services',
          //      'clients' => 'clients'
                  'page' => 'page'
                );
      echo form_dropdown('menu', $options, $page->menu);
    echo '</div>';
  echo '</div>';
echo '</div>';
echo '</div>';

echo '<div style="clear:left; width: 700px;">';

$data = array(
    'name'        => 'content',
    'value'       => '',
    'id'          => 'content',
);

echo form_textarea('content', $page->content);
echo display_ckeditor($ckeditor); 

echo "</div>";

$data = array('name' => 'submit','value' => 'Save changes','class' => 'submit');
echo form_submit($data);
echo form_close();


?>
</div>