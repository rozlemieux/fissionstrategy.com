<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * CodeIgniter
 *
 * An open source application development framework for PHP 4.3.2 or newer
 *
 * @package		CodeIgniter
 * @author		ExpressionEngine Dev Team
 * @copyright	Copyright (c) 2008 - 2009, EllisLab, Inc.
 * @license		http://codeigniter.com/user_guide/license.html
 * @link			http://codeigniter.com
 * @since			Version 1.0
 * @filesource
 */

// ------------------------------------------------------------------------

/**
 * CodeIgniter Cache Helpers
 *
 * @package			CodeIgniter
 * @subpackage	Helpers
 * @category			Helpers
 * @author			Garry Welding
 * @link				http://www.in-the-attic.co.uk/2010/02/07/codeigniter-cache-helper/
 */

// ------------------------------------------------------------------------

/**
 * delete_cache
 *
 * Lets you delete a cache file for a page or array of pages with a simple function call.
 *
 * @access	public
 * @param 	array 	optional parameter to delete a cache file for any array of pages
 * @return		bool		returns true on success and false on failure
 */	
if ( ! function_exists('delete_cache'))
{
	function delete_cache(array $pages = null)
	{
		// get an instance of codeigniter
		$CI =& get_instance();
		
		// pretty obvious, get the cache page from the config file
		$path = $CI->config->item('cache_path');
		
		// if config value is empty then set cache path as the basepath plus cache/ else if it's not
		// empty then set $cache_path as $path.
		$cache_path = ($path == '') ? BASEPATH.'cache/' : $path;
		
		// check the cache directory even exists
		if ( ! is_dir($cache_path) OR ! is_really_writable($cache_path))
		{
			// if it doesn't then log the error and return false
			log_message('error', "Cache directory could not be found: ".$cache_path);
			return false;
		}
		
		// check to see if a variable has been passed into the function, if not then assume they
		// want to delete the cache for the current page
		if(!isset($pages['0']) || strlen($pages['0']) < 1)
		{
			$pages['0'] =	$CI->config->item('base_url').
									$CI->config->item('index_page').
									$CI->uri->uri_string();
		}
		
		// loop over the pages requested
		foreach($pages as $uri)
		{
			// calculate the MD5 hash of the full URL, this is what codeigniter uses to name the cache file
			$cache_path_tmp = $cache_path.md5($uri);

			// check to see if we can find the file
			if ( ! @is_file($cache_path_tmp))
			{
				// if we can't find the file then great, less work for us!
				log_message('debug', "Unable to find cache file: ".$cache_path_tmp);
			} else {
				// if we find the file then attempt to delete it
				if( @unlink($cache_path_tmp))
				{
					// if we successfully deleted it then rejoice!
					log_message('debug', "Cache file deleted: ".$cache_path_tmp);
				} else {
					// otherwise, cry... and return false to stop further processing as something is wrong
					log_message('error', "Unable to delete cache file: ".$cache_path_tmp);
					return false;
				}
			}
			
			unset($cache_path_tmp);
		}
		
		// assume everything has gone fine and return true
		return true;
	}
}

/* End of file cache_helper.php */
/* Location: ./system/helpers/cache_helper.php */