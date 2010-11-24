<?php

class Feed extends Controller {

        function __construct() {
                parent::Controller();
                $this->load->helper('xml');
        }
    
        // format RSS2 blog feed 
        //
        function index() {
                $this->load->library('ContentFeeder');
                $feed = new ContentFeeder_RSS2;
        
                $feed->setStylesheet(''.base_url().'/css/style.css','css');
                $feed->addNamespace('dc', 'http://purl.org/dc/elements/1.1/');
    
                $feed->setElement('title', 'Fission Strategy RSS Feed');
                $feed->setElement('link', $this->config->item('base_url'));
                $feed->setElement('description', 'Fission Strategy');
                $feed->setElement('dc:author', 'by Cindy Mottershead');
                $feed->setElementAttr('enclosure', 'url', $this->config->item('base_url'));
                $feed->setElementAttr('enclosure', 'length', '1234');
                $feed->setElementAttr('enclosure', 'type', 'audio/mpeg');
    
                $image = new ContentFeederImage;
                $image->setElement('url', $this->config->item('base_url') . '/img/logo.gif');
                $image->setElement('title', 'Fission Strategy');
                $image->setElement('link', $this->config->item('base_url'));
                $feed->setImage($image);
    
                $this->db->select('*');
                $this->db->from('fs_blog');
                $this->db->where('status', 'publish');
                $this->db->order_by('date', 'DESC');
                $this->db->limit(10);
                $query = $this->db->get();
                foreach ($query->result() as $blog) {

                        $item = new ContentFeederItem;
                        $item->setElement('title', $blog->title);
                        $item->setElement('link', 'blog/' . $blog->name);
                        $item->setElement('description', $blog->content);
                        // ensure description does not conflict with XML
                        $item->setElementEscapeType('description', 'cdata');    
                        //            $item->setElement('author', $blog->author);
                        $item->setElement('author', 'Fission Strategy');
                        $item->setElement('category', '');
                        $item->setElement('comments', '');
                        $feed->addItem($item);
                }
    
                $feed->display();
        }

} 