<?php

require_once 'cms.php';

// manages the list of blogs in the blogroll
//

class Links extends CMS {
        function __construct() {
                parent::__construct();

                $this->load->model('links_model');
        }

        // main index page for the CMS blog is the dashboard which is a grid of all blogs
        // 
        function index() {
                $data['menu_highlight'] = "Links";
                $data['js_grid'] = $this->_load();
                $data['links_title'] = "Links";
                $data['create_button'] = array("url" => '/CMS/links/new_links', "name" => "Create new Links");
                $data['main_content'] = 'dashboard';
                $this->load->view('includes/template', $data);		
        }

        // Responds to the New Link button on the dashboard page
        // 
        function new_links() {
                $data['menu_highlight'] = "Links";
                $id = null;

                if (isset($_POST['content']))
                        $id = $this->_save(null);

                $links_model = new links_model();
                if ($id) 
                        $links = $links_model->get_from_id($id);

                $this->_edit_links($links, $id, "New Links");
        }

        // responds to clicking the edit column on a row in the grid
        // 
        function edit($id) {

                if (isset($_POST['content']))
                        $this->_save($id);

                $links = $this->links_model->get_from_id($id);
                $this->_edit_links($links, $id, "Editing Links: " . $links->title);
        }

        // responds to clicking the delete button when a row or rows are selected in the grid
        // 
        function delete() {
		$ids = explode(',', $_POST['items']);

		foreach ($ids as $i => $id) {
		  if ($id) {
		    $link = new Links_model($id);
		    $link->delete();
		  }
		}
        }

        function _edit_links($links, $id, $title) {
                $data['menu_highlight'] = "Links";
                $data['id'] = $id;
                $data['links_title'] = $title;
                $data['link'] = $links;

                $this->load->helper('ckeditor');
                $data['ckeditor'] = array('id' => 'content', 'path'	=> '/public/js/ckeditor', 'config' => array(
                                            'toolbar' 	=> 	"CMS_Full", 
                                            'width' 	=> 	"662px",
                                            'height' 	=> 	'200px',
                                    ));
		
                $data['main_content'] = 'links';
                $this->load->view('includes/template', $data);		
        }

        // ajax call from flexigrid to populate rows
        //
        function ajax_load_links() {
                $this->output->enable_profiler(FALSE);

                $this->load->model('grid_model');
                $this->load->library('flexigrid');
                $valid_fields = array('id', 'name');
                $this->flexigrid->validate_post('id', $valid_fields);
                $records = $this->grid_model->get_links();
                $this->output->set_header($this->config->item('json_header'));
		
                $num_rows = $this->links_model->get_num_rows('fs_links');
                // NOTE these fields much match the same order as above _load()
                foreach ($records['records']->result() as $row)	{
                        if ($row->name == '') $row->name = 'Name';
                        $record_items[] = array($row->id,
                                          $row->id,
                                          $row->url,
                                          '<a title="Edit" href="/CMS/links/edit/' . $row->id . '">' . $row->name . '</a>',
                                          $row->target,
                                          $this->truncate(strip_tags($row->description), 60, $break=".", $pad="...")
                        );
                }

                // create a temp export file of this data
                if ($this->input->post('export')) {
                        $contents = '"rowid", "id","Url", "Name", "Target","Description"' . "\n";
                        $this->export($contents, $record_items);
                        return;
                }

                $this->output->set_output($this->flexigrid->json_build($records['record_count'],$record_items));
        }
	
        // configure the grid - identify the columns and their type - this has to match the ajax_load_links
        //
        function _load() {
                $this->load->helper('flexigrid');

                // NOTE: this order has to match the records in grid_ajax: content()
                //
                $colModel['id'] = array('id',40,TRUE,'center',2);
                $colModel['url'] = array('URL',200,TRUE,'left',2);
                $colModel['name'] = array('name',200,TRUE,'left',2);
                $colModel['target'] = array('Target',40,TRUE,'left',2);
                $colModel['description'] = array('Description',478,TRUE,'left',2);
		
                $num_rows = $this->links_model->get_num_rows('fs_links');

                $gridParams = array(
                        'width' => 'auto',
                        'height' => $num_rows * 32,
                        'rp' => $num_rows,
                        'rpOptions' => "[$num_rows / 2, $num_rows]",
                        'linkstat' => 'Displaying: {from} to {to} of {total} items.',
                        'blockOpacity' => 0.5,
                        'title' => 'Content',
                        'showTableToggleBtn' => false
                );
		
                $buttons[] = array('Select All','select all','grid_functions');
                $buttons[] = array('DeSelect All','deselect all','grid_functions');
                $buttons[] = array('Delete','delete','grid_functions');
                $buttons[] = array('Export','export','grid_functions');
                $grid_js = build_grid_js('Grid',site_url("CMS/links/ajax_load_links"),$colModel,'name','asc',$gridParams, $buttons);
        
                return $grid_js;
        }

        // the link was edited or created, upload any new photos, save the changes or insert a new row
        // 
        function _save($id) {

                $links_model = new Links_model();
                if ($id) {
                        $links = $links_model->get_from_id($id);
                        $links->save($_POST);
                }            
                else {
                        $links = $links_model;
                        $id = $links->create($_POST);
                }

                return $id;
        }
}

