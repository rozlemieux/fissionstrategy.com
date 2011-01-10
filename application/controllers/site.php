<?php

class Site extends Controller {
        var $data = array();

        function __construct()
        {
                parent::Controller();

                // set up meta description and facebook image suggestion if this page is shared on facebook
                $this->data['SEO_description'] = 'Fission Strategy helps social causes harness social media for social good. We specialize in delivering social media and mobile-driven strategies for nonprofits and foundations. Our services include: mobile (SMS) action network launch & management, Facebook, Twitter, and mobile application development; online-to-offline grassroots event planning; soup-to-nuts online campaigns; social media mapping and outreach strategy; website design and development; plus, customized training for your staff, grantees or network. ';
                $this->data['facebook_image'] = $this->config->item('site_url') . '/img/fbicon.jpg';
        }


        // the homepage
        //
        function index() {
                $this->_get_sidebar_data();

                $this->data['page_title'] = "Fission Strategy";
                $this->data['main_content'] = 'homepage';
                $this->load->view('includes/template', $this->data);		
        }

        function get_quote() {
            $offset = $_POST['quote_offset'];
            $this->load->module_model('CMS', 'case_study_model');
            $case_study = $this->case_study_model->get_quote($offset);
            $url = $this->config->item('base_url') . 'projects/preview/' . $case_study->id;
            $quote = $this->_truncate(strip_tags($case_study->quote), 100, $break=" ", $pad="...", $url);
            echo $quote . '<div class="author">' . $case_study->author . '</div>';
            echo '<br/><a href="' . $this->config->item('base_url') . 'projects/preview/';
            echo $case_study->id . '" class="more">meet more of our clients</a>';
            return;
        }

        function test_homepage() {
            $this->_test_get_sidebar_data();

            $this->load->module_model('CMS', 'Events_model');
            $events = $this->Events_model->get(0, 4, date('Y'), date('m'), date('d'));
            $this->data['events'] = $events;

            $this->load->module_model('CMS', 'case_study_model');
            $case_study = $this->case_study_model->get_quote(0);
            $url = $this->config->item('base_url') . 'projects/preview/' . $case_study->id;
            $quote = $this->_truncate(strip_tags($case_study->quote), 100, $break=" ", $pad="...", $url);
            $this->data['quote'] = $quote;
            $this->data['author'] = $case_study->author;
            $this->data['quote_order'] = $case_study->order;

            $this->data['page_title'] = "Fission Strategy";
            $this->data['main_content'] = 'new_homepage';
            $this->load->view('new_homepage', $this->data);		
        }

        // get the data for the sidebar
        // this data is used in either the sidebar of every page, or on the homepage directly
        //
        function _get_sidebar_data() {

                // get case studies
                $this->load->module_model('CMS', 'case_study_model');
                $this->data = array();
                $this->data['case_studies'] = $this->case_study_model->get();

                // get the latest blog
                $this->load->module_model('CMS', 'blog_model'); 
                $latest_blog = $this->blog_model->get('', 1);
                $this->data['latest_blog'] = reset($latest_blog);

                // get the quote
                $this->load->module_model('CMS', 'page_model'); 
                $info = $this->page_model->get_page('homepage');
                $this->data['author'] = $info->title;
                $this->data['quote'] = $info->content;
        }

        function _test_get_sidebar_data() {

                // get case studies
                $this->load->module_model('CMS', 'case_study_model');
                $this->data = array();
                $this->data['case_studies'] = $this->case_study_model->get();

                // get the latest blog
                $this->load->module_model('CMS', 'blog_model'); 
                $latest_blog = $this->blog_model->get('', 1);
                $this->data['latest_blog'] = reset($latest_blog);

                // replaced by rotating quote block
                if (0) {
                    // get the quote
                    $this->load->module_model('CMS', 'page_model'); 
                    $info = $this->page_model->get_page('homepage');
                    $this->data['author'] = $info->title;
                    $this->data['quote'] = $info->content;
                }
        }

        // common function to truncate strings
        //
        function _truncate($string, $limit, $break=".", $pad="...", $url) {

                if(strlen($string) <= $limit) return $string;

                if (false !== ($breakpoint = strpos($string, $break, $limit))) {
                        if($breakpoint < strlen($string) - 1) {
                                $string = substr($string, 0, $breakpoint) . '&nbsp<a href="' . $url . '">' . $pad . '</a>';
                        }
                }
    
                return $string;
        }
}
