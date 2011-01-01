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

        function index() {
            redirect("/CMS/blog");
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
                $name = 'exports/' . time() . '.xls';
                $filename = $dirname . $name;

                // set up header as tab delimited
                $contents = str_replace(' ', "", $contents);
                $contents = str_replace(',', "\t", $contents);

                foreach ($record_items as $item) {
                        $line = '';
                        foreach ($item as $value) {
                                if ((!isset($value)) OR ($value == "")) {
                                        $value = "\t";
                                } else {
                                        $value = str_replace('"', '""', $value);
                                        $value = '"' . $value . '"' . "\t";
                                }
                                $line .= $value;
                        }
                        $contents .= trim($line)."\n";
                }
                
                $contents = str_replace("\r","",$contents);

                file_put_contents($filename, $contents);
                //          header("Content-type: application/x-msdownload");
                //          header("Content-Disposition: attachment; filename=$filename.xls");

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

        // make the id cell with edit, ... actions
        function _make_action_field($field, $link) {
            $r = $field . '<br />';
            $r .= '<a title="Open full editor" href="' . $link . '">';
            $r .= '<img src="/img/comment_edit.png" /></a>';
            return $r;
        }

        // make this an editable cell in the grid
        function _make_editable_field($field) {
            if ($this->input->post('export')) 
                return $field;
            return '<div class="editable_cell">' . $field . '</div>';
        }

        // make this an editable cell with a select
        function _make_editable_select($field) {
            if ($this->input->post('export')) 
                return $field;

            $extra = '<select name="status">
                         <option value="draft">Draft</option>
                         <option selected="selected" value="publish">Publish</option>
                         <option value="private">Private</option>
                         <option value="trash">Trash</option>
                       </select>';
            
            $r = '<div class="editable_cell">' . $field . '</div>';
            $r .= '<div style="display:none" class="editable_extra">' . $extra . '</div>';
            return $r;
        }
}

 
?>