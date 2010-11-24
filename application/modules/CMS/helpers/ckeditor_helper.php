<?php
  //if(!defined('BASEPATH')) exit('No direct script access allowed');

/*
 * CKEditor helper for CodeIgniter
 * 
 * @author Samuel Sanchez <samuel.sanchez.work@gmail.com> - http://www.kromack.com/
 * @package CodeIgniter
 * @license http://creativecommons.org/licenses/by-nc-sa/3.0/us/
 * @tutorial http://www.kromack.com/codeigniter/ckeditor-helper-for-codeigniter
 * @see http://codeigniter.com/forums/viewthread/127374/
 * 
 */
function display_ckeditor($data)
{
    $return = '<script type="text/javascript" src="'.base_url(). $data['path'] . '/ckeditor.js"></script>';

    //Adding styles values
    if(isset($data['styles'])) {
    	
    	$return .= "<script type=\"text/javascript\">CKEDITOR.addStylesSet( 'my_styles', [";
   
    	
	    foreach($data['styles'] as $k=>$v) {
	    	
	    	$return .= "{ name : '" . $k . "', element : '" . $v['element'] . "', styles : { ";

	    	if(isset($v['styles'])) {
	    		foreach($v['styles'] as $k2=>$v2) {
	    			
	    			$return .= "'" . $k2 . "' : '" . $v2 . "'";
	    			
					if($k2 !== end(array_keys($v['styles']))) {
						 $return .= ",";
					}
	    		} 
    		} 
	    
	    	$return .= '} }';
	    	
	    	if($k !== end(array_keys($data['styles']))) {
				$return .= ',';
			}	    	
	    	

	    } 
	    
	    $return .= ']);</script>';
    }   
    
    //Building Ckeditor
    
    $return .= "<script type=\"text/javascript\">
     	CKEDITOR_BASEPATH = '" . base_url() . $data['path'] . "/';
     	CKEDITOR.replace('" . $data['id'] . "', {";
    
    		//Adding config values
    		if(isset($data['config'])) {
	    		foreach($data['config'] as $k=>$v) {
	    			
	    			$return .= $k . " : '" . $v . "'";
	    			
	    			if($k !== end(array_keys($data['config']))) {
						$return .= ",";
					}		    			
	    		} 
    		}   			
    				
    $return .= '});';
    
    $return .= "CKEDITOR.config.stylesCombo_stylesSet = 'my_styles';
    </script>";

    return $return;
}