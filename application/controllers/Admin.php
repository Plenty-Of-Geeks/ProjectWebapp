<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Admin extends Application
{
    /* Constructor */
    public function __construct() 
    {
        parent::__construct();
        $this->load->helper('formfields');
        $this->load->model('users');
        $this->load->model('posts');
        //$this->load->view('_latestpostsadmin');
    }
    
    public function index()
    {        
        
        $this->data['pagebody'] = 'admin';
        
        $this->data['username'] = makeTextField('Username', 'username', '');
        
        $this->data['search'] = makeSubmitButton( 
                'Search', 
                "Click here to search for user", 
                'btn-success'); 
         
        $this->render();
    }
    public function deletePost()
    {
        $this->posts->delete($this->input->post('postId'));
        redirect('/Post');
    }
    
    public function search()
    {
        $users = $this->users->some('username', $this->input->post('username'));
        foreach ($users as $user)
        {
            if ($user->username == $this->input->post('username'))
            {
                $_SESSION['username'] = $user->username;
                $_SESSION['user_id'] = $user->user_id;
                redirect('/Post');
            }
        }
       
        redirect('/Welcome');
    }
    
    public function logout()
    {
        unset($_SESSION['username']);
        unset($_SESSION['user_id']);
        redirect('/Welcome');
    }
}

