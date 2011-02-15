<?php

require_once 'cms.php';

class Events extends CMS {
	
    const EVENTS_URL = 'CMS/events/calendar';

    function __construct()    {
        parent::Controller();

        $this->load->model('Events_model');
        $this->Events_model->set_config(self::EVENTS_URL, true);
        $this->data['menu_highlight'] = "Events";
    }

    // display a list of all events
    //
    function index() {
        $year = date('Y');
        $month = date('m');
        redirect($this->config->item('base_url') . self::EVENTS_URL . "/$year/$month");
        return;

        $events = $this->Events_model->get();
        $this->data['events'] = $events;
        $this->data['page_title'] = "Events";
        $this->data['main_content'] = 'events';
        $this->load->view('includes/template', $this->data);		
    }

    // display a single event
    //
    function event($id = null, $date = null) {
        if ($id) 
            $events = $this->Events_model->get($id);
        else {
            $event = new Events_model();
            $event->date = $date;
            $events = array($event);
        }

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
    function calendar($year = null, $month = null, $day = null) {
        if (!$year) 
            $year = date('Y');

        if (!$month)
            $month = date('m');

        if ($day) {
            $this->event(null, "$year-$month-$day 00:00:00");
            return 'success';
        }
		
        $this->data['calendar'] = $this->Events_model->generate($year, $month);
        $this->data['page_title'] = "Events";
        $this->data['main_content'] = 'events';
        $this->load->view('includes/template', $this->data);		
    }

    // edit an event to the database or create new one
    // 
    function save() {

        $id = $this->input->post('id');

        $event = new Events_model();
        if ($id) 
            $event->get_from_id($id);
 
        $id = $event->save($_POST);
        $this->event($id);
    }
}

