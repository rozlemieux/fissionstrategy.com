<h1>Create an Account!</h1>
<div id="create_cms_user">

<?php
echo form_open('login/create_user');

$data = array(
    'name'        => 'first_name',
    'value'       => 'First Name',
    'class'       => 'focusable',
    'maxlength'   => '100',
    'size'        => '50',
);

echo '<div class="create_user">';
echo '<legend>Personal Information</legend>';

echo form_input($data);
$data['name'] = 'last_name';
$data['value'] = 'Last Name';
echo form_input($data);
$data['name'] = 'email_address';
$data['value'] = 'Email';
echo form_input($data);

echo '</div>';
echo '<div class="create_user">';
echo '<legend>Login Info</legend>';

$data['name'] = 'username';
$data['value'] = 'Username';
echo form_input($data);
$data['name'] = 'password';
$data['value'] = 'Password';
echo form_input($data);
$data['name'] = 'password2';
$data['value'] = 'Confirm Password';
echo form_input($data);

echo '</div>';

echo form_submit('submit', 'Create Acccount');
echo form_close();
echo validation_errors('<p class="error">'); 

echo "</div>";
