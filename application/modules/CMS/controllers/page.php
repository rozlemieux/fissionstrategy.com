<?php

require_once 'cms.php';

// provides editing of pages such as homepage, clients page, about page, services page, etc
//

class Page extends CMS {

        function __construct() {
                parent::__construct();

                $this->load->model('page_model');
        }

        // main index page for the CMS Page, the dashboard which is a grid of all pages
        // 
        function index() {
                $data['menu_highlight'] = "Pages";
                $data['js_grid'] = $this->_load();
                $data['page_title'] = "Pages";
                $data['create_button'] = array("url" => '/CMS/page/new_page', "name" => "Create new Page");
                $data['main_content'] = 'dashboard';
                $this->load->view('includes/template', $data);		
        }

        // Responds to the New Page button on the dashboard page
        // 
        function new_page() {
                $data['menu_highlight'] = "Pages";
                $id = null;

                if (isset($_POST['content']))
                        $id = $this->_save(null);

                $page_model = new page_model();
                if ($id) 
                        $page = $page_model->get_from_id($id);

                $this->_edit_pages($page, $id, "New Page");
        }

        // responds to clicking the edit column on a row in the grid
        // 
        function edit($id) {
                if (isset($_POST['content']))
                        $this->_save($id);

                $page = $this->page_model->get_from_id($id);
                if ($page->menu == 'homepage')
                        $this->_edit_homepage($page, $id, "Editing Page: homepage fields");
                else 
                        $this->_edit_pages($page, $id, "Editing Page: " . $page->title);
        }

        // ajax call from flexigrid to populate rows
        //
        function ajax_load_pages() {
                $this->output->enable_profiler(FALSE);

                $this->load->model('grid_model');
                $this->load->library('flexigrid');
                $valid_fields = array('id', 'name');
                $this->flexigrid->validate_post('id', $valid_fields);
                $records = $this->grid_model->get_pages();
                $this->output->set_header($this->config->item('json_header'));
		
                // NOTE these fields much match the same order as above _load()
                foreach ($records['records']->result() as $row)	{
                        if ($row->name == '') $row->name = 'Name';
                        $record_items[] = array($row->id,
                                          $row->id,
                                          $row->status,
                                          $row->menu,
                                          '<a title="Edit" href="/CMS/page/edit/' . $row->id . '">' . $row->title . '</a>',
                                          $this->truncate(strip_tags($row->content), 60, $break=".", $pad="..."),
                                          $row->name
                        );
                }

                // create a temp export file of this data
                if ($this->input->post('export')) {
                        $contents = '"rowid", "id","Staus", "Menu", "Title","Content", "Name"' . "\n";
                        $this->export($contents, $record_items);
                        return;
                }

                $this->output->set_output($this->flexigrid->json_build($records['record_count'],$record_items));
        }
	
        // configure the grid - identify the columns and their type - this has to match the ajax_load_pages
        //
        function _load() {
                $this->load->helper('flexigrid');

                // NOTE: this order has to match the records in ajax_load_page()
                //
                $colModel['id'] = array('id',40,TRUE,'center',2);
                $colModel['status'] = array('Status',40,TRUE,'left',2);
                $colModel['menu'] = array('page',80,TRUE,'left',2);
                $colModel['title'] = array('Title',200,TRUE,'left',2);
                $colModel['content'] = array('Content',478,TRUE,'left',2);
                $colModel['name'] = array('name',40,TRUE,'left',2);
		
                $num_rows = $this->page_model->get_num_rows('fs_static_page');

                $gridParams = array(
                        'width' => 'auto',
                        'height' => $num_rows * 50,
                        'rp' => $num_rows,
                        'rpOptions' => "[$num_rows / 2, $num_rows]",
                        'pagestat' => 'Displaying: {from} to {to} of {total} items.',
                        'blockOpacity' => 0.5,
                        'title' => 'Content',
                        'showTableToggleBtn' => false
                );
		
                $buttons[] = array('Export','export','grid_functions');
                $grid_js = build_grid_js('Grid',site_url("CMS/page/ajax_load_pages"),$colModel,'name','asc',$gridParams, $buttons);
        
                return $grid_js;
        }

        // provide a wysiwyg editor, and blog data for editing all page variables
        // 
        function _edit_pages($page, $id, $title) {
                $data['menu_highlight'] = "Pages";
                $data['id'] = $id;
                $data['page_title'] = $title;
                $data['page'] = $page;

                $this->load->helper('ckeditor');
                $data['ckeditor'] = array('id' => 'content', 'path'	=> '/public/js/ckeditor', 'config' => array(
                                            'toolbar' 	=> 	"CMS_Full", 
                                            'width' 	=> 	"662px",
                                            'height' 	=> 	'600px',
                                    ));

                $data['main_content'] = 'page';
                $this->load->view('includes/template', $data);		
        }

        // provide editing editable areas of home page
        //
        function _edit_homepage($page, $id, $title) {
                $data['menu_highlight'] = "Pages";
                $data['id'] = $id;
                $data['page_title'] = $title;
                $data['page'] = $page;

                $this->load->helper('ckeditor');
                $data['ckeditor'] = array('id' => 'content', 'path'	=> '/public/js/ckeditor', 'config' => array(
                                            'toolbar' 	=> 	"MyToolbar", 
                                            'width' 	=> 	"662px",
                                            'height' 	=> 	'300px',
                                    ));

                $data['main_content'] = 'homepage';
                $this->load->view('includes/template', $data);		
        }

        // the blog was edited or created, upload any new photos, save the changes or insert a new row
        // 
        function _save($id) {

                $page_model = new Page_model();
                if ($id) {
                        $page = $page_model->get_from_id($id);
                        $page->save($_POST);
                }            
                else {
                        $page = $page_model;
                        $id = $page->create($_POST);
                }

                return $id;
        }
}

