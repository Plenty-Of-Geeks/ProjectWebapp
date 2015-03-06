<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class SignUp extends Application
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
        $this->data['pagebody'] = 'signup';
        
        $user = $this->users->create();
        
        if (isset($_SESSION['signup_error']))
        {
            $this->data['message'] = 'User already exists';
        }
        else
        {
            $this->data['message'] = '';
        }
        
        $this->data['username'] = makeTextField('Username', 'username', $user->username);
        $this->data['password'] = makeTextField('Password', 'password', $user->password);
        $this->data['password2'] = makeTextField('Password', 'password2', $user->password);
        $this->data['email'] = makeTextField('Email', 'email', $user->email);
        
        $this->data['submit'] = makeSubmitButton( 
                'Sign Up', 
                "Click here to validate the user data", 
                'btn-success'); 
        
        $this->render();
    }
    
    public function confirm()
    {
        $record = $this->users->create();
        // Extract submitted fields
        $record->username = $this->input->post('username');
        $record->password = $this->input->post('password');
        $record->email = $this->input->post('email');
        
        
        //Check to see if this username already exists
        $users = $this->users->some('username', $this->input->post('username'));
        foreach ($users as $user)
        {
            $_SESSION['signup_error'] = 'username taken';
            redirect('/SignUp');
        }
        
        // Save stuff
        if (empty($record->user_id))
        {
            $this->users->add($record);
        }
        else
        {
            $this->users->update($record);
        }
        
        unset($_SESSION['signup_taken']);
        
        redirect('/Post');
    }
    
    
}
