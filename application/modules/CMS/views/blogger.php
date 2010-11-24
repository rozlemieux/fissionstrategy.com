<?php 

echo '<div class="breadcrumbs">' . $page_title . '</div>';

$attributes = array('id' => 'my_form', 'name' => 'my_form');

if ($id)
    echo form_open_multipart("/CMS/blogger/edit/$id", $attributes);
else 
    echo form_open_multipart('/CMS/blogger/new_blogger', $attributes);

$data = array('name' => 'submit','value' => 'Save changes','class' => 'submit');
echo form_submit($data);

echo '<div id="admin_edit">';
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
      echo form_dropdown('status', $options, $blogger->status);
      ?>
    </div>

    <div class="status clear_only" style='float: left; width:100%'>Category:</div>
      <?php

         $options = array();
         foreach ($categories as $cat) 
            $options[$cat->id] = $cat->name;

         $selected = array();
         foreach ($blogger->tags as $tag) 
             $selected[] = $tag['id'];

         $js = ' size=8 ';
         echo form_multiselect('category[]', $options, $selected, $js);
      ?>
      <div class="status clear_only">New Category:
         <div class="clear_only"> <?php echo form_input('new_category', ''); ?></div>
      </div>

  </div>

</div>
<div id="blogger_info">

  <?php

echo '<div class="blogger_form">Blog Name:</div>';
echo form_input('Blog_Name', $blogger->Blog_Name);

echo '<div class="blogger_form">Source:</div>';
echo form_input('Source', $blogger->Source);

echo '<div class="gray blogger_form">Email:</div>';
echo form_input('Email', $blogger->Email);

echo '<div class="blogger_form">Email_2:</div>';
echo form_input('Email_2', $blogger->Email_2);

echo '<div class="blogger_form">Blog URL:</div>';
echo form_input('URL', $blogger->URL);

echo '<div class="gray blogger_form">First Name:</div>';
echo form_input('First_Name', $blogger->First_Name);

echo '<div class="blogger_form">Last Name:</div>';
echo form_input('Last_Name', $blogger->Last_Name);

echo '<div class="gray blogger_form">City:</div>';
echo form_input('City', $blogger->City);

echo '<div class="blogger_form">State:</div>';
echo form_input('State', $blogger->State);

echo '<div class="gray blogger_form">Phone:</div>';
echo form_input('Phone', $blogger->Phone);

echo '<div class="blogger_form">Skype:</div>';
echo form_input('Skype', $blogger->Skype);

echo '<div class="gray blogger_form">Fax:</div>';
echo form_input('Fax', $blogger->Fax);

echo '<div class="blogger_form">Web Form URL:</div>';
echo form_input('Web_Form_URL', $blogger->Web_Form_URL);

echo '<div class="gray blogger_form">Authority:</div>';
echo form_input('Authority', $blogger->Authority);

echo '<div class="blogger_form">Twitter Blogger:</div>';
echo form_input('Twitter_Blogger', $blogger->Twitter_Blogger);

echo '<div class="gray blogger_form">Twitter Blogger Followers:</div>';
echo form_input('Twitter_Blogger_Followers', $blogger->Twitter_Blogger_Followers);

echo '<div class="blogger_form">Twitter Outlet:</div>';
echo form_input('Twitter_Outlet', $blogger->Twitter_Outlet);

echo '<div class="gray blogger_form">Twitter Outlet Followers:</div>';
echo form_input('Twitter_Outlet_Followers', $blogger->Twitter_Outlet_Followers);

echo '<div class="blogger_form">Facebook:</div>';
echo form_input('Facebook', $blogger->Facebook);

echo '<div class="gray blogger_form">Fans:</div>';
echo form_input('Fans', $blogger->Fans);

echo '<div class="blogger_form">Estimated Readership:</div>';
echo form_input('Estimated_Readership', $blogger->Estimated_Readership);

echo '<div class="gray blogger_form">Media Outlet:</div>';
echo form_input('Media_Outlet', $blogger->Media_Outlet);

echo '<div class="blogger_form">Additional Contacts:</div>';
echo form_input('Additional_Contacts', $blogger->Additional_Contacts);


  $data = array(
      'name'  => 'Notes',
      'class' => 'quote_textarea',
      'col'   =>  80,
      'rows'  =>  5,
      'value' => $blogger->Notes,
  );

  ?>
  <div class="blogger_form">Notes:</div>
      <div class="blogger_form"> <?php echo form_textarea($data); ?></div>

<?php
  $data = array(
      'name'  => 'import_notes',
      'class' => 'quote_textarea',
      'col'   =>  80,
      'rows'  =>  5,
      'value' => $blogger->import_notes,
  );

  ?>
  <div class="blogger_form">Import Notes:</div>
      <div class="blogger_form"> <?php echo form_textarea($data); ?></div>

</div>
<?php
$data = array('name' => 'submit','value' => 'Save changes','class' => 'submit');
echo form_submit($data);
echo form_close();

?>

