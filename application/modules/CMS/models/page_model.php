<?php

class Page_model extends Model {

        var $id = 0;
        var $name = '';
        var $title = '';
        var $content = '';
        var $status = '';
        var $menu = '';

        const table_name = 'fs_static_page';

        // get count of total pages in database
        // 
        function get_num_rows($table_name) {
                $this->db->select('*')->from($table_name);
                $query = $this->db->get();
                return ($this->db->count_all_results($table_name));
        }

        // get a speific page or list of pages
        //
        function get($name) {
                $page = 'Sorry, page not available';
                $pages = array();

                $this->db->select('*');
                if ($name)
                        $this->db->where('name', $name);
                $this->db->where('status', 'publish');
                $this->db->from(self::table_name);
                $query = $this->db->get();
                foreach ($query->result() as $c) {
                        if ($name) 
                                return $c;

                        $c = new Page_model();
                        $c->_load_from_query($c);
                        $page = $c->page;
                }
                return $page;
        }
	
        // get this page from database
        // 
        function get_page($type) {
                $page = 'Sorry, page not available';
                $pages = array();

                $this->db->select('*');
                $this->db->where('menu', $type);
                $this->db->where('status', 'publish');
                $this->db->from(self::table_name);
                $query = $this->db->get();
                foreach ($query->result() as $c) {
                        return $c;
                }
                return $page;
        }
	
        // create this object from the database
        //
        function get_from_id($id) {
                $query = $this->db->get_where(self::table_name, array('id' => $id));
                foreach ($query->result() as $c) 
                        $this->_load_from_query($c);

                return $this;
        }

        // save this object to the database
        //
        function save($changes) {
                if (is_array($changes)) {
                        if (isset($changes['content']))
                                $this->content = $changes['content'];
                        if (isset($changes['title']))
                                $this->title = $changes['title'];
                        if (isset($changes['status']))
                                $this->status = $changes['status'];
                        if (isset($changes['menu']))
                                $this->menu = $changes['menu'];
                }

                $this->db->where('id', $this->id);
                $this->db->update(self::table_name, $this);
        }

        // create a database row for this page object
        //
        function create($changes) {
                if (is_array($changes)) {
                        if (isset($changes['content']))
                                $this->content = $changes['content'];

                        // create the url
                        $this->name = $this->_check_for_uniq_url($changes['title']);

                        if (isset($changes['title']))
                                $this->title = $changes['title'];
                        if (isset($changes['status']))
                                $this->status = $changes['status'];
                        if (isset($changes['menu']))
                                $this->menu = $changes['menu'];
                }

                $this->db->insert(self::table_name, $this);

                return $this->db->insert_id();
        }

        // delete this page from the database
        // 
        function delete() {
                $this->db->delete(self::table_name, array('id' => $this->id));
        }

        // create objects from db query results
        //
        function _load_from_query($c) {
                $this->id = $c->id;
                $this->name = $c->name;
                $this->title = $c->title;
                $this->content = $c->content;
                $this->status = $c->status;
                $this->menu = $c->menu;
        }

        // make sure this url doesn't exist, if it does, add 1 to count of similiar urls and append as count
        //
        function _check_for_uniq_url($title) {
                if ($title == '') $title = 'untitled';
                $blog_url = preg_replace('/[^0-9a-z ]+/i', '', $title); 
                $blog_url = str_replace(' ', '-', strtolower($blog_url));
        
                $this->db->from(self::table_name);
                $this->db->like('name', $blog_url, 'after');
                $count = $this->db->count_all_results();
                if ($count > 0)
                        $blog_url .= '_' . $count;
        
                return $blog_url;
        }
}


