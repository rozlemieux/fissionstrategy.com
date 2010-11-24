<?php

/**
 * Project:     ContentFeeder: A library for assembling content feeds
 * File:        ContentFeeder.class.php
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 *
 * @link http://www.phpinsider.com/php/code/ContentFeeder/
 * @copyright 2004-2005 New Digital Group, Inc.
 * @author Monte Ohrt <monte at newdigitalgroup dot com>
 * @package ContentFeeder
 * @version 2.1-dev
 */

class ContentFeeder {
    
    /**
     * ContentFeeder version number
     *
     * @var string
     */
    var $_version = 'ContentFeeder 2.0';

    /**
     * output character set
     *
     * @var string
     */
    var $_charset = 'UTF-8';

    /**
     * output content type
     *
     * @var string
     */
    var $_content_type = 'application/xml';

    /**
     * content element container
     *
     * @var array
     */
    var $_elements = array();
    
    /**
     * output indent character string
     *
     * @var string
     */    
    var $_indent_char = '    ';

    /**
     * caching enabled
     *
     * @var boolean
     */
    var $_caching = false;
    
    /**
     * cache time-to-live
     *
     * @var string
     */    
    var $_cache_ttl = 3600;
    
    /**
     * cache file directory
     *
     * @var string
     */    
    var $_cache_dir = './cache';
    
    /**
     * id used for cache file
     *
     * @var string
     */    
    var $_cache_id = 'BASE';

    /**
     * date format default RFC 2822
     *
     * @var string
     */    
    var $_date_format = 'r';    
    
    /**
     * send http headers with output
     *
     * @var boolean
     */    
    var $_send_http_headers = true;

    /**
     * content namespace container
     *
     * @var array
     */
    var $_namespace = array();

    /**
     * content stylesheet container
     *
     * @var array
     */
    var $_stylesheet = array();    

    /**
     * tag generation for empty elements
     *
     * @var boolean
     */    
    var $_empty_elements = false;    

    /**
     * tag generation for empty elements
     *
     * @var boolean
     */    
    var $_default_escape_type = 'none';        
                    
    /**#@-*/
    /**
     * The class constructor.
     */
    function ContentFeeder($charset = null) {
        if(isset($charset))
            $this->setCharset($charset);
        // look for cache dir in same directory as script
        $this->_cache_dir = dirname($_SERVER['PHP_SELF']) . '/cache';
    }
    
    /**
     * set the value of an element
     *
     * @param string $element the element name
     * @param string $value the element value
     */
    function setElement($element, $value) {
        $this->_elements[$element]['value'] = $value;
    }     

    /**
     * set the type of an element
     *
     * @param string $element the element name
     * @param string $type the element type
     */
    function setElementType($element, $type) {
        if(!isset($this->_elements[$element]))
            $this->_trigger_error("setElementType: element '$element' not set");
        else
            $this->_elements[$element]['type'] = $type;
    }    

    /**
     * set the escapement behavior on an element
     *
     * @param boolean $enable true/false
     */
    function setElementEscape($element, $enable = true) {
        if(!isset($this->_elements[$element]))
            $this->_trigger_error("setElementEscape: element '$element' not set");
        else
            $this->_elements[$element]['escape'] = $enable;
    }    

    /**
     * set the escapement type on an element
     *
     * @param boolean $enable true/false
     */
    function setElementEscapeType($element, $type) {
        if(!isset($this->_elements[$element]))
            $this->_trigger_error("setElementEscapeType: element '$element' not set");
        else
            $this->_elements[$element]['escape_type'] = $type;
    }    
        
    /**
     * set the strip-tag behavior on an element
     *
     * @param string $element element name
     * @param boolean $enable true/false
     * @param string $excluded_tags list of tags to exclude from strip
     */
    function setElementStripTags($element, $enable = true, $excluded_tags = null) {
        if(!isset($this->_elements[$element]))
            $this->_trigger_error("setElementStripTags: element '$element' not set");
        else {
            $this->_elements[$element]['strip_tags'] = $enable;
            $this->_elements[$element]['strip_tags_excluded_tags'] = $excluded_tags;
        }
    }    
    
    /**
     * set the truncate behavior on an element
     *
     * @param string $element element name
     * @param boolean $enable true/false
     * @param integer $truncate_length character length to trunctate to, including suffix
     * @param string $truncate_suffix characters to append to truncated string
     * @param boolean $truncate_word_boundary truncate at word boundary true/false
     */
    function setElementTruncate($element, $enable = true, $truncate_length = 255, $truncate_suffix = ' ...', $truncate_word_boundary = true) {
        if(!isset($this->_elements[$element]))
            $this->_trigger_error("setElementTruncate: element '$element' not set");
        else {
            $this->_elements[$element]['truncate'] = $enable;
            $this->_elements[$element]['truncate_length'] = $truncate_length;
            $this->_elements[$element]['truncate_suffix'] = $truncate_suffix;
            $this->_elements[$element]['truncate_word_boundary'] = $truncate_word_boundary;
        }
    }  

    /**
     * set the date format for a given element
     *
     * @param string $value PHP date() format
     */
    function setElementDateFormat($element, $format) {
        if(!isset($this->_elements[$element]))
            $this->_trigger_error("setElementDateFormat: element '$element' not set");
        elseif($this->_elements[$element]['type'] != 'date')
            $this->_trigger_error("setElementDateFormat: element '$element' must be type 'date'");
        elseif(strlen($format) == 0)
            $this->_trigger_error("setElementDateFormat: value cannot be empty");            
        else
            $this->_elements[$element]['date_format'] = $format;
    }    
            
    /**
     * set the attribute values for an element
     *
     * @param string $element element name
     * @param string $attr_name attribute name
     * @param string $attr_value attribute value
     */
    function setElementAttr($element, $attr_name, $attr_value) {
        $this->_elements[$element]['attrs'][$attr_name] = $attr_value;
    }
        
    /**
     * set the character set for the output
     *
     * @param string $charset character set name
     */
    function setCharset($charset) {
        $this->_charset = $charset;   
    }

    /**
     * set the character set for the output
     *
     * @param string $charset character set name
     */
    function setDefaultEscapeType($type) {
        $this->_default_escape_type = $type;   
    }    
        
    /**
     * enable caching of the output
     *
     */
    function enableCache() {
        $this->_caching = true;               
    }    

    /**
     * disable caching of the output
     *
     */
    function disableCache() {
        $this->_caching = false;               
    }    
        
    /**
     * set the time-to-live for cached output
     *
     * @param integer $ttl number of seconds to live
     */
    function setCacheTTL($ttl) {
        if(!preg_match('!^\d+$!',$ttl) || (int) $ttl <= 0)
            $this->_trigger_error("setCacheTTL: ttl must be an positive integer value");
        else
            $this->_cache_ttl = (int) $ttl;
    }

    /**
     * set the id for cached output
     *
     * @param string $id value of cache id
     */
    function setCacheID($id) {
        if(strlen($id) < 1 || strlen($id) > 32)
            $this->_trigger_error("setCacheID: ID must be between 1 and 32 characters");
        else
            $this->_cache_id = $id;
    }

    /**
     * set the directory for cached output generated files
     *
     * @param string $dir directory path
     */
    function setCacheDir($dir) {
        if(strlen($dir) == 0 || !file_exists($dir) || !is_dir($dir) || !is_writable($dir))
            $this->_trigger_error("setCacheDir: '$dir' is not a writable directory");
        else        
            $this->_cache_dir = $dir;
    }

    /**
     * set the date format used for elements of type 'date'
     *
     * @param string $format PHP date() format
     */
    function setDateFormat($format) {
        if(strlen($format) == 0)
            $this->_trigger_error("setDateFormat: value cannot be empty");
        else        
            $this->_date_format = $format;
    }    

    /**
     * enable caching of the output
     *
     */
    function enableHTTPHeaders() {
        $this->_send_http_headers = true;
    }    

    /**
     * disable caching of the output
     *
     */
    function disableHTTPHeaders() {
        $this->_send_http_headers = false;               
    }    

    /**
     * enable empty element tags
     *
     */
    function enableEmptyElements() {
        $this->_empty_elements = true;
    }    

    /**
     * disable empty element tags
     *
     */
    function disableEmptyElements() {
        $this->_empty_elements = false;
    }    
        
    /**
     * add a namespace to the feed
     *
     * @var string $name namespace name
     * @var string $value namespace value
     */
    function addNamespace($name, $value) {
        if(strlen($name) == 0)
            $this->_trigger_error("addNameSpace: name cannot be empty");
        elseif(strlen($value) == 0)
            $this->_trigger_error("addNameSpace: value cannot be empty");
        else
            $this->_namespace[$name] = $value;
    }

    /**
     * set stylesheet for the feed
     *
     * @var string $locationstylesheet location
     * @var string $text stylesheet type xsl/css
     */
    function setStylesheet($location, $type = 'xsl') {
        if(strlen($location) == 0)
            $this->_trigger_error("setStyleSheet: location cannot be empty");
        elseif(!in_array($type, array('xsl','css')))
            $this->_trigger_error("setStyleSheet: type must be one of 'xsl' or 'css'");
        else {
            $push = array( 'location' => $location, 'type' => $type); 
            array_push($this->_stylesheet, $push);
        }
    }
                    
    /**
     * return the generated output
     *
     */
    function fetch() {
        if(!$this->_caching) {
            return $this->_generate();
        } else {
            $_cache_file = $this->_cache_dir . '/ContentFeeder_' . urlencode($this->_cache_id) . '.xml';
            if(file_exists($_cache_file) && time() - filemtime($_cache_file) <= $this->_cache_ttl) {
                // send cached copy
                if($this->_send_http_headers) {
		            header('Content-Type: ' . $this->_content_type . '; charset=' . $this->_charset . '; filename=' . basename($_cache_file));
                    header('Content-Disposition: inline; filename=' . basename($_cache_file));
                }
                readfile($_cache_file);
            } else {
                // generate new copy, write cache
                $_output = $this->_generate();
                echo $_output;
                if($_fp = fopen($_cache_file, 'w')) {
                    fwrite($_fp, $_output);
                    fclose($_fp);
                }
            }
        }
    }
    
    /**
     * echo the generated output
     *
     */
    function display() {
        if($this->_send_http_headers)
		    header('Content-Type: ' . $this->_content_type . '; charset=' . $this->_charset);
        echo $this->fetch();
    }
    
    /**
     * escape the output suitable for xml
     *
     * @param string $value string to be escaped
     */
    function _escape($string, $format = null) {
        
        $_format = isset($format) ? $format : $this->_default_escape_type;
        
        switch($_format) {
            case 'html':
                return htmlentities($string, ENT_QUOTES);
                break;
            case 'xml':
                // remove invalid unicode characters
                // http://www.w3.org/TR/2000/REC-xml-20001006#charsets
                if($this->_charset == 'ISO-8859-1') {
                    $string = preg_replace('![^\s\x9\xA\xD\x20-\xFF]!','',$string);
                } else {
                    // utf-8
                    $string = preg_replace('![^\s\x9\xA\xD\x20-\xD7FF\xE000-\xFFFD\x10000\x10FFFF]!u','',$string);
                }
                // unicode < > & and any character outside of x20 - x7E
                return preg_replace('~([^\x20-\x25\x27-\x3b\x3d\x3f-\x7e])~se', "'&#' . ord('\\1') . ';'", $string);
                break;
            case 'none':
                return $string;
                break;
            default:
            case 'cdata':
                return '<![CDATA[' . $string . ']]>';
                break;
        }
    }

    /**
     * return string of indent characters
     *
     * @param integer $depth number of times to repeat indention chars
     */
    function _indent($depth = null) {        
        return str_repeat($this->_indent_char, $depth);   
    }    
    
    /**
     * convert a date into RFC 2822 format
     * use current date for non-determinate values
     *
     * @param string $value date to convert
     * @param string $format PHP date() format
     */
    function _render_date($value = null, $format = null) {
        
        $_format = isset($format) ? $format : $this->_date_format;
        
        if(!isset($value)) {
            $_time = time();
        } elseif(preg_match('!^-?\d+$!', $value)) {
            // already unix timestamp
            $_time = (int) $value;
        } else {
            // generate timestamp
            $_time = strtotime($value);
            if($_time == -1)
                // could not parse, set to current time (?)
                $_time = time();
        }
        return date($_format, $_time);
    }

    /**
     * strip HTML tags from a string of text
     *
     * @param string $value string to strip
     * @param string $excluded_tags list of tags to exclude from strip
     */
    function _strip_tags($value, $exluded_tags) {
        return strip_tags($value, $excluded_tags);   
    }

    /**
     * truncate string of text
     *
     * @param string $value string to truncate
     * @param integer $length length of characters to truncate to
     * @param integer $suffix string of characters to append to string
     * @param boolean $word_boundary truncate at word boundary true/false
     */
    function _truncate($value, $length, $suffix, $word_boundary) {
        if($word_boundary)
            return preg_replace('!\s+?(\S+)?$!', '', substr($value, 0, $length - strlen($suffix) + 1)) . $suffix;   
        else
            return substr($value, 0, $length - strlen($suffix)) . $suffix;   
    }    
    
    /**
     * trigger error message
     *
     * @param string $error_msg error message to show
     */
    function _trigger_error($error_msg) {
        trigger_error($error_msg);   
    }
    
    /**
     * function that does the generation,
     * will be overloaded by extended class
     */
    function _generate() { }

}

class ContentFeeder_RSS2 extends ContentFeeder {

    /**
     * id used for cache file
     *
     * @var string
     */    
    var $_cache_id = 'RSS2';

    /**
     * content element container
     *
     * @var array
     */
    var $_elements = array(
            'title' => array(),
            'link' => array(),
            'description' => array(),
            'language' => array(),
            'copyright' => array(),
            'managingEditor' => array(),
            'webMaster' => array(),
            'pubDate' => array('type' => 'date'),
            'lastBuildDate' => array('type' => 'date'),
            'category' => array(),
            'generator' => array(),
            'docs' => array(),
            'cloud' => array(),
            'ttl' => array(),
            'image' => array(),
            'rating' => array(),
            'textInput' => array(),
            'skipHours' => array(),
            'skipDays' => array(),
            'item' => array()
            );

    /**#@-*/
    /**
     * The class constructor.
     *
     * @var string $charset the character set of the output
     */
    function ContentFeeder_RSS2($charset = null) {
        $this->ContentFeeder();
        if(isset($charset))
            $this->setCharset($charset);
        $this->addNamespace('_default', 'http://backend.userland.com/rss2');
        $this->setElement('generator', $this->_version);
        $this->_elements['pubDate']['value'] = $this->_render_date();
    }
        
    /**
     * set the image definitions to the feed
     *
     * @var object $value image object
     */
    function setImage(&$value) {
        if(!is_object($value))
            $this->_trigger_error("setImage: value must be an object");
        else            
            $this->_elements['image']['value'] = $value;
    }
    
    /**
     * set the text input definitions to the feed
     *
     * @var object $value text input object
     */
    function setTextInput(&$value) {
        if(!is_object($value))
            $this->_trigger_error("setTextInput: value must be an object");
        else            
            $this->_elements['textInput']['value'] = $value; 
    }

    /**
     * add an item to the feed
     *
     * @var object $value item object
     */
    function addItem(&$value) {
        if(!is_object($value))
            $this->_trigger_error("addItem: value must be an object");
        else            
            $this->_elements['item']['value'][] = $value;
    }
 
    /**
     * generate the feed elements output
     *
     * @var array $elements the element array
     * @var integer $depth the indent depth to start rendering from
     */
    function _render_elements($elements, $depth) {
		$_output = null;
        foreach($elements as $_element_key => $_element_array) {
            
            // don't display empty elements
            if(empty($_element_array) && !$this->_empty_elements)
                continue;
            
            // render attributes for this element
            $_attrs = !empty($_element_array['attrs'])
                    ? $this->_render_attrs($_element_array['attrs'], $depth+1)
                    : '';
            if( isset($_element_array['value']) && is_object($_element_array['value'])) {
                // render object as separate element
                $_output .= $this->_indent($depth) . '<' . $_element_key;
                $_output .= $_attrs;
                $_output .= ">\n";
                $_output .= $this->_render_elements($_element_array['value']->_elements, $depth+1);
                $_output .= $this->_indent($depth) . '</' . $_element_key . ">\n";
            } elseif(isset($_element_array['value']) && is_array($_element_array['value'])) {
                // array of objects, render each one as separate element
                foreach($_element_array['value'] as $_element_array_objs) {
                    $_output .= $this->_indent($depth) . '<' . $_element_key;
                    $_output .= $_attrs;
                    $_output .= ">\n";
                    $_output .= $this->_render_elements($_element_array_objs->_elements, $depth+1);
                    $_output .= $this->_indent($depth) . '</' . $_element_key . ">\n";
                }
            } elseif(isset($_element_array['value']) && $_element_array['value'] == '') {
                // empty content, show only if attributes are set
                if(strlen($_attrs) > 0) {
                    $_output .= $this->_indent($depth) . '<' . $_element_key;
                    $_output .= $_attrs;
                    $_output .= "/>\n";
                }
            } else {
                // element has a value
                if(isset($_element_array['type']) && $_element_array['type'] == 'date') {
                    // format the date string
                    if(isset($_element_array['date_format']))
                        $_element_array['value'] = isset($_element_array['value']) ? $this->_render_date($_element_array['value'], $_element_array['date_format']) : null;
                    else
                        $_element_array['value'] = isset($_element_array['value']) ? $this->_render_date($_element_array['value']) : null;
                }
                if(isset($_element_array['strip_tags']) && $_element_array['strip_tags']) {
                    // strip html tags
                    $_element_array['value'] = $this->_strip_tags($_element_array['value'], $_element_array['strip_tags_excluded_tags']);
                }
                if(isset($_element_array['truncate']) && $_element_array['truncate']) {
                    // truncate text
                    $_element_array['value'] = $this->_truncate(
                            $_element_array['value'],
                            $_element_array['truncate_length'],
                            $_element_array['truncate_suffix'],
                            $_element_array['truncate_word_boundary']);
                }
                
                $_output .= $this->_indent($depth) . '<' . $_element_key;
                $_output .= $_attrs;
                $_output .= ">";
                if(!isset($_element_array['escape']) || $_element_array['escape']) {
					$value = isset($_element_array['value']) ? $_element_array['value'] : null;
                    $_output .= $this->_escape($value, isset($_element_array['escape_type']) ? $_element_array['escape_type'] : null);
                } else {
                    $_output .= $_element_array['value'];
				}
                $_output .= '</' . $_element_key . ">\n";
            }
        }
        return $_output;
    }
    
    /**
     * render the attributes for an element
     *
     * @var array $attrs the attribute array
     * @var integer $depth the indent depth to start rendering from
     */
    function _render_attrs($attrs, $depth) {
		$_output = null;
        foreach($attrs as $_attr_key => $_attr_value) {
            $_output .= "\n"
                . $this->_indent($depth)
                . $_attr_key
                . '="'
                . $this->_escape($_attr_value)
                . '"';
        }
        return $_output;
    }
        
    /**
     * create the output for the feed
     *
     */
    function _generate() {
        
        // build header
        $_output = '<?xml version="1.0" encoding="' . $this->_charset . '"?>' . "\n";
        foreach($this->_stylesheet as $_stylesheet) {
            switch($_stylesheet['type']) {
                case 'css':
                    $_output .= '<?xml-stylesheet href="' . $_stylesheet['location'] . '" type="text/css"?>' . "\n";
                    break;
                case 'xsl':
                default:   
                    $_output .= '<?xml-stylesheet href="' . $_stylesheet['location'] . '" type="text/xsl"?>' . "\n";                            
                    break;
            }   
        }   
        $_output .= '<rss version="2.0"';
        if(!empty($this->_namespace)) {
            foreach($this->_namespace as $_ns_key => $_ns_value) {
                if($_ns_key == '_default')                    
                    $_output .= "\n" . $this->_indent(1) . "xmlns=\"" . $_ns_value . '"';
                else
                    $_output .= "\n" . $this->_indent(1) . "xmlns:$_ns_key=\"" . $_ns_value . '"';
            }
        }
        $_output .= ">\n";
        
        // open channel
        $_output .= $this->_indent(1) . "<channel>\n";
        
        // render elements
        $_output .= $this->_render_elements($this->_elements, 2);
        
        // close channel
        $_output .= $this->_indent(1) . "</channel>\n";

        // build footer
        $_output .= "</rss>\n";

        return $_output;
        
    }
          
}

class ContentFeederImage extends ContentFeeder {

    /**
     * content element container
     *
     * @var array
     */
    var $_elements = array(
        'url' => array(),
        'title' => array(),
        'link' => array(),                    
        'width' => array(),
        'height' => array(),
        'description' => array()
    );    
        
    /**#@-*/
    /**
     * The class constructor.
     *
     * @var string $charset the character set of the output
     */
    function ContentFeederImage($charset = null) {
        if(isset($charset))
            $this->setCharset($charset);
    }
}

class ContentFeederItem extends ContentFeeder {

    /**
     * content element container
     *
     * @var array
     */
    var $_elements = array(
        'title' => array(),
        'link' => array(),
        'description' => array(),                    
        'author' => array(),
        'category' => array(),
        'comments' => array(),
        'enclosure' => array(),
        'guid' => array(),
        'pubDate' => array(),
        'source' => array()
    );

    /**#@-*/
    /**
     * The class constructor.
     *
     * @var string $charset the character set of the output
     */
    function ContentFeederItem($charset = null) {
        if(isset($charset))
            $this->setCharset($charset);
    }
}

class ContentFeederTextInput extends ContentFeeder {

    /**
     * content element container
     *
     * @var array
     */
    var $_elements = array(
        'title' => array(),
        'description' => array(),
        'name' => array(),
        'link' => array()      
    );
        
    /**#@-*/
    /**
     * The class constructor.
     *
     * @var string $charset the character set of the output
     */
    function ContentFeederTextInput($charset = null) {
        if(isset($charset))
            $this->setCharset($charset);
    }
}


?>
