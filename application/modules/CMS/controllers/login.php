<?php

class Login extends Controller {
	
    function __construct() {
        parent::Controller();

        //        $this->output->enable_profiler(TRUE);
    }

    function index() {
        $data['page_title'] = $this->config->item('site_name') . ": Login";
        $data['main_content'] = 'login_form';
        $this->load->view('includes/template', $data);		
    }
	
    function validate_credentials() {		
        $this->load->model('membership_model');
        $query = $this->membership_model->validate();

        if($query > 0) { // if the user's credentials validated...
            $data = array(
                'username' => $this->input->post('username'),
                'id' => $query,
                'is_logged_in' => true
            );
            $this->session->set_userdata($data);
            redirect('CMS');
        }
        else {  // incorrect username or password
            $this->index();
        }
    }	
	
    function signup() {
        $this->load->model('template_model');
        $this->data['templates'] = $this->template_model->list_templates();
        $data['main_content'] = '/CMS/signup_form';
        $this->load->view('includes/template', $data);
    }
	
    function logout() {
        $this->session->sess_destroy();
        redirect('/');
    }
  }