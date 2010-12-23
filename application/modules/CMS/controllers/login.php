<?php

class Login extends Controller {

    var $data = array();
	
    function __construct() {
        parent::Controller();

        //        $this->output->enable_profiler(TRUE);
    }

    function index() {
        $this->data['page_title'] = $this->config->item('site_name') . ": Login";
        $this->data['main_content'] = 'login_form';
        $this->load->view('includes/template', $this->data);		
    }
	
    function redirect($redirect) {
        $this->data['redirect'] = $redirect;
        $this->index();
    }

    function validate_credentials() {		
        $this->load->model('membership_model');
        $query = $this->membership_model->validate();

        if($query > 0) { // if the user's credentials validated...
            $this->data = array(
                'username' => $this->input->post('username'),
                'id' => $query,
                'is_logged_in' => true
            );
            $this->session->set_userdata($this->data);
            if ($this->input->post('redirect')) 
                redirect($this->input->post('redirect'));
            else 
                redirect('CMS');
        }
        else {  // incorrect username or password
            $this->data['error'] = "Incorrect username or password";
            $this->index();
        }
    }	
	
    function signup() {
        $this->load->model('template_model');
        $this->data['templates'] = $this->template_model->list_templates();
        $this->data['main_content'] = '/CMS/signup_form';
        $this->load->view('includes/template', $this->data);
    }
	
    function logout() {
        $this->session->sess_destroy();
        redirect('/');
    }
  }