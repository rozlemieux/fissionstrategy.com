<div class="content">
  <div id="contact_form">

    <h1>Contact Us</h1>
    <?php 
      $data = array(
          'class'       => 'focusable',
          'maxlength'   => '100',
          'size'        => '50',
      );

     echo form_open('/contact/submit');
       echo '<label for="name">Name:</label>';
       $data['name'] = 'name';
       $data['id'] = 'name';
       echo form_input($data);
       echo '<label for="email">Email:</label>';
       $data['name'] = 'email';
       $data['id'] = 'email';
       echo form_input($data);
       $data = array('name' => 'message', 'cols' => 30, 'rows' => 15, 'id' => 'message');
       echo '<label for="message">Message:</label>';
       echo form_textarea($data, '', 'id="message"');
       echo form_submit('submit', 'Send Message', 'id="submit"');
     echo form_close();
    ?>

  </div>
</div>

<script>
jQuery(document).ready(function() { 

    $('#submit').click(function() {

	if ($('#name').val() == '') {
	  alert("please enter a name");
	  return false ;
	}

	if ($('#email').val() == '') {
          alert("Please enter a valid Email Address.");
	  return false;
	}

	if ($('#message').val() == '') {
	  alert("please enter a message");
	  return false ;
	}

        return true;
    });

});

