<?php

class Blog_model extends Model {
        var $id = 0;
        var $name = '';
        var $title = '';
        var $author = '';
        var $content = '';
        var $thumb = '';
        var $modified = '';
        var $status = '';
        var $date = '';

        const table_name = 'fs_blog';

        function __construct($id) {
                parent::Model();
        
                if ($id)
                        $this->get_from_id($id);

                $this->id = $id;
        }

        // get the list, or specific blog
        //
        function get($name = '', $limit = 5, $offset = 0, $order = 'DESC', $cms = false, $preview) {

                $blogs = array();
                $this->db->select('*, fs_blog.id as id');
                if (!$preview)
                        $this->db->where('status', 'publish');
                $this->db->from(self::table_name);
                if ($name)
                        $this->db->where('name', $name);
                $this->db->order_by('date', $order);
                $this->db->group_by(self::table_name . '.id');
                $this->db->limit($limit, $offset);
                $this->db->join("fs_membership", "fs_membership.id = fs_blog.author");
                $query = $this->db->get();

                // create an object for each blog
                foreach ($query->result() as $c) {
                        $blog = new Blog_model();
                        $blog->_load_from_query($c);
                        if (!$cms)
                                $blog->author = $c->first_name . ' ' . $c->last_name;
                        $blogs[$c->id] = $blog;
                }
                return $blogs;
        }
	
        // used by the paginator, returns count of published blogs
        //
        function get_num_blogs() {
                $this->db->select('*');
                $this->db->from(self::table_name);
                $this->db->where('status', 'publish');

                return $this->db->count_all_results();
        }

        // blog edit form submitted, save changes
        // 
        function save($changes) {
                if (is_array($changes)) {
                        if (isset($changes['content']))
                                $this->content = $changes['content'];
                        if (isset($changes['title'])) {
                                //                $this->name = $this->_check_for_uniq_url($changes['title']);
                                $this->title = $changes['title'];
                        }
                        if (isset($changes['date']))
                                $this->date = $changes['date'];
                        $this->load->helper('date');
                        $this->author = ($changes['author']) ? $changes['author'] : $changes['current_author'];
                        $this->modified = mdate("%Y-%m-%d %h:%i:%a", now());
                        if (isset($changes['thumb']))
                                $this->thumb = $changes['thumb'];

                        $this->status = $changes['status'];
                }
                $this->db->where('id', $this->id);
                $this->db->update(self::table_name, $this);
        }

        // create a new blog, insert into database
        //
        function create($changes) {

                if (is_array($changes)) {
                        if (isset($changes['content']))
                                $this->content = $changes['content'];

                        // create the url
                        $this->name = $this->_check_for_uniq_url($changes['title']);

                        if (isset($changes['title']))
                                $this->title = $changes['title'];
                        if (isset($changes['thumb']))
                                $this->thumb = $changes['thumb'];
                        $this->load->helper('date');
                        $this->date = mdate("%Y-%m-%d %h:%i:%a", now());
                        $this->author = $changes['author'];
                        $this->modified = $this->date;
                        $this->status = $changes['status'];
                }

                $this->db->insert(self::table_name, $this);
                return $this->db->insert_id();
        }

        // fetch the blog from the id
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

        // delete blog from database
        //
        function delete() {
                $this->db->delete(self::table_name, array('id' => $this->id));
        }

        // create objects from db query results
        //     note: should just do loop over property exists but this way it is clear what fields are being used
        //
        function _load_from_query($c) {
                $this->id = $c->id;
                $this->name = $c->name;
                $this->author = $c->author;
                $this->status = $c->status;
                $this->title = $c->title;
                $this->content = $c->content;
                $this->thumb = $c->thumb;
                $this->date = $c->date;
                $this->modified = $c->modified;
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

