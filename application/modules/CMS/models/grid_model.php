<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

if (!function_exists('json_encode')) {
	require_once 'jsonwrapper_inner.php';
} 

class Grid_model extends Model {

        // this is the model used by the grid (CMS table of blogs, case studies, etc)
        // 
        // This is called from ajax and has additional arguments for the query - pagination, sort order, search item
        // 
        public function Grid_model()  {
                parent::Model();
                $this->CI =& get_instance();
        }
	
        // get blogs that meet criteria and pagination, and the total count 
        //
        public function get_blog() {
                // return all records that match the critera and fall within the page
                // 
                $this->db->select('*, fs_blog.id as bid')->from('fs_blog');
                $this->db->join("fs_membership", "fs_membership.id = fs_blog.author");
                if (empty($_POST['sortname']))
                        $this->db->order_by('fs_blog.id', 'DESC');
                $this->db->group_by('fs_blog.id');
                $this->CI->flexigrid->build_query();
                $rs['records'] = $this->db->get();

                // now return the total count that meet this criteria
                $this->db->select('*, fs_blog.id as bid')->from('fs_blog');
                $this->db->join("fs_membership", "fs_membership.id = fs_blog.author");
                if (empty($_POST['sortname']))
                        $this->db->order_by('fs_blog.id', 'DESC');
                $this->db->group_by('fs_blog.id');
                $this->CI->flexigrid->build_query(FALSE);
                $record_count = $this->db->get();
                $rs['record_count'] = $record_count->num_rows;

                return $rs;
        }

        // get bloggers that meet criteria and pagination, and the total count 
        //
        public function get_blogger($category_ids) {

                // get records for this page (offset, limit)
                $this->_join_category($category_ids);
                $this->CI->flexigrid->build_query();
                $rs['records'] = $this->db->get();

                // Get total Record Count 
                $this->_join_category($category_ids);
                $this->CI->flexigrid->build_query(FALSE); 
                $record_count = $this->db->get();
                $rs['record_count'] = $record_count->num_rows;

                return $rs;
        }
		
        // get full list of the remaining objects (gridjs will add search criteria, pagination, etc)
        //
        public function get_case_study() {
                if (empty($_POST['sortname']))
                        $this->db->order_by('id', 'DESC');
                return $this->_get("fs_case_study");
        }

        
        public function get_pages() {
                return $this->_get("fs_static_page");
        }

        public function get_links() {
                return $this->_get("fs_links");
        }

        public function get_subscribers() {
                return $this->_get("fs_subscribers");
        }

        public function get_users() {
                return $this->_get("fs_membership");
        }
		
        public function get_team_members() {
                return $this->_get("fs_team");
        }
		
        // not implemented
        public function delete($id) {
                $deleted = $this->db->query('DELETE FROM fs_XXX WHERE id='.$id);
                return TRUE;
        }

        // get all records and record count for specified table and criteria
        //
        public function _get($table_name, $count = 0) {
                $this->db->select('*')->from($table_name);
                $this->CI->flexigrid->build_query();

                $rs['records'] = $this->db->get();
                if ($count) 
                        $rs['record_count'] = $count;
                else 
                        $rs['record_count'] = $this->db->count_all_results($table_name);

                return $rs;
        }

        // if categor(ies) specified, join category_map and group by id to return only those which are in all categories specified
        //  
        private function _join_category($category_ids) {

                $concat_category = ' DISTINCT fs_blogger_category.name order by fs_blogger_category.name ';
                if ($category_ids) {
                        $this->db->select('*, group_concat(' . $concat_category . ') as catname, count(fs_blogger.id), fs_blogger.id as id')->from('fs_blogger');
                        $this->db->join("fs_blogger_cat_map", "fs_blogger_cat_map.blogger_id = fs_blogger.id");
                        $this->db->join("fs_blogger_category", "fs_blogger_category.id = fs_blogger_cat_map.category_id");
                        $cat_ids = explode('_', $category_ids);
                        foreach ($cat_ids as $i => $cat) 
                                $this->db->or_where("fs_blogger_cat_map.category_id", $cat);
                        $this->db->group_by("fs_blogger.id");
                        if (count($cat_ids) > 1)
                                $this->db->having("count(fs_blogger.id) > " . (count($cat_ids) - 1));
                }
                else {
                        $this->db->select('*, group_concat(' . $concat_category . ' ) as catname, fs_blogger.id as id')->from('fs_blogger');
                        $this->db->join("fs_blogger_cat_map", "fs_blogger_cat_map.blogger_id = fs_blogger.id");
                        $this->db->join("fs_blogger_category", "fs_blogger_category.id = fs_blogger_cat_map.category_id");
                        $this->db->group_by("fs_blogger.id");
                }
        }

}

?>