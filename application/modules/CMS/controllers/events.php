<?php

require_once 'cms.php';

class Events extends CMS {
	
    function __construct()    {
        parent::Controller();

        $this->load->model('Events_model');
        $this->Events_model->set_config('CMS/events/calendar', true);
        $this->data['menu_highlight'] = "Events";
    }

    // display a list of all events
    //
    function index() {
        $this->calendar();
        return;

        $events = $this->Events_model->get();
        $this->data['events'] = $events;
        $this->data['page_title'] = "Events";
        $this->data['main_content'] = 'events';
        $this->load->view('includes/template', $this->data);		
    }

    // display a single event
    //
    function event($id) {
        $events = $this->Events_model->get($id);

        $this->_build_calendar();
        $this->load->helper('ckeditor');
        $this->data['ckeditor'] = array('id' => 'content', 
                            'path'	=> '/public/js/ckeditor', 
                            'config' => array(
                                'toolbar' 	=> 	"CMS_Full", 
                                'width' 	=> 	"662px",
                                'height' 	=> 	'600px'
                            ));

        $this->data['events'] = $events;
        $this->data['page_title'] = "Events";
        $this->data['main_content'] = 'events';
        $this->load->view('includes/template', $this->data);		
    }

    // Displays a calendar view
    //
    function calendar($year = null, $month = null) {
        if (!$year) {
            $year = date('Y');
        }
        if (!$month) {
            $month = date('m');
        }

        if ($day = $this->input->post('day')) {
            $this->Events_model->add_calendar_data(
                "$year-$month-$day",
                $this->input->post('data')
            );
        }
		
        $this->data['calendar'] = $this->Events_model->generate($year, $month);
        $this->data['page_title'] = "Events";
        $this->data['main_content'] = 'events';
        $this->load->view('includes/template', $this->data);		
    }
}
