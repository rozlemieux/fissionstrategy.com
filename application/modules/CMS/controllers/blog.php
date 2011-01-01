<?php

if (!function_exists('json_encode')) {
	require_once 'includes/jsonwrapper_inner.php';
} 

require_once 'cms.php';

class Blog extends CMS {
        function __construct() {
                parent::__construct();

                $this->load->model('blog_model');
        }

        // main index page for the CMS blog is the dashboard which is a grid of all blogs
        // 
        function index() {
                $data['menu_highlight'] = "Blog";
                $data['js_grid'] = $this->_load();
                $data['page_title'] = "Blog";
                $data['create_button'] = array("url" => '/CMS/blog/new_blog', "name" => "Create new Blog");
                $data['main_content'] = 'dashboard';
                $this->load->view('includes/template', $data);		
        }

        // Responds to the New Blog button on the dashboard page
        // 
        function new_blog() {

                $data['menu_highlight'] = "Blog";
                $id = null;

                if (isset($_POST['content']))
                        $id = $this->_save(null);

                $blog = new Blog_model();
                if ($id) $blog->get_from_id($id);

                $this->_edit_blog($blog, $id, "New Blog");
        }

        // responds to clicking the edit column on a row in the grid
        // 
        function edit($id) {
                if (isset($_POST['content']))
                        $this->_save($id);

                $blog = $this->blog_model->get_from_id($id);
                $this->_edit_blog($blog, $id, "Editing Blog: " . $blog->title);
        }

        // responds to clicking the delete button when a row or rows are selected in the grid
        // 
        function delete() {
		$ids = explode(',', $_POST['items']);

		foreach ($ids as $i => $id) {
		  if ($id) {
		    $blog = new Blog_model($id);
		    $blog->delete();
		  }
		}
        }

        // ajax call to update one field (called from grid / dashboard)
        function update_field() {
            $id = $this->input->post('edit_id');
            $field = $this->input->post('field_name');
            $value = $this->input->post($field) ? $this->input->post($field) : $this->input->post('value');

            $team = new Blog_model($id);
            $team->$field = $value;
            $team->save(null);
            
            echo $value;
        }

        // ajax call from flexigrid to populate rows
        //
        function ajax_get_blog() {
                // must disable profiler or ajax will include json and then html profile info
                $this->output->enable_profiler(FALSE);

                $this->load->model('grid_model');
                $this->load->library('flexigrid');

                // sortable fields
                $valid_fields = array('id', 'title', 'status', 'author', 'name',  'date', 'modified');
                $this->flexigrid->validate_post('id', $valid_fields);
                $records = $this->grid_model->get_blog();
                $this->output->set_header($this->config->item('json_header'));

                // NOTE these fields much match the same order as _load()
                foreach ($records['records']->result() as $row)	{
                        $record_items[] = array($row->bid,
                                          $this->_make_action_field($row->bid, "/CMS/blog/edit" . $row->bid),
                                          "<img width='50' src='/uploads/images/Blog/$row->thumb' />",
                                          $this->_make_editable_field($row->title),
                                          $this->_make_editable_select($row->status),
                                          $row->first_name . ' ' . $row->last_name,
                                          $this->_make_editable_field($row->name),
                                          $row->date,
                                          $row->modified
                        );
                }

                // create a temp export file of this data
                if ($this->input->post('export')) {
                        $contents = '"rowid", "id","Thumb", "Status", "Name","Created_Date","Modified","name"' . "\n";
                        $this->export($contents, $record_items);
                        return;
                }

                $this->output->set_output($this->flexigrid->json_build($records['record_count'],$record_items));
        }

        // configure the grid - identify the columns and their type - this has to match the ajax_get_blog
        //
        function _load() {
                $this->load->helper('flexigrid');

                $colModel['bid'] = array('bid',40,TRUE,'center',2);
                $colModel['thumb'] = array('Thumb',50,TRUE,'center',2);
                $colModel['title'] = array('Title',400,TRUE,'left',2);
                $colModel['status'] = array('Status',40,TRUE,'center',2);
                $colModel['author'] = array('Author',100,TRUE,'left',2);
                $colModel['name'] = array('Name',300,TRUE,'left',2);
                $colModel['date'] = array('Created',100,TRUE,'left',2);
                $colModel['modified'] = array('Modified',100,TRUE,'left',2);
		
                $gridParams = array(
                        'width' => 'auto',
                        'height' => 620,
                        'rp' => 40,
                        'rpOptions' => '[40,200]',
                        'pagestat' => 'Displaying: {from} to {to} of {total} items.',
                        'blockOpacity' => 1.0,
                        'title' => 'Projects',
                        'showTableToggleBtn' => false
                );
		
                $buttons[] = array('Select All','select all','grid_functions');
                $buttons[] = array('DeSelect All','deselect all','grid_functions');
                $buttons[] = array('Delete','delete','grid_functions');
                $buttons[] = array('Export','export','grid_functions');
                $grid_js = build_grid_js('Grid',site_url("CMS/blog/ajax_get_blog"),$colModel,'bid','desc',$gridParams, $buttons);
        
                return $grid_js;
        }

        // remaining functions are private methods
        //

        
        // provide a wysiwyg editor, and blog data for editing all blog variables
        // 
        function _edit_blog($blog, $id, $title) {

                $data['menu_highlight'] = "Blog";
                $data['id'] = $id;
                $data['page_title'] = $title;
                $data['blog'] = $blog;

                $this->_build_calendar();
                $this->load->helper('ckeditor');
                $data['ckeditor'] = array('id' => 'content', 
                                    'path'	=> '/public/js/ckeditor', 
                                    'config' => array(
                                            'toolbar' 	=> 	"CMS_Full", 
                                            'width' 	=> 	"662px",
                                            'height' 	=> 	'600px'
                                    ));
		
                $data['main_content'] = 'blog';
                $this->load->view('includes/template', $data);		
        }

        // the blog was edited or created, upload any new photos, save the changes or insert a new row
        // 
        function _save($id) {
                $blog = new Blog_model($id);

                $config['upload_path'] = $this->config->item('base_dir') . 'uploads/images/Blog/';
                $config['allowed_types'] = 'gif|jpg|png';
                //        $config['max_size']	= '100';
                //        $config['max_width']  = '1024';
                //        $config['max_height']  = '768';

                $this->load->library('upload', $config);

                if ( ! $this->upload->do_upload()) {
                        $error = array('error' => $this->upload->display_errors());
                        //            $this->load->view('upload_form', $error);
                        //echo "ERROR" . print_r($error, 1);
                }	
                else {
                        $uploaded = $this->upload->data();
                        $_POST['thumb'] = $uploaded['orig_name'];
                }

                $uid = $this->session->userdata('id');
                $_POST['author'] = ($uid > 0) ? $uid : 1;

                if ($id)
                        $blog->save($_POST);
                else 
                        $id = $blog->create($_POST);

                return $id;
        }

}
