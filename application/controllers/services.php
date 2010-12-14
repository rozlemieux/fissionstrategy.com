<?php

require_once 'site.php';

class Services extends Site {

        // 
        // get the contents of the services pag form the page_model
        // 
        function index() {
                $this->_get_sidebar_data();

                $this->load->module_model('CMS', 'page_model');
                $this->data['page'] = $this->page_model->get('services');

                $this->data['page_title'] = "Fission Strategy: Services";
                $this->data['main_content'] = 'services';
                $this->load->view('includes/template_sidebar', $this->data);		
        }


}