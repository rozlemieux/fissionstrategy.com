<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"> 
<html xmlns="http://www.w3.org/1999/xhtml"> 
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"> 
   <title><?php echo $this->config->item('site_name') ?> CMS - <?php echo $page_title ?></title>
    <link rel="shortcut icon" href="/favicon.ico" /> 
   
	<link rel="stylesheet" href="<?php echo base_url();?>application/modules/CMS/css/reset.css" type="text/css" media="screen" title="no title" />
	<link rel="stylesheet" href="<?php echo base_url();?>application/modules/CMS/css/text.css" type="text/css" media="screen" title="no title" />
	<link rel="stylesheet" href="<?php echo base_url();?>application/modules/CMS/css/form.css" type="text/css" media="screen" title="no title" />
	<link rel="stylesheet" href="<?php echo base_url();?>application/modules/CMS/css/buttons.css" type="text/css" media="screen" title="no title" />
	<link rel="stylesheet" href="<?php echo base_url();?>application/modules/CMS/css/grid.css" type="text/css" media="screen" title="no title" />	
	<link rel="stylesheet" href="<?php echo base_url();?>application/modules/CMS/css/layout.css" type="text/css" media="screen" title="no title" />	
	
	<link rel="stylesheet" href="<?php echo base_url();?>application/modules/CMS/css/ui-darkness/jquery-ui-1.8.12.custom.css" type="text/css" media="screen" title="no title" />
	<link rel="stylesheet" href="<?php echo base_url();?>application/modules/CMS/css/plugin/jquery.visualize.css" type="text/css" media="screen" title="no title" />
	<link rel="stylesheet" href="<?php echo base_url();?>application/modules/CMS/css/plugin/facebox.css" type="text/css" media="screen" title="no title" />
	<link rel="stylesheet" href="<?php echo base_url();?>application/modules/CMS/css/plugin/uniform.default.css" type="text/css" media="screen" title="no title" />
	<link rel="stylesheet" href="<?php echo base_url();?>application/modules/CMS/css/plugin/dataTables.css" type="text/css" media="screen" title="no title" />

	<link rel="stylesheet" href="<?php echo base_url();?>application/modules/CMS/css/custom.css" type="text/css" media="screen" title="no title">
    

    <!-- link rel="stylesheet" href="<?php echo base_url();?>css/style.css" type="text/css" media="screen" / -->
    <!-- link href="<?php echo $this->config->item('base_url');?>public/css/flexigrid.css?1" rel="stylesheet" type="text/css" / -->
    <!-- link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/calendar_pi.css" / --> 
    <!-- link rel="stylesheet" href="<?php echo base_url();?>css/admin.css?1" type="text/css" media="screen"/ -->
    
    <script src="/js/cufon-yui.js" type="text/javascript"></script>
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script> 
    <script type="text/javascript" src="<?php echo $this->config->item('base_url');?>public/js/flexigrid.js"></script>
    <script type="text/javascript" src="<?php echo $this->config->item('base_url');?>application/modules/CMS/js/admin.js"></script>
    <script type="text/javascript">
      Cufon.replace('.navigation a, .intro h3, .title, .footer', { fontFamily: 'Museo Slab 500' });
    </script>

  </head>

  <body>

<div id="wrapper">
	
	<div id="top">
		
		<div class="content_pad">			
		<img src="http://madebyamp.com/themes/dashboard/images/logo.png" />	<ul class="right">
				<li>
       <?php if( $this->session && $this->session->userdata('username') ) {    ?>
       <div id="logout">
          <h4>Welcome back <strong><?php echo $this->session->userdata('username'); ?></strong>&nbsp;-&nbsp;<?php echo anchor('/CMS/login/logout', 'Logout'); ?></h4>
       </div>                                                                               
       <?php } ?></li>
			</ul>
		</div> <!-- .content_pad -->
		
	</div> <!-- #top -->	
	
	<div id="header">
		
		<div class="content_pad">
			
			    <ul id="nav">
				<li class="nav_current nav_icon"><a href="./dashboard.html"><span class="ui-icon ui-icon-home"></span>Home</a></li>
				<li  class="nav_icon" <?php echo is_current($menu_highlight, 'Blog'); ?>><a href="/CMS/blog">Blog</a></li>
                <li class="nav_icon" <?php echo is_current($menu_highlight, 'Blogger'); ?>><a href="/CMS/blogger">Bloggers</a></li>
                <li class="nav_icon" <?php echo is_current($menu_highlight, 'Case Studies'); ?>><a href="/CMS/case_study">Case Studies</a></li>
                <li class="nav_icon" <?php echo is_current($menu_highlight, 'Pages'); ?>><a href="/CMS/page">Pages</a></li>
                <li class="nav_icon" <?php echo is_current($menu_highlight, 'Links'); ?>><a href="/CMS/links">Links</a></li>
                <li class="nav_icon" <?php echo is_current($menu_highlight, 'CMS Users'); ?>><a href="/CMS/users"><span class="ui-icon ui-icon-gear"></span>CMS Users</a></li>
                <li class="nav_icon" <?php echo is_current($menu_highlight, 'Templates'); ?>><a href="/CMS/templates"><span class="ui-icon ui-icon-signal"></span>Templates</a>
				
				<li class="nav_dropdown nav_icon_only">
					<a href="javascript:;">&nbsp;</a>
					
					<div class="nav_menu">
						
						<ul>
							 <?php  /*
                   $this->load->model('template_model'); 
                   $views = $this->template_model->list_templates();
                   foreach ($views as $view) {
                     echo '<li><a href="/CMS/templates/edit/' . $view['name'] . '">' . $view['name'] . '</a></li>';
                  }
                          */
                  ?>
						</ul>
					</div> <!-- .menu -->
				</li>
			</ul>

		</div> <!-- .content_pad -->
	
	<div id="top">
    
    <?php // lightbox and edit_popup are used for the ajax editing of the grid ?>
    <div id="lightbox"></div>
    <div id="edit_popup" style="display: none;" >
      <form action="edit_field">
        <input id="edit_id" name="edit_id" type="hidden" value="" />
        <input id="field_name" name="field_name" type="hidden" value="" />

        <div id="popup_who"></div>
        <div>
          <div class="popup_label">Editing: <span id="popup_title"></span></div>
          <div><textarea type="text" name="value" id="popup_value" /></textarea></div>
          <div id="popup_extra"></div>
       </div>
      <div class="popup_buttons">
        <div id="save" style="float: right" class="popup_button">Save</div>
        <div id="cancel" class="popup_button">Cancel</div>
      </div>
    </form>
</div> 
	</div> <!-- end top -->       
       
      
 </div> <!-- end div header -->
                                                                            
       

<?php

function is_current($page_title, $name) {

    if ($page_title == $name)
       return " class='current_page_item' ";
    
      return '';
}
?>