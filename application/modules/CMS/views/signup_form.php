<?php
echo '<div class="breadcrumbs">' . $page_title . '</div>';

$show_errors = (isset($show_errors)) ? ' style="display:block" ' : '';

echo form_open_multipart('/CMS/users/create_user');

$title = ($user['id']) ? "Edit: " . $user['username'] : 'Create new User';
$data = array('name' => 'submit', 'value' => 'Save changes', 'class' => 'submit');
echo form_submit($data);

echo '<div id="admin_edit">';
?>

<div class="sidebar">
  <div class="block cms_blue">
    <div class="status clear">Thumbnail:&nbsp;
      <div id="blog_photo">
        <h5>Avatar:</h5>
        <img width="100" class="image" src="/uploads/images/Users/<?php echo $user['avatar'] ?>" />
        <?php form_label('Replace avatar:', 'userfile'); ?>
        <input type="file" name="userfile" />
      </div>
    </div>
  </div>
</div>

<div id="create_cms_user">

    <?php
       echo form_label('First Name:', 'first_name');
       echo form_input('first_name', $user['first_name']);
       echo form_label('Last Name:', 'last_name');
       echo form_input('last_name', $user['last_name']);
       echo form_label('Email:', 'email_address');
       echo form_input('email_address', $user['email_address']);
       ?>

    <?php
       echo form_label('Username:', 'username');
       echo form_input('username', $user['username']);
       echo form_label('Password:', 'password');
       echo form_input('password', '');
       echo form_label('Password Confirm:', 'password2');
       echo form_input('password2', '');

       echo "</div>";

       echo "<div class='validation_errors' $show_errors>";
       echo validation_errors('<p class="error">'); 
       echo '</div>';


       $data = array('name' => 'submit', 'value' => 'Save changes', 'class' => 'submit');
       echo form_submit($data);

       if ($user['id'])
           $data = array('name' => 'id', 'value' => $user['id'], 'type' => 'hidden');
       else 
           $data = array('name' => 'create_user', 'value' => '1', 'type' => 'hidden');
       echo form_input($data);
?>

</form>
</div>
