<?php

  //
  // provides security/login validation and common methods for all CMS controllers
  //

class CMS extends Controller {

        function __construct() {
                parent::Controller();
                //                $this->output->enable_profiler(TRUE);
                $this->is_logged_in();
        }

        function is_logged_in() {
                $is_logged_in = $this->session->userdata('is_logged_in');
                if(!isset($is_logged_in) || $is_logged_in != true) {
                        redirect('/CMS/login');
                }		
        }

        // common function to truncate strings
        //
        function truncate($string, $limit, $break=".", $pad="...") {

                if(strlen($string) <= $limit) return $string;

                if (false !== ($breakpoint = strpos($string, $break, $limit))) {
                        if($breakpoint < strlen($string) - 1) {
                                $string = substr($string, 0, $breakpoint) . $pad;
                        }
                }
    
                return $string;
        }

        // common function to respond to the export button on all grids
        //
        function export($contents, $record_items) {

                $dirname = $this->config->item('base_dir');
                $name = 'exports/' . time() . '.csv';
                $filename = $dirname . $name;

                foreach ($record_items as $item) {
                        foreach ($item as $value) {
                                if ($value)
                                    $contents .= str_replace(',', ";", $value);
                                $contents .= ',';
                        }
                        $contents .= "\n";
                }
                file_put_contents($filename, $contents);

                $this->output->set_output($this->config->item('base_url') . $name);
        }

        // create the calendar for editing the date in the sidebar
        // 
        function _build_calendar() {
                // build the calendar
                $this->load->library('calendar');
                $this->load->plugin('js_calendar');

                $prefs = array (
                        'show_next_prev' => TRUE,
                        'next_prev_url' => base_url() . '/CMS/index/'
                );

                $this->calendar->initialize($prefs);
                $this->data['my_calendar'] = $this->calendar->generate('2010', '08');

        }

}

 
?>