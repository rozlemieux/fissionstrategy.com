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
echo form_label('Author:', 'title');
echo "<div id='admin_title' style='clear:both'>";
echo form_input('title', $page->title);
echo "</div>";

echo '<div class="sidebar">';
  echo '<div class="block cms_blue">';
   echo '<div class="status clear">';
  echo '</div>';
echo '</div>';
echo '</div>';


echo form_label('Quote:', 'content');
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