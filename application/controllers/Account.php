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
        //Reset the edit_profile_username session data
        unset($_SESSION['edit_profile_username']);
        
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
                <a href="/Account/edit/"> Click here to change your password </a>';
            $this->data['delete'] = 
                '<br/>
                 <a href="/Account/delete/"> Click here to delete your profile </a>';
            $_SESSION['edit_profile_username'] = $username;
            
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
    
    //Deleting profiles
    public function delete()
    {       
        //Security redirect
        if(!isset($_SESSION['edit_profile_username']))
            redirect('../');
     
        //Setting pagebody
        $this->data['pagebody'] = 'account_delete';
        
        //Setting page data
        $this->data['yes'] = '<div class="button"> <a href="../confirm_delete">Yes</a></div>';
        $this->data['no'] = '<div class="button"> <a href="../profile/'.$_SESSION['edit_profile_username'].'">No</a></div>';
        
        $this->render();
        
    }
    
    /** Confirmed deletion of account **/
    public function confirm_delete()
    {
        //Setting page body
        $this->data['pagebody'] = 'account_delete';
        
        //loading all the models needed
        $this->load->model('team_members');
        
        $this->load->model('teams');
                
        //Getting user_id from username
        $user_id = $this->users->get_by_username($_SESSION['edit_profile_username'])->user_id;
        
        //Getting post_id
        $post_ids = $this->posts->some('poster_id',$user_id);
        
        //Deleting all posts
        foreach($post_ids as $post_id)
        {
            $this->db->delete('posts',array('post_id' => $post_id->post_id));
        }
        
        //Deleting the user
        $this->users->delete($user_id);                       
        
        //Unsetting session variables
        unset($_SESSION['edit_profile_username']);        
        unset($_SESSION['username']);
        unset($_SESSION['user_id']);
        unset($_SESSION['admin']);
        
        redirect('../');
    }
    
    
    /** Editing your account details **/
    public function edit()
    {
        //Security redirect
        if(!isset($_SESSION['edit_profile_username']))
            redirect('../');
        
        //Loading button helper
        $this->load->helper('button');
                
        //Pagebody
        $this->data['pagebody'] = 'account_edit';     
        
        //Getting User's info from User table
        $query = $this->users->get_by_username($_SESSION['edit_profile_username']);
        
        //Setting data to empty strings
        $this->data['form_username'] = '';
        $this->data['form_email'] = '';
        $this->data['form_password'] = '';
        $this->data['save'] = '';
        $this->data['cancel'] = '';
        $this->data['form_confirm_password'] = '';
        
        //Set the username Textbox to edit
        //$this->data['form_username'] = makeTextField('User Name','username',$query->username );
     

        //Set the password Textbox to edit
        $this->data['form_password'] = makePasswordField('New Password', 'password', '*****');
        
        //Set the password confirmation Textbox to edit
        $this->data['form_confirm_password'] = makePasswordField('Confirm New Password', 'confirm_password', '*****');
        
        //Making the submit button
        $this->data['save'] = makeSubmitButton('Save', 'save_button', 'button floatLeft');

        //Making the cancel button
        $this->data['cancel'] = '<div class="button"> '
                . '<a href="../profile/'.$_SESSION['edit_profile_username'].'">Cancel</a></div>';
        
        $this->render();
    }
    
    public function edit_save()
    {
        //Safety check if someone is directly typing it into URL
        if(!isset($_SESSION['edit_profile_username']))
            redirect('../');


        //Setting pagebody
        $this->data['pagebody'] = 'account_edit_save';

        //Getting the variables from the form submit
        $username = $_SESSION['edit_profile_username'];
        $email = $this->input->post('email');
        $password = $this->input->post('password');
        $password_confirm = $this->input->post('confirm_password');

        //Setting view outputs
        $this->data['username'] = '';
        $this->data['email'] = '';
        $this->data['password'] = '';
        $this->data['password_confirm'] = '';
        $this->data['result'] = '';

        //Getting User's info from User table
        $query = $this->users->get_by_username($username);

        
        if($this->password_validataion($password, $password_confirm))
        {
            $this->data['result'] = 'Password changed';
            $query->password = $password;
            $this->users->update($query);

        }
        else
        {
            $this->data['result'] = 'The password did not match. Password was not changed';
        }


        //unset Session variable
       unset($_SESSION['edit_profile_username']);
        $this->render();
    }   


    private function password_validataion($password, $password_confirm)
    {
        //Checking if equal and not empty string
        if(strcmp($password, $password_confirm) == 0)
        {
            return TRUE;
        }
        else if(strcmp($password,'') == 0 || strcmp($password_confirm,'') == 0)
        {
            return False;
        }  else 
        {
            return false;
        }
    }
}