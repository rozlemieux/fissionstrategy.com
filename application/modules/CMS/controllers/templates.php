<?php

require_once 'cms.php';

class Templates extends Controller {

        function __construct() {
                parent::__construct();
        }

        // display a list of templates pages (views, stylesheets, js)
        // 
        function index() {
                $data['menu_highlight'] = "Templates";
                $this->load->model('template_model');
                $data['templates'] = $this->template_model->list_templates();
                $data['page_title'] = "Template List";
                $data['main_content'] = 'list_templates';
                $this->load->view('includes/template', $data);		
        }

        // present a wysiwyg editor, prefill with contents of file if it exists
        //
        function edit($key, $name = null) {
                $data['menu_highlight'] = "Templates";

                $this->load->model('template_model');
                if (isset($_POST['template'])) 
                        $this->template_model->save($key, $name);

                $this->config->item('global_xss_filtering', FALSE);

                $data['template'] = $this->template_model->get($key, $name);
                $data['name'] = $name;
                $data['key'] = $key;
                $data['page_title'] = "Editing Template: " . $name;
                $data['main_content'] = 'template';
                $this->load->view('includes/template', $data);		
        }
}
