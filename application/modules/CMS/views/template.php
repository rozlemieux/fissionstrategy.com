<div class="breadcrumbs"><?php echo $page_title ?></div>

<div id="admin_edit">

<?php
$hidden = array('id' => $name);
$attributes = array('id' => 'my_form', 'name' => 'my_form');

echo form_open_multipart("/CMS/templates/edit/$key/$name", $attributes, $hidden);

$data = array('name' => 'submit','value' => 'Save changes','class' => 'submit');
echo form_submit($data);
echo '<div style="clear:both"></div>';

echo "<div id='textarea_edit'></div>";
?>

  <div class="sidebar">
    <div class="block cms_blue">
      <div class="status clear">Revisions:
      </div>
    </div>
  </div>

  <div style="float:left">
    <?php
    echo form_textarea('template', $template);
    echo "</div>";
    $data = array('name' => 'submit','value' => 'Save changes','class' => 'submit');
    echo form_submit($data);
    echo form_close();
    ?>
  </div>
</div>
