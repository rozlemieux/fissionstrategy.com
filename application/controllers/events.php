<?php

require_once 'site.php';

class Events extends Site {
	
    function __construct()    {
        parent::Controller();

        $this->_get_sidebar_data();
        $this->load->module_model('CMS', 'Events_model');
        $this->Events_model->set_config('events/calendar');
    }

    // display a list of all events
    //
    function index() {

        $events = $this->Events_model->get();
        $this->data['events'] = $events;
        $this->data['page_title'] = "Fission Strategy: Events";
        $this->data['main_content'] = 'events';
        $this->load->view('includes/template_sidebar', $this->data);		
    }

    // display a single event
    //
    function event($id) {
        $events = $this->Events_model->get($id);
        $this->data['events'] = $events;
        $event = reset($events);
        if ($event) {
            $year = date("Y", strtotime($event->date));
            $month = date("m", strtotime($event->date));
        }
        else {
            $year = date("Y", date());
            $month = date("m", date());
        }

        $this->data['calendar'] = $this->_get_calendar($year, $month);
        $this->data['page_title'] = "Fission Strategy: Events";
        $this->data['main_content'] = 'events';
        $this->load->view('includes/template_sidebar', $this->data);		
    }

    // Displays a calendar view
    //
    function calendar($year = null, $month = null) {

        // get all events for this month
        if ($month && $year)
            $this->data['events'] = $this->Events_model->get($id, 0, $year, $month);

        $this->data['calendar'] = $this->_get_calendar($year, $month);
        $this->data['page_title'] = "Fission Strategy: Events";
        $this->data['main_content'] = 'events';
        $this->load->view('includes/template_sidebar', $this->data);		
    }

    function _get_calendar($year = null, $month = null, $size = 'small') {
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
		
        return $this->Events_model->generate_small($year, $month);
    }
}
