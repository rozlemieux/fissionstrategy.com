<?php

require_once 'cms.php';

class Blogger extends CMS {

        function __construct() {
                parent::__construct();

                $this->load->model('blogger_model');
        }

        // show the grid of all bloggers, no category
        // 
        function index() {
                $this->category('');
        }

        // present a grid of all bloggers in these categories
        // 
        function category($category_ids = array()) {

                $cat_ids = implode('_', $category_ids);

                $data['menu_highlight'] = "Blogger";
                $data['js_grid'] = $this->_load($cat_ids);
                $data['page_title'] = "Blogger CRM";
                $data['create_button'] = array("url" => '/CMS/blogger/new_blogger', "name" => "Create new Blogger");
                $data['categories'] = $this->_build_category_select($cat_ids);
                $data['active_category'] = $cat_ids;
                $data['main_content'] = 'blogger_dashboard';
                $this->load->view('includes/template', $data);		
        }

        // delete this??
        function get_category() {
                $this->category($_POST['category']);
        }

        // Responds to the New Bloggerr button on the dashboard page
        // 
        function new_blogger() {

                $data['menu_highlight'] = "Blogger";
                $id = null;

                if (isset($_POST['status']))
                        $id = $this->_save(null);

                $blogger = new Blogger_model();

                if ($id) 
                        $blogger->get_from_id($id);

                $this->_edit_blogger($blogger, $id, "New Blogger");
        }

        // responds to clicking the edit column on a row in the grid
        // 
        function edit($id) {
                if (isset($_POST['status']))
                        $this->_save($id);

                $blogger = $this->blogger_model->get_from_id($id);
                $this->_edit_blogger($blogger, $id, "Editing Blogger: " . $blogger->title);
        }

        // responds to clicking the delete button when a row or rows are selected in the grid
        // 
        function delete() {
		$ids = explode(',', $_POST['items']);

		foreach ($ids as $i => $id) {
		  if ($id) {
		    $blogger = new Blogger_model($id);
		    $blogger->delete();
		  }
		}
        }

        // ajax call to update one field (called from grid / dashboard)
        function update_field() {
            $id = $this->input->post('edit_id');
            $field = $this->input->post('field_name');
            $value = $this->input->post($field) ? $this->input->post($field) : $this->input->post('value');

            $blogger = new Blogger_model($id);
            $blogger->$field = $value;
            $blogger->save_field();
            
            echo $value;
        }

        // ajax call from flexigrid to populate rows
        //
        function ajax_load_blogger($cat_ids = '') {
                $this->output->enable_profiler(FALSE);

                $this->load->model('grid_model');
                $this->load->library('flexigrid');
                // sortable fields
                $valid_fields = array('id', 'Source', 'Email', 'Email_2', 'import_notes');
                $this->flexigrid->validate_post('id', $valid_fields);
                $records = $this->grid_model->get_blogger($cat_ids);
            
                $exporting = ($this->input->post('export')) ? true : false;
 
                // NOTE these fields much match the same order as above _load()
                foreach ($records['records']->result() as $row)	{

                        // create links for the linkable or emailable values
                        if ($exporting) 
                            $blogname = $row->Blog_Name;
                        else if ($row->URL != '') {
                                if ($row->Blog_Name != '')
                                        $blogname = '<a target="_blank" title="Visit: ' . $row->Blog_Name . '" href="' . $row->URL . '">' . $row->Blog_Name . '</a>';
                                else 
                                        $blogname = '<a target="_blank" title="Visit: ' . $row->URL . '" href="' . $row->URL . '">noname</a>';
                        }

                        $blog_email = '';
                        $blog_email2 = '';
                        $Web_Form_URL = '';
                        $Twitter_Outlet = '';
                        $Facebook = '';

                        if ($exporting) 
                            $blog_email = $row->Email;
                        else if ($row->Email != '')
                            $blog_email = '<a title="Compose Email to: ' . $row->Blog_Name . '" href="mailto:' . $row->Email . '">' . $row->Email . '</a>';

                        if ($exporting) 
                            $blog_email2 = $row->Email_2;
                        else if ($row->Email_2 != '') 
                            $blog_email2 = '<a title="Compose Email to: ' . $row->Blog_Name . '" href="mailto:' . $row->Email2 . '">' . $row->Email2 . '</a>';
                        if ($exporting) 
                            $Web_Form_URL = $row->Web_Form_URL;
                        else if ($row->Web_Form_URL != '') 
                            $Web_Form_URL = '<a target="_blank" title="Visit: ' . $row->Web_Form_URL . '" href="' . $row->Web_Form_URL . '">' . $row->Web_Form_URL . '</a>';

                        if ($exporting) 
                            $Twitter_Outlet = $row->Twitter_Outlet;
                        else if ($row->Twitter_Outlet != '') 
                            $Twitter_Outlet = '<a  target="_blank" title="Visit: ' . $row->Twitter_Outlet . '" href="' . $row->Twitter_Outlet . '">' . $row->Twitter_Outlet . '</a>';
                        
                        if ($exporting) 
                            $Facebook = $row->Facebook;
                        else if ($row->Facebook != '') 
                                $Facebook = '<a  target="_blank" title="Visit: ' . $row->Facebook . '" href="' . $row->Facebook . '">' . $row->Facebook . '</a>';
                        
                        // now record the row 
                        $record_items[] = array($row->id,
                                          $this->_make_action_field($row->id, "/CMS/blogger/edit/" . $row->id),
                                          $this->_make_editable_field($row->Source),
                                          $this->_make_editable_url($row->Blog_Name, $blogname),
                                          $row->catname,
                                          $this->_make_editable_url($row->Email, $blog_email),
                                          $this->_make_editable_field($row->Email_2),
                                          $this->_make_editable_url($row->Web_Form_URL, $Web_Form_URL),
                                          $this->_make_editable_field($row->First_Name),
                                          $this->_make_editable_field($row->Last_Name),
                                          $this->_make_editable_field($row->City),
                                          $this->_make_editable_field($row->State),
                                          $this->_make_editable_field($row->Phone),
                                          $this->_make_editable_field($row->Skype),
                                          $this->_make_editable_field($row->Fax),
                                          $this->_make_editable_field($row->Authority),
                                          $this->_make_editable_field($row->Twitter_Blogger),
                                          $this->_make_editable_field($row->Twitter_Blogger_Followers),
                                          $this->_make_editable_url($row->Twitter_Outlet, $Twitter_Outlet),
                                          $this->_make_editable_field($row->Twitter_Outlet_Followers),
                                          $this->_make_editable_url($row->Facebook, $Facebook),
                                          $this->_make_editable_field($row->URL),
                                          $this->_make_editable_field($row->Notes),
                                          $this->_make_editable_field($row->import_notes),
                                          $this->_make_editable_field($row->Estimated_Readership),
                                          $this->_make_editable_field($row->Additional_Contacts)
                        );
                }

                // create a temp export file of this data
                if ($this->input->post('export')) {

                        $contents = 'rowid,id,Source,Blog_Name,Category, Email,Email_2,Web_Form_URL,First_Name,Last_Name,City,State,Phone,Skype,Fax,Authority, Twitter_Blogger,Twitter_Blogger_Followers,Twitter_Outlet,Twitter_Outlet_Followers,Facebook,URL,Notes,import_notes,Estimated_Readership,Additional_Contact,end' . "\n";

                        $this->export($contents, $record_items);
                        return;
                }

                $this->output->set_header($this->config->item('json_header'));
                $this->output->set_output($this->flexigrid->json_build($records['record_count'],$record_items));
        }
	
        // configure the grid - identify the columns and their type - this has to match the ajax_get_blogger
        //
        function _load($cat_ids = '') {
                $this->load->helper('flexigrid');

                $colModel['id'] = array('id',40,TRUE,'center',2);
                $colModel['Source'] = array('Source',50,TRUE,'left',2);
                $colModel['Blog_Name'] = array('Blog_Name',180,TRUE,'left',2);
                $colModel['catname'] = array('Categories',150,TRUE,'left',2);
                $colModel['Email'] = array('Email',150,TRUE,'left',2);
                $colModel['Email_2'] = array('Email_2',150,TRUE,'left',2);
                $colModel['Web_Form_URL'] = array('Web_Form_URL',100,TRUE,'left',2);
                $colModel['First_Name'] = array('First_Name',80,TRUE,'left',2);
                $colModel['Last_Name'] = array('Last_Name',80,TRUE,'left',2);
                $colModel['City'] = array('City',100,TRUE,'left',2);
                $colModel['State'] = array('State',20,TRUE,'left',2);
                $colModel['Phone'] = array('Phone',100,TRUE,'left',2);
                $colModel['Skype'] = array('Skype',100,TRUE,'left',2);
                $colModel['Fax'] = array('Fax',100,TRUE,'left',2);

                $colModel['Authority'] = array('Authority',100,TRUE,'left',2);
                $colModel['Twitter_Blogger'] = array('Twitter_Blogger',200,TRUE,'left',2);
                $colModel['Twitter_Blogger_Followers'] = array('Twitter_Blogger_Followers',80,TRUE,'left',2);
                $colModel['Twitter_Outlet'] = array('Twitter_Outlet',100,TRUE,'left',2);
                $colModel['Twitter_Outlet_Followers'] = array('Twitter_Outlet_Followers',80,TRUE,'left',2);
                $colModel['Facebook'] = array('Facebook',200,TRUE,'left',2);
                $colModel['URL'] = array('URL',150,TRUE,'left',2);
                $colModel['Notes'] = array('Notes',200,TRUE,'left',2);
                $colModel['import_notes'] = array('Import Notes',200,TRUE,'left',2);
                $colModel['Estimated_Readership'] = array('Estimated_Readership',80,TRUE,'left',2);
                $colModel['Additional_Contacts'] = array('Additional_Contacts',100,TRUE,'left',2);
		
                $gridParams = array(
                        'width' => 'auto',
                        'height' => 600,
                        'rp' => 100,
                        'rpOptions' => '[100, 200, 1000, 2000]',
                        'pagestat' => 'Displaying: {from} to {to} of {total} items.',
                        'blockOpacity' => 0.8,
                        'title' => 'Projects',
                        'showTableToggleBtn' => false
                );
        
                $buttons = array();
                //        $buttons[] = array('separator');
                $buttons[] = array('Select All','select all','grid_functions');
                $buttons[] = array('DeSelect All','deselect all','grid_functions');
                $buttons[] = array('Delete','delete','grid_functions');
                $buttons[] = array('Export','export','grid_functions');

                $grid_js = build_grid_js('Grid',site_url("CMS/blogger/ajax_load_blogger/$cat_ids"),$colModel,'title','asc',$gridParams, $buttons);
        
                return $grid_js;
        }

        // present a page to edit current or new blogger, find categories if existing
        // 
        function _edit_blogger($blogger, $id, $title) {

                $this->load->model('blogger_category_model');
                $data['categories'] = $this->blogger_category_model->get();
                $data['menu_highlight'] = "Bloggers";
                $data['id'] = $id;
                $data['page_title'] = $title;
                $data['blogger'] = $blogger;

                $data['main_content'] = 'blogger';
                $this->load->view('includes/template', $data);		
        }

        // save the new or existing blogger
        //
        function _save($id) {

                $blogger = new Blogger_model($id);

                if ($id)
                        $blogger->save($_POST);
                else 
                        $id = $blogger->create($_POST);

                return $id;
        }

        // for dashboard page - build the multiselect for categories
        // 
        function _build_category_select($category_ids) {
        
                $cat_ids = explode('_', $category_ids);

                $this->load->model('blogger_category_model');
                $categories = $this->blogger_category_model->get_counts();
                $options = array();
                $selected = array();
                foreach ($categories as $i => $cat) {
                        $options[$cat['id']] = $cat['name'] . '  (' . $cat['count'] . ')';
                        if (in_array($cat['id'], $cat_ids))
                                $selected[] = $cat['id'];
                }

                return form_multiselect('category[]', $options, $selected, ' size=8 ');
        }

}
