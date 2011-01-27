<?php

require_once 'site.php';

class Clients extends Site {

        // 
        // read the page contents from page_model
        // 
        function index() {
                $this->page();
        }

        function page($page = 0) {
                $this->data = array();

                $this->load->module_model('CMS', 'page_model');
                $this->data['page_model'] = $this->page_model->get('clients');

                $this->load->module_model('CMS', 'case_study_model');
                $page_size = 99;
                $this->data['case_studies'] = $this->case_study_model->get('', $page_size, $page * $page_size);
                $this->data['pages'] = floor($this->case_study_model->get_num() / $page_size) + 1;
                $this->data['page'] = $page;

                $this->data['page_title'] = "Fission Strategy: Clients";
                $this->data['main_content'] = 'clients';
                $this->load->view('includes/template', $this->data);		
        }

}