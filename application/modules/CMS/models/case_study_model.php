<?php

class Case_study_model extends Model {
        var $id = 0;
        var $name = '';
        var $title = '';
        var $author = '';
        var $content = '';
        var $excerpt = '';
        var $thumb = '';
        var $modified = '';
        var $status = '';
        var $date = '';
        var $quote = '';
        var $new = 'false';
        var $url = '';
        var $image = '';
        var $tags = array();
        var $photos = array();

        const table_name = 'fs_case_study';

        function __construct($id) {
                parent::Model();
        
                if ($id)
                        $this->get_from_id($id);

                $this->id = $id;
        }

        // get the list, or specific blog
        //    note: need to get_photos for this specific range
        //
        function get($name = '', $limit = 99, $offset = 0, $category = 0, $preview = 0) {

                $photos = $this->_get_photos();
                $tags = $this->_get_tags();
                $case_studies = array();
                $this->db->select('*, fs_case_study.id as id');
                $this->db->where('status', 'publish');
                if ($preview)
                        $this->db->or_where('id', $preview);
                $this->db->from(self::table_name);
                if ($category > 1) {
                        $this->db->join('fs_map', 'fs_map.case_study_id = fs_case_study.id');
                        $this->db->where('fs_map.cat_id', $category);
                }

                if ($name)
                        $this->db->where('name', $name);
                $this->db->order_by('date', 'DESC');
                $this->db->limit($limit, $offset);
                $query = $this->db->get();
                foreach ($query->result() as $c) {
                        $case_study = new case_study_model();
                        $case_study->_load_from_query($c, $tags, $photos);
                        $case_studies[$c->id] = $case_study;
                }
                return $case_studies;
        }

        // get a quote for the homepage or sidebar rotating quotes
        // 
        function get_quote($offset) {

                $this->db->select('id, quote, author');
                $this->db->where("status = 'publish' and quote != ''");
                $this->db->from(self::table_name);
                $limit = 1;
                $this->db->limit($limit, $offset);
                $query = $this->db->get();
                foreach ($query->result() as $c) {
                    $c->order = $offset;
                    return $c;
                }

                return null;
        }

        // get total rows
        //
        function get_num() {
                $this->db->select('*');
                $this->db->from(self::table_name);
                $this->db->where('status', 'publish');

                return $this->db->count_all_results();
        }

        // save this object to the database - save categories and photos too
        // 
        function save($changes) {
            
                $this->_save_photos($changes);
        
                // save a thumbnail if there is one
                foreach ($changes['images'] as $key => $image) {
                        $changes['image'] = $image;
                        break;
                }

                // if new category, create it first
                if (isset($changes['new_category']) && ($changes['new_category'] != '')) {
                        $category_name = str_replace(' ', '_', $changes['new_category']);
                        $this->db->insert('fs_category', array('name' => $category_name, 'parent_id' => 1));
                        $changes['category'][] = $this->db->insert_id();
                }

                // add any categories
                $categories = $changes['category'];
                foreach ($categories as $i => $cat_id) {
                        $data = array(
                                'case_study_id' => $this->id,
                                'cat_id' => $cat_id
                        );
                        $this->db->insert('fs_map', $data);
                }

                // save all fields from POST if they exist in this class
                if (is_array($changes)) {
                        foreach ($changes as $key => $value) {
                                if (property_exists($this, $key)) 
                                        $this->$key = $value;
                        }
                }

                $this->load->helper('date');
                $this->modified = mdate("%Y-%m-%d %h:%i:%a", now());
                if (isset($changes['new']))
                        $this->new = ($changes['new'] == '') ? 'Y' : 'N';

                if (isset($changes['status']))
                    $this->status = $changes['status'];

                $this->db->where('id', $this->id);
                $this->db->update(self::table_name, $this);
        }

        // create a new case study - add photos and categories if they exist
        //
        function create($changes) {
                if (is_array($changes)) {

                        // save a thumbnail if there is one
                        foreach ($changes['images'] as $key => $image) {
                                $changes['image'] = $image;
                                break;
                        }

                        // save all fields from POST if they exist in this class
                        if (is_array($changes)) {
                                foreach ($changes as $key => $value) {
                                        if (property_exists($this, $key)) 
                                                $this->$key = $value;
                                }
                        }

                        // create the url
                        $this->name = $this->_check_for_uniq_url($changes['title']);

                        $this->load->helper('date');
                        $this->date = mdate("%Y-%m-%d %h:%i:%a", now());
                        $this->modified = $this->date;
                        $this->status = $changes['status'];
                }

                $this->db->insert(self::table_name, $this);
                $this->id = $this->db->insert_id();

                $images = $changes['images'];
                foreach ($images as $key => $image) {
                        $this->db->insert('fs_case_study_photo', array('case_study_id' => $this->id, 'url' => $image));
                }

                // if new category, create it first
                if (isset($changes['new_category']) && ($changes['new_category'] != '')) {
                        $category_name = str_replace(' ', '_', $changes['new_category']);
                        $this->db->insert('fs_category', array('name' => $category_name, 'parent_id' => 1));
                        $changes['category'][] = $this->db->insert_id();
                }

                // add any categories
                $categories = $changes['category'];
                foreach ($categories as $i => $cat_id) {
                        $data = array(
                                'case_study_id' => $this->id,
                                'cat_id' => $cat_id
                        );
                        $this->db->insert('fs_map', $data);
                }

                return $this->id;
        }

        // get this case study object from the database 
        // 
        function get_from_id($id) {  
                $photos = $this->_get_photos();
                $tags = $this->_get_tags();

                $query = $this->db->get_where(self::table_name, array('id' => $id));
                foreach ($query->result() as $c) 
                        $this->_load_from_query($c, $tags, $photos);

                return $this;
        }

        // get total rows in this table
        //
        function get_num_rows($table_name) {
                $this->db->select('*')->from($table_name);
                $query = $this->db->get();
                return ($this->db->count_all_results($table_name));
        }

        // delete case study from database
        //
        function delete() {
                $this->db->delete(self::table_name, array('id' => $this->id));
        }

        // get photos for all case studies or for specific one
        // 
        function _get_photos($id = '') {

                $photos = array();
                $this->db->select('*');
                $this->db->from('fs_case_study_photo');
                $query = $this->db->get();
                foreach ($query->result() as $c) {
                        if (!isset($photos[$c->case_study_id]))
                                $photos[$c->case_study_id] = array();

                        $photos[$c->case_study_id][$c->id] = $c->url;
                }
                return $photos;
        }
	
        // save photos to database - delete photos marked to be deleted first, then add or update new/changed photos
        // 
        function _save_photos($changes) {

                // delete any photos marked to be deleted
                for ($i = 0; $i < 100; $i++) {

                        if (!isset($changes["userfile{$i}_id"]))
                                break;
                        $photo_id = $changes["userfile{$i}_id"];
                        if ($changes["userfile{$i}_delete"] == 'on') 
                                $this->db->delete('fs_case_study_photo', array('id' => $photo_id));
                }

                // update or insert any photos updated or added
                $images = $changes['images'];
                foreach ($images as $key => $image) {
                        if (isset($changes["userfile{$key}_id"])) {
                                $this->db->where("id", $changes["userfile{$key}_id"]);
                                $this->db->update('fs_case_study_photo', array('case_study_id' => $this->id, 'url' => $image));
                        }
                        else 
                                $this->db->insert('fs_case_study_photo', array('case_study_id' => $this->id, 'url' => $image));
                }
        }
	
        // get all the categories for these case_studies
        // 
        function _get_tags($id = '') { 

                $tags = array();
                $this->db->select('*');
                $this->db->join('fs_category', 'fs_category.id = fs_map.cat_id');
                $this->db->where('case_study_id >', '0');
                // 1 is the 'case study' category id
                $this->db->where('fs_category.id !=', '1');
                $this->db->from('fs_map');
                $query = $this->db->get();
                foreach ($query->result() as $c) {
                        if (!isset($tags[$c->case_study_id]))
                                $tags[$c->case_study_id] = array();

                        $tags[$c->case_study_id][$c->id] = array('id' => $c->id, 'name' => $c->name);
                }
                return $tags;
        }
	
        // create objects from db query results
        //     note: should just do loop over property exists but this way it is clear what fields are being used
        //
        function _load_from_query($c, $tags = array(), $photos = array()) {
                $this->id = $c->id;
                $this->name = $c->name;
                $this->author = $c->author;
                $this->status = $c->status;
                $this->title = $c->title;
                $this->content = $c->content;
                $this->excerpt = ($c->excerpt) ? $c->excerpt : $this->_truncate($c->content, 200, $break=" ", $pad=" ...");
                $this->thumb = $c->thumb;
                $this->url = $c->url;
                $this->date = $c->date;
                $this->image = $c->image;
                $this->quote = $c->quote;
                $this->new = $c->new;
                $this->modified = $c->modified;
                $this->tags = (isset($tags[$c->id])) ? $tags[$c->id] : array();
                $this->photos = (isset($photos[$c->id])) ? $photos[$c->id] : array();
        }

        // make sure this url doesn't exist, if it does, add 1 to count of similiar urls and append as count
        //
        function _check_for_uniq_url($title) {
                if ($title == '') $title = 'untitled';
                $case_study_url = preg_replace('/[^0-9a-z ]+/i', '', $title); 
                $case_study_url = str_replace(' ', '-', strtolower($case_study_url));
        
                $this->db->from(self::table_name);
                $this->db->like('name', $case_study_url, 'after');
                $count = $this->db->count_all_results();
                if ($count > 0)
                        $case_study_url .= '_' . $count;
        
                return $case_study_url;
        }

        // common function to truncate strings
        //
        function _truncate($string, $limit, $break=".", $pad="...") {

                if(strlen($string) <= $limit) return $string;

                if (false !== ($breakpoint = strpos($string, $break, $limit))) {
                        if($breakpoint < strlen($string) - 1) {
                                $string = substr($string, 0, $breakpoint) . $pad;
                        }
                }
    
                return $string;
        }

}

