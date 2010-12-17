<?php

class Blogger_model extends Model {
        var $id = 0;
        var $Source = '';
        var $City = '';
        var $State = '';
        var $Country = '';
        var $Blog_Name = '';
        var $URL = '';
        var $Authority = '';
        var $Title = '';
        var $First_Name = '';
        var $Last_Name = '';
        var $Email = '';
        var $Email_2 = '';
        var $Web_Form_URL = '';
        var $Notes = '';
        var $Twitter_Blogger = '';
        var $Twitter_Blogger_Followers = '';
        var $Twitter_Outlet = '';
        var $Twitter_Outlet_Followers = '';
        var $Facebook = '';
        var $Fans = '';
        var $Phone = '';
        var $Fax = '';
        var $Additional_Contacts = '';
        var $Skype = '';
        var $Estimated_Readership = '';
        var $Media_Outlet = '';
        var $tags;
        var $import_notes = '';

        const table_name = 'fs_blogger';

        function __construct($id) {
                parent::Model();
        
                if ($id)
                        $this->get_from_id($id);

                $this->id = $id;
        }

        // get list of bloggers, or specific one by email address
        //
        function get($email = '', $limit = 99, $offset = 0, $category = 0) {

                $tags = $this->_get_tags();

                $blogger = array();
                $this->db->select('*, fs_blogger.id as id');
                $this->db->where('od6 is null');
                $this->db->from(self::table_name);
                if ($category > 1) {
                        $this->db->join('fs_map', 'fs_map.blogger_id = fs_blogger.id');
                        $this->db->where('fs_map.cat_id', $category);
                }

                if ($email)
                        $this->db->where('name', $email);
                $this->db->limit($limit, $offset);
                $query = $this->db->get();

                // create object from database
                foreach ($query->result() as $c) {
                        $blogger = new blogger_model();
                        $blogger->_load_from_query($c, $tags);
                        $blogger[$c->id] = $blogger;
                }
                return $blogger;
        }

        // get total rows in this table
        //
        function get_num() {
                $this->db->select('*');
                $this->db->from(self::table_name);
                $this->db->where('status', 'publish');

                return $this->db->count_all_results();
        }

        // save blogger to database
        //
        function save($changes) {
                // delete any current categories
                $this->db->delete('fs_blogger_cat_map', array('blogger_id' => $this->id)); 

                // if new category, create it first
                if (isset($changes['new_category']) && ($changes['new_category'] != '')) {
                        $category_name = str_replace(' ', '_', $changes['new_category']);
                        $this->db->insert('fs_blogger_category', array('name' => $category_name));
                        $changes['category'][] = $this->db->insert_id();
                }

                // add any categories
                $categories = $changes['category'];
                foreach ($categories as $i => $cat_id) {
                        $data = array(
                                'blogger_id' => $this->id,
                                'category_id' => $cat_id
                        );
                        $this->db->insert('fs_blogger_cat_map', $data);
                }

                // copy each value from 4changes into the variable for this object if that variable exists
                if (is_array($changes)) {
                        foreach ($changes as $key => $value) {
                                if (property_exists($this, $key)) 
                                        $this->$key = $value;
                        }
                }
                $this->db->where('id', $this->id);
                $this->db->update(self::table_name, $this);
        }

        // create a new blogger
        // 
        function create($changes) {
                if (is_array($changes)) {
                        if (isset($changes['content']))
                                $this->content = $changes['content'];

                        // create the url
                        $this->name = $this->_check_for_uniq_url($changes['title']);

                        if (isset($changes['title']))
                                $this->title = $changes['title'];
                        $this->load->helper('date');
                        $this->date = mdate("%Y-%m-%d %h:%i:%a", now());
                        $this->modified = $this->date;
                        $this->status = $changes['status'];
                }

                $this->db->insert(self::table_name, $this);
                $this->id = $this->db->insert_id();
        
                // if new category, create it first
                if (isset($changes['new_category'])) {
                        $category_name = str_replace(' ', '_', $changes['new_category']);
                        $this->db->insert('fs_blogger_category', array('category_name' => $category_name));
                        $changes['category'][] = $this->db->insert_id();
                }

                // add any categories
                $categories = $changes['category'];
                foreach ($categories as $i => $cat_id) {
                        $data = array(
                                'blogger_id' => $this->id,
                                'category_id' => $cat_id
                        );
                        $this->db->insert('fs_blogger_cat_map', $data);
                }

                return $this->id;
        }

        // load object from database
        //
        function get_from_id($id) {  
                $tags = $this->_get_tags($id);

                $query = $this->db->get_where(self::table_name, array('id' => $id));
                foreach ($query->result() as $c) 
                        $this->_load_from_query($c, $tags);

                return $this;
        }

        // get total number bloggers
        //
        function get_num_rows($table_name) {
                $this->db->select('*')->from($table_name);
                $query = $this->db->get();
                return ($this->db->count_all_results($table_name));
        }

        // delete this blogger from the database
        //
        function delete() {
                $this->db->delete(self::table_name, array('id' => $this->id));
        }

        // return all tags (categories) associated with this blogger
        //
        function _get_tags($id = '') { 

                $tags = array();
                $this->db->select('*');
                if ($id)
                        $this->db->where('blogger_id', $id);
                $this->db->from('fs_blogger_cat_map');
                $query = $this->db->get();
                foreach ($query->result() as $c) {
                        if (!isset($tags[$c->blogger_id]))
                                $tags[$c->blogger_id] = array();

                        $tags[$c->blogger_id][] = array('id' => $c->category_id, 'name' => $c->name);
                }
                return $tags;
        }
	
        // create an object from the database
        //     note: should just do loop over property exists but this way it is clear what fields are being used
        //
        function _load_from_query($c, $tags = array()) {
                $this->id = $c->id;
                $this->City = $c->City;
                $this->Source = $c->Source;
                $this->State = $c->State;
                $this->Country = $c->Country;
                $this->Blog_Name = $c->Blog_Name;
                $this->URL = $c->URL;
                $this->Authority = $c->Authority;
                $this->Title = $c->Title;
                $this->First_Name = $c->First_Name;
                $this->Last_Name = $c->Last_Name;
                $this->Email = $c->Email;
                $this->Email_2 = $c->Email_2;
                $this->Web_Form_URL = $c->Web_Form_URL;
                $this->Notes = $c->Notes;
                $this->import_notes = $c->import_notes;
                $this->Twitter_Blogger = $c->Twitter_Blogger;
                $this->Twitter_Blogger_Followers = $c->Twitter_Blogger_Followers;
                $this->Twitter_Outlet = $c->Twitter_Outlet;
                $this->Twitter_Outlet_Followers = $c->Twitter_Outlet_Followers;
                $this->Facebook = $c->Facebook;
                $this->Fans = $c->Fans;
                $this->Phone = $c->Phone;
                $this->Fax = $c->Fax;
                $this->Additional_Contacts = $c->Additional_Contacts;
                $this->Skype = $c->Skype;
                $this->Estimated_Readership = $c->Estimated_Readership;
                $this->Media_Outlet = $c->Media_Outlet;
                $this->tags = (isset($tags[$c->id])) ? $tags[$c->id] : array();
        }

        // check for uniq url, increment until unique
        //
        function _check_for_uniq_url($title) {
                if ($title == '') $title = 'untitled';
                $blogger_url = preg_replace('/[^0-9a-z ]+/i', '', $title); 
                $blogger_url = str_replace(' ', '-', strtolower($blogger_url));
        
                $this->db->from(self::table_name);
                $this->db->like('name', $blogger_url, 'after');
                $count = $this->db->count_all_results();
                if ($count > 0)
                        $blogger_url .= '_' . $count;
        
                return $blogger_url;
        }
}

