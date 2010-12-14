<?php

require_once 'site.php';

class blog extends Site {

        function index() {
                $this->page();
        }

        // 
        // use remap to control the url to be backward compatible with the wordpress site
        //  this remaps /blog/post/blog-name to /blog/blog-name
        //
        function _remap($method) {
                if ($method == 'index') {
                        $this->$method();
                }
                else  if ($method == 'page') {
                        $this->page($this->uri->segment(3));
                }
                else  if ($method == 'preview') {
                        $this->preview($this->uri->segment(3));
                }
                else  
                        $this->post($method);
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

        // 
        // Preview Blog (single) post - get the blogroll for the sidebar and get the blog contents
        //
        function preview($blog_name = '') {
                $this->_get_sidebar_data();

                $this->load->module_model('CMS', 'links_model');
                $this->data['blogroll'] = $this->links_model->get();

                $this->load->module_model('CMS', 'blog_model'); 
                $blog = $this->blog_model->get($blog_name, 1, 0, 'DESC', false, true);
                $this->data['blogposts'] = $blog;

                // this is shared on facebook, suggest an image
                if ($blog->thumb)                 
                        $this->data['facebook_image'] =  $this->config->item('base_url') . "uploads/images/Blog/" . $blog->thumb;

                $this->data['page_title'] = "Fission Strategy: Blog";
                $this->data['main_content'] = 'blog_single';
                $this->load->view('includes/template_sidebar', $this->data);		
        }
}