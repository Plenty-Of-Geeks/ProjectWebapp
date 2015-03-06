<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class SignIn extends Application
{
    /* Constructor */
    public function __construct() 
    {
        parent::__construct();
        $this->load->helper('formfields');
        $this->load->model('users');
    }
    
    public function index()
    {        
        $this->data['pagebody'] = 'signin';
        
        $user = $this->users->create();
        
        $this->data['username'] = makeTextField('Username', 'username', $user->username);
        $this->data['password'] = makeTextField('Password', 'password', $user->password);
        
        $this->data['submit'] = makeSubmitButton( 
                'Sign In', 
                "Click here to validate the user data", 
                'btn-success'); 
         
        $this->render();
    }
    
    public function confirm()
    {
        $users = $this->users->some('username', $this->input->post('username'));
        foreach ($users as $user)
        {
            if ($user->password == $this->input->post('password'))
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

