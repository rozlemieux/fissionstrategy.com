<?php

require_once 'site.php';

class Team extends Site {

        // team page
        // 
        function index() {
                $this->_get_sidebar_data();

                $this->load->module_model('CMS', 'team_model');
                $this->data['team_members'] = $this->team_model->get();
                $this->data['page_title'] = "Fission Strategy: Team";
                $this->data['main_content'] = 'team';
                $this->load->view('includes/template_sidebar', $this->data);		
        }

        // 
        // added for direct access to memmber info for bar code scanner
        //    /team/member/Cheryl Contee
        //
        function member($name = '') {
                $this->_get_sidebar_data();

                $this->load->module_model('CMS', 'team_model');
                
                if (stripos($name, "lemieux"))
                    $name = 'Rosalyn "Roz" Lemieux';
                $this->data['team_members'] = $this->team_model->get($name);
                $this->data['page_title'] = "Fission Strategy: Team";
                $this->data['selected_name'] = $name;
                $this->data['main_content'] = 'team';
                $this->load->view('includes/template_sidebar', $this->data);		
        }

}