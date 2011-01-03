<?php

require_once 'cms.php';

class Users extends CMS {

        function __construct() {
                parent::__construct();
        }

        // main index page for the CMS User, a dashboard which is a grid of all Users
        // 
        function index() {
                $data['menu_highlight'] = "CMS Users";
                $data['js_grid'] = $this->_load_users();
                $data['create_button'] = array("url" => '/CMS/users/create_user', "name" => "Create new CMS User");
                $data['page_title'] = "CMS Users";
                $data['main_content'] = 'dashboard';
                $this->load->view('includes/template', $data);		
        }

        // responds to clicking the edit column on a row in the grid
        // 
        function edit_user($id) {
                $data['menu_highlight'] = "CMS Users";

                if ($id < 0) {
                        $data['page_title'] = "Create user";
                        $data['main_content'] = 'signup_form';
                        $this->load->view('includes/template', $data);
                        return;
                }
                $this->load->model('membership_model');

                $data['user'] = $this->membership_model->getUser($id);
                $data['page_title'] = "Editing User: " . $data['user']['username'];
                $data['main_content'] = 'signup_form';
                $data['show_errors'] = true;
                $this->load->view('includes/template', $data);
        }

        // responds to clicking the delete button when a row or rows are selected in the grid
        // 
        function delete() {
            $ids = explode(',', $_POST['items']);

            $this->load->model('membership_model');
            $user = new membership_model();
            foreach ($ids as $i => $id) {
                if ($id) {
		    $user->delete($id);
                }
            }
        }

        // Responds to the New user button on the dashboard page
        // 
        function create_user($id = '') {

                $data['menu_highlight'] = "CMS Users";

                $this->load->model('membership_model');
                if ((! isset($_POST['id'])) && (! isset($_POST['create_user']))) {
                        $data['page_title'] = "Signup";
                        $data['main_content'] = 'signup_form';
                        $this->load->view('includes/template', $data);
                        return;
                }

                $data['user'] = $this->membership_model->getUser($id);
                $this->load->library('form_validation');

                $config['upload_path'] = $this->config->item('base_path') . 'uploads/images/Users/';
                $config['allowed_types'] = 'gif|jpg|png';
                //        $config['max_size']	= '100';
                //        $config['max_width']  = '1024';
                //        $config['max_height']  = '768';
                $this->load->library('upload', $config);

                if ( ! $this->upload->do_upload()) {
                        $error = array('error' => $this->upload->display_errors());
                        //            $this->load->view('upload_form', $error);
                        //            $_POST['avatar'] = print_r($error,1);
                }	
                else {
                        $uploaded = $this->upload->data();
                        $_POST['avatar'] = $uploaded['orig_name'];
                }

                // field name, error message, validation rules
                $this->form_validation->set_rules('first_name', 'Name', 'trim|required');
                $this->form_validation->set_rules('last_name', 'Last Name', 'trim|required');
                $this->form_validation->set_rules('email_address', 'Email Address', 'trim|required|valid_email');
                if (isset($_POST['create_user'])) 
                        $this->form_validation->set_rules('username', 'Username', 'callback_username_check');
                else 
                        $this->form_validation->set_rules('username', 'Username', 'trim|required|min_length[4]');

                if ($_POST['password']) {
                        $this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[4]|max_length[32]');
                        $this->form_validation->set_rules('password2', 'Password Confirmation', 'trim|required|matches[password]');
                }
                $data['page_title'] = "CMS Users";

                if ($this->form_validation->run() == FALSE) {
                        $data['main_content'] = 'signup_form';
                        $data['show_errors'] = true;
                        $this->load->view('includes/template', $data);
                }
                else {			
                        if ($query = $this->membership_model->create_user()) {
                                redirect('/CMS/users');
                        }
                        else {
                                $data['main_content'] = 'signup_form';
                                $data['show_errors'] = true;
                                $this->load->view('includes/template', $data);
                        }
                }
        }

        // callback for form_validatian set_rules
        // 
        function username_check($str) {

                $str = trim($str);

                if ($str == '') {
                        $this->form_validation->set_message('username_check', 'The %s is required');
                        return FALSE;
                }

                if ($this->membership_model->username_exists($str)) {
                        $this->form_validation->set_message('username_check', 'The username: ' . $this->input->post('username') . ' already exists');
                        return FALSE;
                }

                return TRUE;
        }
	
        // ajax call to update one field (called from grid / dashboard)
        function update_field() {
            $id = $this->input->post('edit_id');
            $field = $this->input->post('field_name');
            $value = $this->input->post($field) ? $this->input->post($field) : $this->input->post('value');

            $this->load->model('membership_model');
            $fields = array($field => $value);
            $this->membership_model->save($id, $fields);
            
            echo $value;
        }

        // ajax call from flexigrid to populate rows
        //
        function ajax_load_users() {

                $exporting = ($this->input->post('export')) ? true : false; 

                $this->output->enable_profiler(FALSE);

                $this->load->model('grid_model');
                $this->load->library('flexigrid');
                // sortable fields
                $valid_fields = array('id', 'first_name', 'last_name', 'username', 'email');
                $this->flexigrid->validate_post('id', $valid_fields);
                $records = $this->grid_model->get_users();
                $this->output->set_header($this->config->item('json_header'));

                // note these must be in same order as in above _load_users()
                foreach ($records['records']->result() as $row)	{

                        $avatar = ($row->avatar != 0) ? "<img width='50' src='/uploads/images/Users/{$row->avatar}' />" : '&nbsp;';
                        if ($exporting) 
                                $avatar = $row->avatar;

                        $record_items[] = array($row->id,
                                          $this->_make_action_field($row->id, "/CMS/users/edit_user/" . $row->id),
                                          $avatar,
                                          $this->_make_editable_field($row->username),
                                          $this->_make_editable_field($row->first_name),
                                          $this->_make_editable_field($row->last_name),
                                          $this->_make_editable_field($row->email_address)
                        );
                }

                // create a temp export file of this data
                if ($exporting) {
                        $contents = '"rowid", "id","Avatar", "Username", "First Name","Last Name", "Email"' . "\n";
                        $this->export($contents, $record_items);
                        return;
                }

                $this->output->set_output($this->flexigrid->json_build($records['record_count'],$record_items));
        }

        // configure the grid - identify the columns and their type - this has to match the ajax_get_blog
        //
        function _load_users() {
                $this->load->helper('flexigrid');

                $colModel['id'] = array('id',40,TRUE,'center',2);
                $colModel['avatar'] = array('Avatar',50,TRUE,'center',2);
                $colModel['username'] = array('Username',200,TRUE,'left',2);
                $colModel['first_name'] = array('First Name',200,TRUE,'left',2);
                $colModel['last_name'] = array('Last Name',200,TRUE,'left',2);
                $colModel['email_address'] = array('Email',100,TRUE,'left',2);
		
                $gridParams = array(
                        'width' => 'auto',
                        'height' => 420,
                        'rp' => 20,
                        'rpOptions' => '[15,20]',
                        'pagestat' => 'Displaying: {from} to {to} of {total} items.',
                        'blockOpacity' => 0.8,
                        'title' => 'CMS Users',
                        'showTableToggleBtn' => false
                );
		
                $buttons[] = array('Select All','select all','grid_functions');
                $buttons[] = array('DeSelect All','deselect all','grid_functions');
                $buttons[] = array('Delete','delete','grid_functions');
                $buttons[] = array('Export','export','grid_functions');
                $grid_js = build_grid_js('Grid',site_url("CMS/users/ajax_load_users"),$colModel,'username','asc',$gridParams, $buttons);
        
                return $grid_js;
        }

}
