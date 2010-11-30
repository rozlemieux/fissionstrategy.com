<?php

require_once 'site.php';

class Page extends Site {

        function __construct()    {
                parent::Controller();
        }
        
        // 
        //  allows the second parameter to be the name of the page
        // get the contents of the specified page form the page_model
        //
        function _remap($name) {
                $this->_get_sidebar_data();

                $this->load->module_model('CMS', 'page_model');
                $page = $this->page_model->get($name);
                $this->data['page'] = $page;

                $this->data['page_title'] = $page->title;
                $this->data['main_content'] = 'services';
                $this->load->view('includes/template_sidebar', $this->data);		
        }
}