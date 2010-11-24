<?php

require_once 'cms.php';

class Case_study extends CMS {
        function __construct() {
                parent::__construct();

                $this->load->model('case_study_model');
        }

        // show the grid of all case studies, no category
        // 
        function index() {
                $data['menu_highlight'] = "Case Studies";
                $data['js_grid'] = $this->_load();
                $data['page_title'] = "Case Studies";
                $data['create_button'] = array("url" => '/CMS/case_study/new_case_study', "name" => "Create new Case Study");
                $data['main_content'] = 'dashboard';
                $this->load->view('includes/template', $data);		
        }

        // responds to the new case study button on the dashboard, prents a form
        // 
        function new_case_study() {

                $data['menu_highlight'] = "Case Studies";
                $id = null;

                if (isset($_POST['content']))
                        $id = $this->_save(null);

                $case_study = new Case_study_model();
                $this->load->model('category_model');

                if ($id) 
                        $case_study->get_from_id($id);

                $this->_edit_case_study($case_study, $id, "New Case Study");
        }

        // responds to clicking the edit column on a row in the grid
        // 
        function edit($id) {

                if (isset($_POST['content']))
                        $this->_save($id);

                $this->load->model('category_model');
                $case_study = $this->case_study_model->get_from_id($id);
                $this->_edit_case_study($case_study, $id, "Editing Case Study: " . $case_study->title);
        }

        // ajax call from flexigrid to populate rows
        //
        function ajax_load_case_study() {
                $this->output->enable_profiler(FALSE);

                $this->load->model('grid_model');
                $this->load->library('flexigrid');
                // sortable fields
                $valid_fields = array('id', 'title', 'status', 'author', 'name',  'date', 'modified');
                $this->flexigrid->validate_post('id', $valid_fields);
                $records = $this->grid_model->get_case_study();
                $this->output->set_header($this->config->item('json_header'));

                // NOTE these fields much match the same order as above _load()
                foreach ($records['records']->result() as $row)	{
                        $record_items[] = array($row->id,
                                          $row->id,
                                          "<img width='50' src='/uploads/images/CaseStudies/$row->image' />",
                                          $row->status,
                                          '<a class="grid_edit" title="Edit" href="/CMS/case_study/edit/' . $row->id . '">' . $row->title . '</a>',
                                          $row->quote,
                                          $row->date,
                                          $row->modified,
                                          $row->new,
                                          $row->author,
                                          $row->name
                        );
                }

                // create a temp export file of this data
                if ($this->input->post('export')) {
                        $contents = '"rowid", "id","Image", "Status", "Title","Quote","Created", "Modified","New", "author", "name"' . "\n";
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
                $colModel['image'] = array('Image',50,TRUE,'left',2);
                $colModel['status'] = array('Status',50,TRUE,'left',2);
                $colModel['title'] = array('Title',200,TRUE,'left',2);
                $colModel['quote'] = array('Quote',420,TRUE,'left',2);
                $colModel['date'] = array('Created',100,TRUE,'left',2);
                $colModel['modified'] = array('Modified',100,TRUE,'left',2);
                $colModel['new'] = array('New',40,TRUE,'left',2);
                $colModel['author'] = array('Author',40,TRUE,'left',2);
                $colModel['name'] = array('Name',200,TRUE,'left',2);
		
                $gridParams = array(
                        'width' => 'auto',
                        'height' => 420,
                        'rp' => 40,
                        'rpOptions' => '[40, 200]',
                        'pagestat' => 'Displaying: {from} to {to} of {total} items.',
                        'blockOpacity' => 0.8,
                        'title' => 'Projects',
                        'showTableToggleBtn' => false
                );
		
                $buttons[] = array('Export','export','grid_functions');
                $grid_js = build_grid_js('Grid',site_url("CMS/case_study/ajax_load_case_study"),$colModel,'title','asc',$gridParams, $buttons);
        
                return $grid_js;
        }

        // provide a wysiwyg editor, and blog data for editing all blog variables
        // 
        function _edit_case_study($case_study, $id, $title) {

                $data['categories'] = $this->category_model->get_list();
                $data['menu_highlight'] = "Case Studies";
                $data['id'] = $id;
                $data['page_title'] = $title;
                $data['case_study'] = $case_study;

                $this->_build_calendar();
                $this->load->helper('ckeditor');
                $data['ckeditor'] = array('id' => 'content', 'path'	=> '/public/js/ckeditor', 'config' => array(
                                            'toolbar' 	=> 	"CMS_Full", 
                                            'width' 	=> 	"662px",
                                            'height' 	=> 	'200px',
                                    ));
		
                $data['main_content'] = 'case_study';
                $this->load->view('includes/template', $data);		
        }

        // the case study was edited or created, upload any new photos, catetories, save the changes or insert a new row
        // 
        function _save($id) {

                $case_study = new Case_study_model($id);

                $config['upload_path'] = $this->config->item('base_dir') . 'uploads/images/CaseStudies/';
                $config['allowed_types'] = 'gif|jpg|png';
                //        $config['max_size']	= '100';
                //        $config['max_width']  = '1024';
                //        $config['max_height']  = '768';

                $this->load->library('upload', $config);

                // upload photos for this case study
                //
                foreach ($_FILES as $key => $value) {
                        if ( ! $this->upload->do_upload($key)) {
                                $error = array('error' => $this->upload->display_errors());
                                //if ($error == 'You did not select a file to upload.')
                        }	
                        else {
                                $upload_data = $this->upload->data();
                                $up_filename = $config['upload_path'] . $upload_data['file_name'];
                                $filename = $config['upload_path'] . $value['name'];

                                $_POST['images'][$key] = $value['name'];

                                // resize the image, create a 500x image and a thumbnail (.sm.jpg)
                                // 
                                $convert = "convert -resize 500x -quality 90 '$filename' '$filename.jpg'";
                                $output = shell_exec($convert); 

                                $convert = "convert -resize 256x '$filename' '$filename.sm.jpg'";
                                $output = shell_exec($convert); 

                                $convert = "convert -crop 256x256+0+0 '$filename.sm.jpg' '$filename.sm.jpg'";
                                $output = shell_exec($convert); 
                        }
                }

                if ($id)
                        $case_study->save($_POST);
                else 
                        $id = $case_study->create($_POST);

                return $id;
        }
}
