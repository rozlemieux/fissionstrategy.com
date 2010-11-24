<?php

require_once 'site.php';

class Case_studies extends Site {

        function __construct()    {
                parent::Controller();
        }

        function index() {
                $this->page();
        }

        //
        // Case Study/Project index page - lists 15 per page for the given category
        // 

        function page($page = 0, $category = 0) {
                // get case studies
                $this->load->module_model('CMS', 'case_study_model');
                $this->data = array();
                $this->data['case_studies'] = $this->case_study_model->get('', 15, $page * 15, $category);
                $this->data['pages'] = floor($this->case_study_model->get_num() / 15) + 1;

                if ($category > 1) $this->data['pages'] = 0;   // by category paging needs work ;)
                $this->data['page'] = $page;

                $this->data['page_title'] = "Fission Strategy - Case Studies";
                $this->data['main_content'] = 'case_studies';
                $this->load->view('includes/template', $this->data);		
        }

        // 
        // this page is really only called when someone shares the link on facebook and the share is clicked
        //      the link is shared in the case study popup
        // 
        function study($id) {
                $this->_get_sidebar_data();

                // get case studies
                $this->load->module_model('CMS', 'case_study_model');
                $case_study = $this->case_study_model->get_from_id($id);
                $this->data['case_study'] = $case_study;

                $photo = (isset($case_study->photos)) ? reset($case_study->photos) : '';
        
                // this is shared on facebook, suggest an image and a description
                $this->data['SEO_description'] = addslashes(strip_tags($case_study->content));
                if ($photo)                 
                        $this->data['facebook_image'] =  $this->config->item('base_url') . "uploads/images/CaseStudies/" . $photo;

                $this->data['page_title'] = "Fission Strategy - Case Study: " . $this->title;
                $this->data['main_content'] = 'case_study';
                $this->load->view('includes/template_sidebar', $this->data);		
        }

}