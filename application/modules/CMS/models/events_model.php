<?php
class Events_model extends Model {
	
    var $conf;
    var $CMS;

    function Events_model () {
        parent::Model();
		
        // set some config options (these should be moved to /config
        $this->conf = array(
            'start_day' => 'sunday',
            'show_next_prev' => true,
        );

        // the calendar library class uses templates, here is our default template
        //
        $this->conf['template'] = '
			{table_open}<table border="0" cellpadding="0" cellspacing="0" class="calendar">{/table_open}
			
			{heading_row_start}<tr>{/heading_row_start}
			
			{heading_previous_cell}<th><a href="{previous_url}">&lt;&lt;</a></th>{/heading_previous_cell}
			{heading_title_cell}<th colspan="{colspan}">{heading}</th>{/heading_title_cell}
			{heading_next_cell}<th><a href="{next_url}">&gt;&gt;</a></th>{/heading_next_cell}
			
			{heading_row_end}</tr>{/heading_row_end}
			
			{week_row_start}<tr>{/week_row_start}
			{week_day_cell}<td>{week_day}</td>{/week_day_cell}
			{week_row_end}</tr>{/week_row_end}
			
			{cal_row_start}<tr class="days">{/cal_row_start}
			{cal_cell_start}<td class="day">{/cal_cell_start}
			
			{cal_cell_content}
				<div class="day_num">{day}</div>
				<div class="cell_content">{content}</div>
			{/cal_cell_content}
			{cal_cell_content_today}
				<div class="day_num highlight">{day}</div>
				<div class="cell_content">{content}</div>
			{/cal_cell_content_today}
			
			{cal_cell_no_content}<div class="day_num">{day}</div>{/cal_cell_no_content}
			{cal_cell_no_content_today}<div class="day_num highlight">{day}</div>{/cal_cell_no_content_today}
			
			{cal_cell_blank}&nbsp;{/cal_cell_blank}
			
			{cal_cell_end}</td>{/cal_cell_end}
			{cal_row_end}</tr>{/cal_row_end}
			
			{table_close}</table>{/table_close}
		';
		
    }
	
    function set_config($url, $CMS = false) {
        $this->conf['next_prev_url'] = base_url() . $url;
        $this->CMS = $CMS;
    }

    // gets the specified event or the list of all events
    //
    function get($id = null, $limit = 0, $year = null, $month = null) {
        
        $this->db->select('id, title, date, description')->from('fs_events');
        if ($id)
            $this->db->where('id', $id);
        if ($limit)
            $this->db->limit($limit);

        if ($year && $month) {
            $this->db->like('date', "$year-$month", 'after');
            $this->db->orderby('id', 'asc');
        }
        else $this->db->orderby('id', 'desc');

        $query = $this->db->get();
        $event_data = array();
		
        foreach ($query->result() as $row) {
            $event_data[$row->id] = $row;
        }
        return $event_data;
    }

    // creates an event in the database
    //
    function add_calendar_data($date, $description) {
		
        $this->db->insert('fs_events', array(
                'date' => $date,
                'description' => $description
            ));
        return;

        // the following was to only allow one event per day
        if ($this->db->select('date')->from('fs_events')
            ->where('date', $date)->count_all_results()) {
			
            $this->db->where('date', $date)
                ->update('fs_events', array(
                        'date' => $date,
                        'description' => $description			
                    ));
			
        } else {
		
            $this->db->insert('fs_events', array(
                    'date' => $date,
                    'description' => $description
                ));
        }
    }

    // generates a calender view with the events filled in
    //
    function generate ($year, $month) {
		
        $this->load->library('calendar', $this->conf);
        $cal_data = $this->_get_calendar_data($year, $month);
		
        return $this->calendar->generate($year, $month, $cal_data);
    }
	
    // generates a small calender view with the events filled in
    //
    function generate_small ($year, $month) {
		
        $this->load->library('calendar', $this->conf);
        $cal_data = $this->_get_calendar_data($year, $month, true);
		
        return $this->calendar->generate($year, $month, $cal_data);
    }
	
    // this fetches the events for the given year/month and merges them in with the month
    // info to generate a calendar page
    function _get_calendar_data($year, $month, $small = false) {

        $query = $this->db->select('id, date, title, description')->from('fs_events')
            ->like('date', "$year-$month", 'after')->get();

        $cal_data = array();
		
        foreach ($query->result() as $row) {
            $url = ($this->CMS) ? '/CMS/events/event/' : '/events/event/';
            $url .= $row->id;

            $event = '<a href="' . $url . '">' . $row->description . '</a>';
            if ($small)
                $event = '<a href="' . $url . '" style="float:left;width:30px;height:10px;background-color: green"></a>';

            if (isset($cal_data[substr($row->date,8,2) + 0]))
                $cal_data[substr($row->date,8,2) + 0] .= $event;
            else 
                $cal_data[substr($row->date,8,2) + 0] = $event;
        }
		
        return $cal_data;
    }
}
