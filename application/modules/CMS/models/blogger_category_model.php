<?php

class Blogger_category_model extends Model {
        var $id = '';
        var $cat_id = '';
        var $name = '';

        const table_name = 'fs_blogger_category';

        function __construct($id) {
                parent::Model();
        
                if ($id)
                        $this->get_from_id($id);

                $this->id = $id;
        }

        // get list of all categories or get a specific category
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

                // create objects from databae 
                foreach ($query->result() as $c) {
                        $link = new Blogger_category_model();
                        $link->_load_from_query($c);
                        $links[$c->id] = $link;
                }
                return $links;
        }

        // get count for each category
        //
        function get_counts() {

                $categories = array();

                $this->db->select('*, fs_blogger_category.id as id, count(*) as total');
                $this->db->from(self::table_name);
                $this->db->join("fs_blogger_cat_map", "fs_blogger_cat_map.category_id = fs_blogger_category.id"); 
                $this->db->order_by('name', 'ASC');
                $this->db->group_by('fs_blogger_cat_map.category_id');
                $query = $this->db->get();
                foreach ($query->result() as $c) 
                        $categories[] = array('id' => $c->id, 'name' => $c->name, 'count' => $c->total);

                return $categories;
        }

        // save changes to database
        //
        function save($changes) {
                if (is_array($changes)) {
                        if (isset($changes['name'])) 
                                $this->name = $changes['name'];
                }

                $this->db->where('id', $this->id);
                $this->db->update(self::table_name, $this);
        }

        // save changes to database by inserting new row
        //
        function create($changes) {
                if (is_array($changes)) {
                        if (isset($changes['name'])) 
                                $this->name = $changes['name'];
                }

                $this->db->insert(self::table_name, $this);
                return $this->db->insert_id();
        }

        // delete from database
        //
        function delete() {
                $this->db->delete(self::table_name, array('id' => $this->id));
        }

        // get this specific category from the database
        //
        function get_from_id($id) {  
                $query = $this->db->get_where(self::table_name, array('id' => $id));
                foreach ($query->result() as $c) 
                        $this->_load_from_query($c);

                return $this;
        }

        // get humber of rows in this database
        //
        function get_num_rows($table_name) {
                $this->db->select('*')->from($table_name);
                $query = $this->db->get();
                return ($this->db->count_all_results($table_name));
        }

        // fill in the object from database rows
        //
        function _load_from_query($c) {
                $this->id = $c->id;
                $this->cat_id = $c->cat_id;
                $this->name = $c->name;
        }


}

