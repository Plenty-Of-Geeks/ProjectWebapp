<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Account extends Application
{
    /* Constructor */
    public function __construct() 
    {
        parent::__construct();
        
        $this->load->helper('formfields');
        $this->load->model('posts');
    }
	
     public function index()
    {        
        $this->data['pagebody'] = 'account';

        
        
      
        $this->render();
    }
    
    
    
    
    public function get_all_posts()
    {
         $this->data['pagebody'] = 'account_get_all_post';
         
     
        $result = $this->posts->get_all_post_by_poster_id($_SESSION['user_id']);
        
        $this->data['posts'] = $result;      
        
        
        
       $this->data['display_all_post'] = $this->parser->parse('_latestposts', $this->data, true);
     $this->render();
        
        
    }
    
    public function get_teams()
    {
        
    }
}

