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
	
    /** The index. This will only get called when user clicks the menu "account" tab **/
    /** Which means the user is going straight into their account **/
     public function index()
    {          
       redirect('../Account/profile/'.$_SESSION['username']);
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
        //Will add an extra option if user can edit
        $this->data['edit'] = '';
        
        //Can the user accessing this page edit the profile?
        $can_edit = false;
        
        //If either it's an admin or own account, then can edit
        if(isset($_SESSION['admin']) && $_SESSION['admin'] )
            $can_edit = true;
        if(isset($_SESSION['username']) && $_SESSION['username'] == $username)
            $can_edit = true;
                      
        //Logic to see if you can edit the profile
        if($can_edit)
        {
            $this->data['edit'] = 
                '<br/>
                <a href="/Account/edit"> Click here to edit your profile </a>';
        }else
        {
            //It's already set to empty string already
        }
        
        
        //query will hold the selected profile id
        $query = $this->users->get_by_username($username);
        
        print_r($query);
        $this->data['profile_pic'] = "<img src='"  .base_url() . $query->profile_picture."'>";      
               
        $this->data['username'] = $query->username;
        $this->data['email'] = $query->email;

        $this->render();
    }
    
    /** Gets all post from a general user **/
    public function show_posts($username)
    {
        //Page body
        $this->data['pagebody'] = 'account_get_all_post';
        //Result from getting te user from posts table
        $result = $this->posts->get_all_post_by_username($username);
        //What is shown in the view
        $this->data['display_all_post'] = '';
        
       $this->data['posts'] = $result;       
       
       if(count($result) == 0)
       {
           $this->data['display_all_post'] = '<strong> You have no posts. </strong>';
       }else
       {
        $this->data['display_all_post'] = $this->parser->parse('_latestposts', $this->data, true);               
       }
       
       $this->render();
    }
    
    /** Editing your account details **/
    public function edit()
    {
        //Pagebody
        $this->data['pagebody'] = 'account_edit';
        
        $max_username_length = $this->users->max_length('username');
        print_r($max_username_length);
        //Getting User's info from User table
        $query = $this->users->get_by_username($_SESSION['username']);
        
        //Set the Textbox to edit
        $this->data['username'] = makeTextField('User Name','username',$query->username );
        
        $this->render();
    }
}

