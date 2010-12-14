<?php

require_once 'site.php';

class Clients extends Site {

        // 
        // read the page contents from page_model
        // 
        function index() {
                $this->_get_sidebar_data();

                $this->load->module_model('CMS', 'page_model');
                $this->data['page'] = $this->page_model->get('clients');

                $this->data['page_title'] = "Fission Strategy: Clients";
                $this->data['main_content'] = 'clients';
                $this->load->view('includes/template_sidebar', $this->data);		
        }
}