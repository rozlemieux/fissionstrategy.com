<?php

require_once 'site.php';

class Events extends Site {
	
        function __construct()    {
                parent::Controller();
        }

        // for events model
        // not ready yet
        //
        function display($year = null, $month = null) {
                $this->_get_sidebar_data();
		
                if (!$year) {
                        $year = date('Y');
                }
                if (!$month) {
                        $month = date('m');
                }
		
                $this->load->model('Events_model');
		
                if ($day = $this->input->post('day')) {
                        $this->Events_model->add_calendar_data(
                                "$year-$month-$day",
                                $this->input->post('data')
                        );
                }
		
                $this->data['calendar'] = $this->Events_model->generate($year, $month);
		
                $this->data['page_title'] = "Fission Strategy: Events";
                $this->data['main_content'] = 'events';
                $this->load->view('includes/template_sidebar', $this->data);		
		
        }
	
}
