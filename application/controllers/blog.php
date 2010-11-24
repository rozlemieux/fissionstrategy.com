<?php

require_once 'site.php';

class blog extends Site {

        function __construct()    {
                parent::Controller();
        }

        function index() {
                $this->page();
        }

        //
        // Blog index page - lists 15 per page
        // 
        function page($page = 0) {
                $this->_get_sidebar_data();

                $this->load->module_model('CMS', 'links_model');
                $this->data['blogroll'] = $this->links_model->get();

                $this->load->module_model('CMS', 'blog_model'); 
                $this->data['blogposts'] = $this->blog_model->get($blog_name, 5, $page * 5);
                $this->data['pages'] = floor($this->blog_model->get_num_blogs() / 5) + 1;
                $this->data['page'] = $page;
                $this->data['page_title'] = "Fission Strategy: Blog";
                $this->data['main_content'] = 'blog';
                $this->load->view('includes/template', $this->data);		
        }

        // 
        // Blog (single) post - get the blogroll for the sidebar and get the blog contents
        //
        function post($blog_name = '') {
                $this->_get_sidebar_data();

                $this->load->module_model('CMS', 'links_model');
                $this->data['blogroll'] = $this->links_model->get();

                $this->load->module_model('CMS', 'blog_model'); 
                $blog = $this->blog_model->get($blog_name, 1);
                $this->data['blogposts'] = $blog;

                // this is shared on facebook, suggest an image
                if ($blog->thumb)                 
                        $this->data['facebook_image'] =  $this->config->item('base_url') . "uploads/images/Blog/" . $blog->thumb;

                $this->data['page_title'] = "Fission Strategy: Blog";
                $this->data['main_content'] = 'blog_single';
                $this->load->view('includes/template_sidebar', $this->data);		
        }
}