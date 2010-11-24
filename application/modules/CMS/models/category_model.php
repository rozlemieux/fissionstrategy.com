<?php

class Category_model extends Model {
        var $id = '';
        var $cat_id = '';
        var $name = '';

        const table_name = 'fs_category';

        function __construct($id) {
                parent::Model();
        
                if ($id)
                        $this->get_from_id($id);

                $this->id = $id;
        }

        // get list of categories
        //
        function get($name = '', $limit = 999) {

                $links = array();
                $this->db->select('*');
                $this->db->from(self::table_name);
                if ($name)
                        $this->db->where('name', $name);
                $this->db->limit($limit);
                $this->db->order_by('name', 'ASC');
                $query = $this->db->get();
                foreach ($query->result() as $c) {
                        $link = new Category_model();
                        $link->_load_from_query($c);
                        $links[$c->id] = $link;
                }
                return $links;
        }

        // get list of categories
        //
        function get_list() {

                $categories = array();

                $this->db->select('*');
                $this->db->from(self::table_name);
                $this->db->order_by('name', 'ASC');
                $query = $this->db->get();
                foreach ($query->result() as $c)
                        $categories[] = array('id' => $c->id, 'name' => $c->name);

                return $categories;
        }

        // save this category
        //
        function save($changes) {
                if (is_array($changes)) {
                        if (isset($changes['name'])) 
                                $this->name = $changes['name'];
                }

                $this->db->where('id', $this->id);
                $this->db->update(self::table_name, $this);
        }

        // create a new category
        //
        function create($changes) {
                if (is_array($changes)) {
                        if (isset($changes['name'])) 
                                $this->name = $changes['name'];
                }

                $this->db->insert(self::table_name, $this);
                return $this->db->insert_id();
        }

        // get this category object from id
        //
        function get_from_id($id) {  
                $query = $this->db->get_where(self::table_name, array('id' => $id));
                foreach ($query->result() as $c) 
                        $this->_load_from_query($c);

                return $this;
        }

        // get total number of categories
        //
        function get_num_rows($table_name) {
                $this->db->select('*')->from($table_name);
                $query = $this->db->get();
                return ($this->db->count_all_results($table_name));
        }

        // delete this category from database
        //
        function delete() {
                $this->db->delete(self::table_name, array('id' => $this->id));
        }

        // load this category object from database
        //
        function _load_from_query($c) {
                foreach ($c as $row) {
                        $this->id = $c->id;
                        $this->cat_id = $c->cat_id;
                        $this->name = $c->name;
                }
        }
}

