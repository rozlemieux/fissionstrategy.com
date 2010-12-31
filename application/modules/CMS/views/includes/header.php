<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"> 
<html xmlns="http://www.w3.org/1999/xhtml"> 
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"> 
   <title><?php echo $this->config->item('site_name') ?> CMS - <?php echo $page_title ?></title>
    <link rel="shortcut icon" href="/favicon.ico" /> 
    <link rel="stylesheet" href="<?php echo base_url();?>css/style.css" type="text/css" media="screen" />
    <link href="<?php echo $this->config->item('base_url');?>public/css/flexigrid.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="<?php echo base_url();?>application/modules/CMS/css/admin.css" type="text/css" media="screen" />
    <script src="/js/cufon-yui.js" type="text/javascript"></script>
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script> 
    <script type="text/javascript" src="<?php echo $this->config->item('base_url');?>public/js/flexigrid.js"></script>
    <script type="text/javascript" src="<?php echo $this->config->item('base_url');?>application/modules/CMS/js/admin.js"></script>
    <script type="text/javascript">
      Cufon.replace('.navigation a, .intro h3, .title, .footer', { fontFamily: 'Museo Slab 500' });
    </script>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url()?>application/modules/CMS/css/calendar_pi.css" /> 

  </head>

  <body>
    <div id="lightbox"></div>
    <div class="wrapper">
     <div class="header">
       <a href="/" class="logo"></a>
       <?php if( $this->session && $this->session->userdata('username') ) {    ?>
       <div id="logout">
          <h4>Welcome back <strong><?php echo $this->session->userdata('username'); ?></strong>&nbsp;-&nbsp;<?php echo anchor('/CMS/login/logout', 'Logout'); ?></h4>
       </div>                                                                               
       <?php } ?>

       <div class="navigation">
         <ul>
          <li <?php echo is_current($menu_highlight, 'Blog'); ?>><a href="/CMS/blog">Blog</a></li>
          <li <?php echo is_current($menu_highlight, 'Blogger'); ?>><a href="/CMS/blogger">Bloggers</a></li>
          <li <?php echo is_current($menu_highlight, 'Case Studies'); ?>><a href="/CMS/case_study">Case Studies</a></li>
          <li <?php echo is_current($menu_highlight, 'Team'); ?>><a href="/CMS/team">Team</a></li>
          <li <?php echo is_current($menu_highlight, 'Pages'); ?>><a href="/CMS/page">Pages</a></li>
 	  <li <?php echo is_current($menu_highlight, 'Links'); ?>><a href="/CMS/links">Links</a></li>
 	  <li <?php echo is_current($menu_highlight, 'CMS Users'); ?>><a href="/CMS/users">CMS Users</a></li>
 	  <li <?php echo is_current($menu_highlight, 'Templates'); ?>><a href="/CMS/templates">Templates</a>
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
           </li>
        </ul>
      </div>
    </div>

<?php

function is_current($page_title, $name) {

    if ($page_title == $name)
       return " class='current_page_item' ";
    
      return '';
}
