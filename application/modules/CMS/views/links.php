<?php
echo '<div class="breadcrumbs">' . $page_title . '</div>';

if ($id)
    echo form_open_multipart("/CMS/links/edit/$id");
else 
    echo form_open_multipart('/CMS/links/new_links');

$data = array('name' => 'submit','value' => 'Save changes','class' => 'submit');
echo form_submit($data);

echo "<div id='admin_title'>";
echo '<h2>Description: </h2>';
echo "</div>";

echo '<div id="admin_edit">';
?>

<div class="sidebar">

  <div class="block cms_blue">

    <div class="status clear_only">URL:
       <div class="clear_only"> <?php echo form_input('url', $link->url); ?></div>
    </div>

    <div class="status clear_only">Title:
       <div class="clear_only"> <?php echo form_input('name', $link->name); ?></div>
    </div>

    <div class="status clear_only">Open link in:
      <?php
      $options = array(
                  '_blank'  => 'New browser window',
                  '_top'   => 'Current browser tab or window'
                );
      echo form_dropdown('target', $options, $link->target);
      ?>
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

echo form_textarea('content', $link->description);
echo display_ckeditor($ckeditor); 

$data = array('name' => 'submit','value' => 'Save changes','class' => 'submit');
echo form_submit($data);
echo form_close();
?>

</div>