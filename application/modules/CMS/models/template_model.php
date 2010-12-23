<?php

class Template_model extends Model {

        // list of directories to expose the template files (all php, css, js in these directories)
        // 
        var $dirkeys = array(
                'views' => 'application/views/',
                'includes' => 'application/views/includes/',
                'css' => 'css/',
                'js' => 'js/');

        // get a specific file using the dirkey to find the directory
        //
        function get($dirkey, $name) {
                $dirname = $this->config->item('base_path');

                return file_get_contents($dirname . $this->dirkeys[$dirkey] . $name);
        }
    
        // list all themplates in all directories
        // 
        function list_templates() {

                $files = array();
                foreach ($this->dirkeys as $key => $dirname) 
                        $files = array_merge($files, $this->_list_files($key, $dirname));

                return $files;
        }

        // save this file, create a revision. 
        ///     CONFIG:  $config['global_xss_filtering'] = FALSE;   
        //           has to be set to allow saving php and <scripts>
        //           would have to specfically call xss filtering on any uploads by users
        //
        function save($dirkey, $name) {

                $dirname = $this->config->item('base_path');
                $dirname .= $this->dirkeys[$dirkey];

                $filename = $dirname . $name; 
                $revision = $dirname . '.' . $name . '.' . time();

                rename($filename, $revision);
        
                $contents = str_replace('&lt;', '<', $_POST['template']);
                $contents = str_replace('&gt;', '>', $contents);

                file_put_contents($filename, $contents);
        }

        // list all the php, css, or js files in the configured directories
        //
        function _list_files($dirkey, $dirname) {

                $files = array();
                $rootname = $this->config->item('base_path');

                $handle = opendir($rootname . $dirname);
        
                if (!$handle) return array();
                while (($filename = readdir($handle)) !== false ) {
                        if ($filename == "." || $filename == "..")
                                continue;
                        if (!preg_match('/.+\.(php|css|js)$/', $filename))
                                continue;

                        if (is_dir($dirname . '/' . $filename)) 
                                continue;

                        $path = "$rootname$dirname$filename";
                        if (file_exists($path)) {
                                $files[] = array('dir' => $dirkey, 'name' => "$filename");
                        }

                }
                closedir($handle);

                return $files;
        }

        // list all revisions for this file
        //
        function _list_revisions($dirkey, $dirname) {

                $files = array();
                $rootname = $this->config->item('base_path');

                $handle = opendir($rootname . $dirname);
        
                if (!$handle) return array();
                while (($filename = readdir($handle)) !== false ) {
                        if ($filename == "." || $filename == "..")
                                continue;
                        if (!preg_match('/.+\.(php|css|js)$/', $filename))
                                continue;

                        if (is_dir($dirname . '/' . $filename)) 
                                continue;

                        $path = "$rootname$dirname$filename";
                        if (file_exists($path)) {
                                $files[] = array('dir' => $dirkey, 'name' => "$filename");
                        }

                }
                closedir($handle);

                return $files;
        }

}

?>