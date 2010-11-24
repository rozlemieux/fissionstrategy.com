<?php

require_once 'site.php';

class Projects extends Site {

        function __construct()    {
                parent::Controller();
        
        }

        function index() {
                $this->page();
        }

        // this returns all the case studies on a projects page - not really
        //    any difference between projects and case studies other than case_studies are tagged
        // 
        function page($page = 0) {
                // get case studies
                $this->load->module_model('CMS', 'case_study_model');
                $this->data = array();
                $this->data['case_studies'] = $this->case_study_model->get('', 15, $page * 15);
                $this->data['pages'] = floor($this->case_study_model->get_num() / 15) + 1;
                $this->data['page'] = $page;

                $this->data['page_title'] = "Projects - Fission Strategy";
                $this->data['main_content'] = 'projects';
                $this->load->view('includes/template', $this->data);		
        }

}