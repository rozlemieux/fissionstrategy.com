<?php
class Events_model extends Model {
	
    var $date = '';
    var $title = '';
    var $description = '';
    var $repeat = '';

    function Events_model () {
        parent::Model();
		
        // set some config options (these should be moved to /config
        $this->conf = array(
            'start_day' => 'sunday',
            'show_next_prev' => true,
        );

        $day_template = ($this->conf['is_CMS']) ? 
            '<div title="create new event" class="new_event"><div class="day_num">{day}</div></div>' : 
            '<div class="day_num">{day}</div>';

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
			
			{cal_cell_content}' . $day_template . '
				
				<div class="cell_content">{content}</div>
			{/cal_cell_content}
			{cal_cell_content_today}
				<div class="day_num highlight">{day}</div>
				<div class="cell_content">{content}</div>
			{/cal_cell_content_today}
			
			{cal_cell_no_content}<div title="create new event" class="new_event">
                                 <div class="day_num">{day}</div>{/cal_cell_no_content}</div>
			{cal_cell_no_content_today}<div class="day_num highlight">{day}</div>{/cal_cell_no_content_today}
			
			{cal_cell_blank}&nbsp;{/cal_cell_blank}
			
			{cal_cell_end}</td>{/cal_cell_end}
			{cal_row_end}</tr>{/cal_row_end}
			
			{table_close}</table>{/table_close}
		';
		
    }
	
    function set_config($url, $CMS = false) {
        $this->conf['next_prev_url'] = base_url() . $url;
        $this->conf['is_CMS'] = $CMS;
    }

    // gets the specified event or the list of all events
    //
    function get($id = null, $limit = 0, $year = null, $month = null, $day = null) {
        
        $this->db->select('*')->from('fs_events');
        if ($id)
            $this->db->where('id', $id);
        else 
            $this->db->where('status', 'publish');

        if ($limit)
            $this->db->limit($limit);

        // SELECT * FROM `fs_events` WHERE DATE(date) > DATE('2011-01-01') order by DATE(date) asc

        if ($year && $month) {
            // must be getting all future events from this day
            if ($day) 
                $this->db->where("DATE(date) >= DATE('$year-$month-$day')");
            else 
                $this->db->like('date', "$year-$month", 'after');
            $this->db->orderby('DATE(date)', 'asc');
        }
        else $this->db->orderby('date', 'desc');

        $query = $this->db->get();
        $event_data = array();
		
        foreach ($query->result() as $row) {
            $event_data[$row->id] = $row;
        }
        return $event_data;
    }

    function get_from_id($id) {
        
        $this->db->select('*')->from('fs_events');
        $this->db->where('id', $id);

        $query = $this->db->get();
		
        foreach ($query->result() as $row)  {
            $this->_load_from_query($row);
            break;
        }

        return $this;
    }

    // creates an event in the database
    //
    function save($changes) {
        if (is_array($changes)) {
            if (isset($changes['date'])) {
                $date = date("Y-m-d H:i:s", strtotime($changes['date']));
                $this->date = $date;
            }
            if (isset($changes['repeat'])) {
                if ($changes['repeat']) 
                    $this->repeat = ((strtotime($changes['repeat']) - strtotime($changes['date'])) / (24 * 60 * 60)) + 1;
                else $this->repeat = 0;
            }
            if (isset($changes['title'])) 
                $this->title = $changes['title'];
            if ($this->title == '') $this->title = 'no title';

            if (isset($changes['content']))
                $this->description = $changes['content'];
            if (isset($changes['status']))
                $this->status = $changes['status'];
            $this->load->helper('date');
            //            $this->updated = mdate("%Y-%m-%d %h:%i:%a", now());
        }

        if ($this->id) {
            $this->db->where('id', $this->id);
            $this->db->update('fs_events', $this);
        }
        else {
            $this->db->insert('fs_events', $this);
            $this->id = $this->db->insert_id();
        }

        return $this->id;
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

        $query = $this->db->select('*', FALSE)->from('fs_events')
            ->like('date', "$year-$month", 'after')->get();

        $cal_data = array();
		
        foreach ($query->result() as $row) {
            $url = ($this->conf['is_CMS']) ? '/CMS/events/event/' : '/events/event/';
            $url .= $row->id;

            $event = '<a href="' . $url . '">' . $row->title . '</a>';
            if ($small)
                $event = '<a href="' . $url . '" style="float:left;width:30px;height:10px;background-color: green"></a>';

            $day = substr($row->date,8,2);
            $repeat = ($row->repeat == 0) ? 1 : $row->repeat;
            while ($repeat > 0) {
                if (isset($cal_data[$day + 0]))
                    $cal_data[$day + 0] .= $event;
                else 
                    $cal_data[$day + 0] = $event;
                $day += 1;
                $repeat = $repeat - 1;
            }
        }
		
        return $cal_data;
    }

    // copy database row into this object
    //
    function _load_from_query($c) {
        $this->id = $c->id;
        $this->title = $c->title;
        $this->description = $c->description;
        $this->date = $c->date;
        $this->repeat = $c->repeat;
    }
}
