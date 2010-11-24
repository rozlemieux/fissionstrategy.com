<?php

class Team_model extends Model {
        var $id = 0;
        var $name = '';
        var $team_title = '';
        var $content = '';
        var $email ='';
        var $photo = ''; 
        var $linkedin = '';
        var $twitter = '';
        var $skype = '';
        var $status = 'publish';

        const table_name = 'fs_team';

        function __construct($id) {
                parent::Model();
        
                if ($id)
                        $this->get_from_id($id);

                $this->id = $id;
        }

        // get the list of team members or a specific menber by name
        //
        function get($name = '', $limit = 99) {

                $team_members = array();
                $this->db->select('*');
                $this->db->where('status', 'publish');
                $this->db->from(self::table_name);
                $this->db->order_by('id');
                if ($name)
                        $this->db->where('name', $name);
                //        $this->db->order_by('date', 'DESC');
                $this->db->limit($limit);
                $query = $this->db->get();
                foreach ($query->result() as $c) {
                        $team_member= new team_model();
                        $team_member->_load_from_query($c);
                        $team_members[$c->id] = $team_member;
                }
                return $team_members;
        }
	
        // save this team member object to the database
        //
        function save($changes) {
                $this->_save_changes($changes);

                $this->db->where('id', $this->id);
                $this->db->update(self::table_name, $this);
        }

        // create a new database team member
        // 
        function create($changes) {
                $this->_save_changes($changes);

                $this->db->insert(self::table_name, $this);
                $id = $this->db->insert_id();

                return $id;
        }

        // delete this team member from the database
        // 
        function delete() {
                $this->db->delete(self::table_name, array('id' => $this->id));
        }

        // load this team member object from the database
        //
        function get_from_id($id) {  
                $query = $this->db->get_where(self::table_name, array('id' => $id));
                foreach ($query->result() as $c) 
                        $this->_load_from_query($c);

                return $this;
        }

        // get total number of team members
        // 
        function get_num_rows($table_name) {
                $this->db->select('*')->from($table_name);
                $query = $this->db->get();
                return ($this->db->count_all_results($table_name));
        }

        // save post data to the database
        //
        function _save_changes($changes) {
        
                $this->load->helper('date'); 
                if (is_array($changes)) {
                        $this->name = $changes['name'];
                        $this->content = $changes['content'];
                        if ($changes->date)
                                $this->date = $changes->date;
                        else
                                $this->date = mdate("%Y-%m-%d %h:%i:%a", now());
                        $this->team_title = $changes['team_title'];
                        $this->email = $changes['email'];
                        $this->linkedin = $changes['linkedin'];
                        $this->twitter = $changes['twitter'];
                        $this->skype = $changes['skype'];

                        if ($changes['photo'])
                                $this->photo = $changes['photo'];

                        $this->load->helper('date');
                        $this->modified = mdate("%Y-%m-%d %h:%i:%a", now());
                }
        }

        // load this object from a database query result
        //
        function _load_from_query($c) {
                $this->id = $c->id;
                $this->name = $c->name;
                $this->team_title = $c->team_title;
                $this->content = $c->content;
                $this->email = $c->email;
                $this->photo = $c->photo;
                $this->linkedin = $c->linkedin;
                $this->twitter = $c->twitter;
                $this->skype = $c->skype;
        }

        // make sure this url doesn't exist, if it does, add 1 to count of similiar urls and append as count
        //
        function _check_for_uniq_url($title) {
                if ($title == '') $title = 'untitled';
                $team_url = preg_replace('/[^0-9a-z ]+/i', '', $title); 
                $team_url = str_replace(' ', '-', strtolower($team_url));
        
                $this->db->from(self::table_name);
                $this->db->like('name', $team_url, 'after');
                $count = $this->db->count_all_results();
                if ($count > 0)
                        $team_url .= '_' . $count;
        
                return $team_url;
        }
}

