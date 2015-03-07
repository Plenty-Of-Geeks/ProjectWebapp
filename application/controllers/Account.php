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
        $this->load->model('users');
      
    }
	
    /* This is ALWAYS going to be the logged in user's profile */
     public function index()
    {        
        $this->data['pagebody'] = 'account'; //Set default veiw to View/account.php      
        $allow_edit = false; //Boolean to check on privillges 
        
        //The profile pic (static for now, will make it dynamic later)
        $this->data['profile_pic'] = "<img src='../assets/images/profile_pic.png'>";      
        
        $query = $this->users->get($_SESSION['user_id']);        
        
        $this->data['username'] = $query->username;
        $this->data['email'] = $query->email;
        
        //if the clicked profile is the same as the user currently login in
        if(isset($_SESSION['selected_profile']) == isset($_SESSION['user_id']))
            $allow_edit = true;
             

        //Negatively checking
      //  if(!isset($_SESSION['user_id']) || $_SESSION['user_id'] != 
        
      
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
    
    
    public function profile()
    {
            $this->data['pagebody'] = 'profile';
        $_SESSION['selected_profile'] = $this->uri->segment(3);
    
        $this->render();
    }
    
    public function get_teams()
    {
        
    }
}

