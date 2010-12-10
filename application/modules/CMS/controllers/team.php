<?php
require_once 'cms.php';

class Team extends CMS {
        function __construct() {
                parent::__construct();

                $this->load->model('team_model');
        }

        // main index page for the CMS Team is the dashboard which is a grid of all team members
        // 
        function index() {
                $data['menu_highlight'] = "Team";
                $data['js_grid'] = $this->_load();
                $data['page_title'] = "Team";
                $data['create_button'] = array("url" => '/CMS/team/new_team_member', "name" => "Create new Team member");
                $data['main_content'] = 'dashboard';
                $this->load->view('includes/template', $data);		
        }

        // Responds to the New Team member button on the dashboard page
        // 
        function new_team_member() {

                $data['menu_highlight'] = "Team";
                $id = null;

                if (isset($_POST['content']))
                        $id = $this->_save(null);

                $team_member = new Team_model($id);

                $this->_edit_team_member($team_member, $id, "New Team Member");
        }

        // responds to clicking the edit column on a row in the grid
        // 
        function edit($id) {
                if (isset($_POST['content']))
                        $this->_save($id);

                $team_member = $this->team_model->get_from_id($id);
                $this->_edit_team_member($team_member, $id, "Editing team member: " . $team_member->name);
        }

        // responds to clicking the delete button when a row or rows are selected in the grid
        // 
        function delete() {
		$ids = explode(',', $_POST['items']);

		foreach ($ids as $i => $id) {
		  if ($id) {
		    $tean = new team_model($id);
		    $tean->delete();
		  }
		}
        }

        // ajax call from flexigrid to populate rows
        //
        function ajax_load_team_members() {
                $this->output->enable_profiler(FALSE);

                $this->load->model('grid_model');
                $this->load->library('flexigrid');
                // sortable fields
                $valid_fields = array('id', 'name', 'email', 'linkedin',  'twitter', 'modified', 'team_title');
                $this->flexigrid->validate_post('id', $valid_fields);
                $records = $this->grid_model->get_team_members();
                $this->output->set_header($this->config->item('json_header'));

                // NOTE these fields much match the same order as above _load()
                foreach ($records['records']->result() as $row)	{
                        $record_items[] = array($row->id,
                                          $row->id,
                                          "<img width='50' src='/uploads/images/Team/$row->photo' />",
                                          '<a title="Edit" href="/CMS/team/edit/' . $row->id . '">' . $row->name . '</a>',
                                          $row->team_title,
                                          $row->email,
                                          $row->linkedin,
                                          $row->twitter,
                                          $row->skype,
                                          $row->modified
                        );
                }

                // create a temp export file of this data
                if ($this->input->post('export')) {
                        $contents = '"rowid", "id", "Photo","Name", "Team Title", "Email","Linked In", "Twitter", "Skype", "Date Modified"' . "\n";
                        error_log(" what is the problem??");
                        $this->export($contents, $record_items);
                        return;
                }

                $this->output->set_output($this->flexigrid->json_build($records['record_count'],$record_items));
        }

        // configure the grid - identify the columns and their type - this has to match the ajax_get_blog
        //
        function _load() {
                $this->load->helper('flexigrid');

                $colModel['id'] = array('id',40,TRUE,'center',2);
                $colModel['photo'] = array('Photo',50,TRUE,'center',2);
                $colModel['name'] = array('Name',120,TRUE,'left',2);
                $colModel['team_title'] = array('Team title',120,TRUE,'left',2);
                $colModel['email'] = array('Email',150,TRUE,'left',2);
                $colModel['linkedin'] = array('Linkedin',180,TRUE,'left',2);
                $colModel['twitter'] = array('Twitter',100,TRUE,'left',2);
                $colModel['skype'] = array('Skype',100,TRUE,'left',2);
                $colModel['modified'] = array('modified',40,TRUE,'left',2);
		
                $gridParams = array(
                        'width' => 'auto',
                        'height' => 620,
                        'rp' => 20,
                        'rpOptions' => '[15,20]',
                        'pagestat' => 'Displaying: {from} to {to} of {total} items.',
                        'blockOpacity' => 1.0,
                        'title' => 'Projects',
                        'showTableToggleBtn' => false
                );
		
                $buttons[] = array('Select All','select all','grid_functions');
                $buttons[] = array('DeSelect All','deselect all','grid_functions');
                $buttons[] = array('Delete','delete','grid_functions');
                $buttons[] = array('Export','export','grid_functions');
                $grid_js = build_grid_js('Grid',site_url("CMS/team/ajax_load_team_members"),$colModel,'id','asc',$gridParams, $buttons);
        
                return $grid_js;
        }

        // provide a wysiwyg editor, and blog data for editing all team member variables
        // 
        function _edit_team_member($team_member, $id, $title) {

                $data['menu_highlight'] = "Team";
                $data['id'] = $id;
                $data['page_title'] = $title;
                $data['team_member'] = $team_member;

                $this->_build_calendar();
                $this->load->helper('ckeditor');
                $data['ckeditor'] = array('id' => 'content', 'path'	=> '/public/js/ckeditor', 'config' => array(
                                            'toolbar' 	=> 	"CMS_Full", 
                                            'width' 	=> 	"662px",
                                            'height' 	=> 	'600px',
                                    ));
		
                $data['main_content'] = 'team_member';
                $this->load->view('includes/template', $data);		
        }

        // the team member was edited or created, upload any new avatar, save the changes or insert a new row
        // 
        function _save($id) {
                $team = new Team_model($id);

                $config['upload_path'] = $this->config->item('base_dir') . 'uploads/images/Team/';
                $config['allowed_types'] = 'gif|jpg|png';
                //        $config['max_size']	= '100';
                //        $config['max_width']  = '1024';
                //        $config['max_height']  = '768';

                $this->load->library('upload', $config);

                if ( ! $this->upload->do_upload()) {
                        $error = array('error' => $this->upload->display_errors());
                        //            $this->load->view('upload_form', $error);
                        echo "ERROR" . print_r($error, 1);
                }	
                else {
                        $uploaded = $this->upload->data();
                        $_POST['photo'] = $uploaded['orig_name'];
                }

                if ($id)
                        $team->save($_POST);
                else 
                        $id = $team->create($_POST);

                return $id;
        }

}
