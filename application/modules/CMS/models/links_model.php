<?php

class Links_model extends Model {
        var $id = '';
        var $url = '';
        var $name = '';
        var $image = '';
        var $target = '_blank';
        var $description = '';
        var $visible = 'Y';
        var $owner = '1';
        var $rating = '';
        var $updated = '';
        var $rel = '';
        var $notes = '';
        var $rss = '';

        const table_name = 'fs_links';

        function __construct($id) {
                parent::Model();
        
                if ($id)
                        $this->get_from_id($id);

                $this->id = $id;
        }

        // get a list of links or a specific one
        //
        function get($name = '', $limit = 20) {

                $links = array();
                $this->db->select('*');
                $this->db->from(self::table_name);
                if ($name)
                        $this->db->where('name', $name);
                $this->db->limit($limit);
                $this->db->order_by('name', 'ASC');
                $query = $this->db->get();
                foreach ($query->result() as $c) {
                        $link = new Links_model();
                        $link->_load_from_query($c);
                        $links[$c->id] = $link;
                }
                return $links;
        }

        // save this link to the database
        //
        function save($changes) {
                if (is_array($changes)) {
                        if (isset($changes['url']))
                                $this->url = $changes['url'];
                        if (isset($changes['name'])) 
                                $this->name = $changes['name'];
                        if (isset($changes['content']))
                                $this->description = $changes['content'];
                        if (isset($changes['target']))
                                $this->target = $changes['target'];
                        $this->load->helper('date');
                        $this->updated = mdate("%Y-%m-%d %h:%i:%a", now());
                        $this->visible = $changes['visible'];
                }

                $this->db->where('id', $this->id);
                $this->db->update(self::table_name, $this);
        }

        // create a new link in the database
        //
        function create($changes) {
                if (is_array($changes)) {
                        if (isset($changes['url']))
                                $this->url = $changes['url'];
                        if (isset($changes['name'])) 
                                $this->name = $changes['name'];
                        if (isset($changes['content']))
                                $this->description = $changes['content'];
                        if (isset($changes['target']))
                                $this->target = $changes['target'];
                }

                $this->db->insert(self::table_name, $this);
                return $this->db->insert_id();
        }

        // fetch the link from the id
        //
        function get_from_id($id) {  
                $query = $this->db->get_where(self::table_name, array('id' => $id));
                foreach ($query->result() as $c) 
                        $this->_load_from_query($c);

                return $this;
        }

        // return total count in this table
        //
        function get_num_rows($table_name) {
                $this->db->select('*')->from($table_name);
                $query = $this->db->get();
                return ($this->db->count_all_results($table_name));
        }

        // delete this link from the database
        // 
        function delete() {
                $this->db->delete(self::table_name, array('id' => $this->id));
        }

        // create objects from db query results
        //
        function _load_from_query($c) {
                foreach ($c as $row) {
                        $this->id = $c->id;
                        $this->url = $c->url;
                        $this->name = $c->name;
                        $this->image = $c->image;
                        $this->target = $c->target;
                        $this->description = $c->description;
                        $this->visible = $c->visible;
                        $this->owner = $c->owner;
                        $this->rating = $c->rating;
                        $this->updated = $c->updated;
                        $this->rel = $c->rel;
                        $this->notes = $c->notes;
                        $this->rss = $c->rss;
                }
        }
}

