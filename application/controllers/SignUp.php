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
        $this->load->library('form_validation');
    }
    
    public function index()
    {        
        $this->data['pagebody'] = 'signup';
        
        if (isset($_SESSION['signup_error']))
        {
            $this->data['message'] = $_SESSION['signup_error'];
        }
        else
        {
            $this->data['message'] = '';
        }
        
        $user = $this->restore_signup_session();
        
        $this->data['username'] = makeTextField('Username', 'username', $user->username);
        $this->data['password'] = makePasswordField('Password', 'password', '');
        $this->data['password2'] = makePasswordField('Password', 'password2', '');
        $this->data['email'] = makeTextField('Email', 'email', $user->email);
        
        $this->data['submit'] = makeSubmitButton( 
                'Sign Up', 
                "Click here to validate the user data", 
                'btn-success'); 
        
        $this->render();
    }
    
    public function restore_signup_session()
    {
        $user = $this->users->create();
        
        $user->username = (isset($_SESSION['username'])) ? $_SESSION['username'] : '';   
        $user->email = (isset($_SESSION['email'])) ? $_SESSION['email'] : '';
        
        return $user;
    }
    
    public function confirm()
    {
        $this->signup_validation();
        
        $record = $this->users->create();
        // Extract submitted fields
        $record->username = $this->input->post('username');
        $record->password = $this->input->post('password');
        $record->email = $this->input->post('email');
        
        
        //Check to see if this username already exists
        $users = $this->users->some('username', $this->input->post('username'));
        foreach ($users as $user)
        {
            $_SESSION['signup_error'] = 'Username Taken';
            redirect('/SignUp');
        }
        
        $users2 = $this->users->some('email', $record->email);
        foreach ($users2 as $user)
        {
            $_SESSION['signup_error'] = 'Email Taken';
            redirect('SignUp');
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
        unset($_SESSION['username']);
        unset($_SESSION['password']);
        unset($_SESSION['password2']);
        unset($_SESSION['email']);
        
        redirect('/Post');
    }
    
    public function signup_validation()
    {
        /* Form Validation */
        $this->form_validation->set_rules('username', 'Username', 'required');
        $this->form_validation->set_rules('password', 'Password', 'required');
        $this->form_validation->set_rules('password2', 'Password', 'required');
        $this->form_validation->set_rules('email', 'Email', 'required');
        if ($this->form_validation->run() == false)
        {
            $_SESSION['signup_error'] = 'Missing Required Field.';
            $_SESSION['username'] = $this->input->post('username');
            $_SESSION['password'] = $this->input->post('password');
            $_SESSION['password2'] = $this->input->post('password2');
            $_SESSION['email'] = $this->input->post('email');
            redirect('../SignUp');
        }
    }
}
