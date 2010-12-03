<?php

require_once 'site.php';

class Contact extends Site {

        function __construct()    {
                parent::Controller();

                $this->_get_sidebar_data();
        }

        // show the contact us form
        //
        function index() {
                $this->_get_sidebar_data();

                $this->data['main_content'] = 'contact_form';
                $this->load->view('includes/template_sidebar', $this->data);		
        }

        // they submitted the form, send the email
        //
        function submit() {
                $this->load->library('email');
                $this->email->set_newline("\r\n");
  
                $this->email->from($_POST['email']);

                $sendto = 'cindy.mottershead@gmail.com, info@fissionstrategy.com';

                $this->email->to($sendto); 
                $this->email->subject('Fission Strategy: Received a Contact Us');

                $msg = "Received a Contact Us request from: \r\n\r\n" . $_POST['name'] . "\r\n\r\n" . $_POST['email'] ;
                $msg .= "\r\n\r\n" . $_POST['message'];

                $this->email->message($msg);	

                $this->email->send();

                $this->data['main_content'] = 'contact_submitted';
                $this->load->view('includes/template_sidebar', $this->data);		
        }
}
