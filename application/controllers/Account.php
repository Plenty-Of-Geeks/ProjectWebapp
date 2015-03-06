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

        
        
        
        /**
        $user = $this->users->create();
        
        $this->data['username'] = makeTextField('Username', 'username', $user->username);
        $this->data['password'] = makeTextField('Password', 'password', $user->password);
        $this->data['password2'] = makeTextField('Password', 'password2', $user->password);
        $this->data['email'] = makeTextField('Email', 'email', $user->email);
        
        $this->data['submit'] = makeSubmitButton( 
                'Sign Up', 
                "Click here to validate the user data", 
                'btn-success'); 
        **/
        $this->render();
    }
    
    
    
    
    public function get_all_posts()
    {
         $this->data['pagebody'] = 'account_get_all_post';
         
     
        $result = $this->posts->some('poster_id',$_SESSION['user_id']);//get_where('poster_id',$_SESSION['user_id']);
        
        
        print_r($_SESSION['user_id']);
        print_r($result);
        
        
        
       $this->data['display_all_post'] = $this->parser->parse('_latestposts', $this->data, true);
     $this->render();
        
        
    }
    
    public function get_teams()
    {
        
    }
}

