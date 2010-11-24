<?php echo '<div class="breadcrumbs">' . $page_title . '</div>'; ?>

<div id="login_form">
     
  <h1>Login please</h1>

  <?php 
  echo form_open('/CMS/login/validate_credentials');

  echo form_label('Username', 'username');
  $data = array('name' => 'username', 'maxlength' => '100', 'size' => '50');
  echo form_input($data);

  $data['name'] = 'password';
  $data['value'] = '';
  echo form_label('Password', 'password');
  echo form_password($data);

  $data = array('name' => 'submit', 'value' => 'Login', 'class' => 'submit');
  echo form_submit($data);
  echo form_close();
  ?>

</div>

