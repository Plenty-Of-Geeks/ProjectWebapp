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
        $this->data['profile_pic'] = "<img src='" . $this->users->get($_SESSION['user_id'])->profile_picture."'>";      
        
        $query = $this->users->get($_SESSION['user_id']);        
        print_r($query);
        
        $this->data['username'] = $query->username;
        $this->data['email'] = $query->email;
        
        //if the clicked profile is the same as the user currently login in
        if(isset($_SESSION['selected_profile']) == isset($_SESSION['user_id']))
            $allow_edit = true;
             

        //Negatively checking
      //  if(!isset($_SESSION['user_id']) || $_SESSION['user_id'] != 
        
      
        $this->render();
    }
    
    
    
    /** This is for YOUR account only **/
    public function get_all_posts()
    {
         $this->data['pagebody'] = 'account_get_all_post';
         
     
        $result = $this->posts->get_all_post_by_poster_id($_SESSION['user_id']);
        
        $this->data['posts'] = $result;      
        
        
        
       $this->data['display_all_post'] = $this->parser->parse('_latestposts', $this->data, true);
     $this->render();            
    }
    
    /*The profile page for a general profile*/
    public function profile($username)
    {
        //Set pagebody
        $this->data['pagebody'] = 'profile';
        
        //query will hold the selected profile id
        $query = $this->users->get_by_username($username);
        
        print_r($query);
        $this->data['profile_pic'] = "<img src='"  .base_url() . $query->profile_picture."'>";      
               
        $this->data['username'] = $query->username;
        $this->data['email'] = $query->email;
        
        
    
        print_r($_SESSION['selected_profile']);
        $this->render();
    }
    
    /** Gets all post from a general user **/
    public function show_posts($username)
    {
        $this->data['pagebody'] = 'account_get_all_post';
        
        $result = $this->posts->get_all_post_by_username($username);
        
       $this->data['posts'] = $result;                  
       $this->data['display_all_post'] = $this->parser->parse('_latestposts', $this->data, true);               
       $this->render();
    }
}

