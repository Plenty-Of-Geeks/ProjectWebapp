<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class CreatePost extends Application
{
    public function __construct() 
    {
        parent::__construct();
        $this->load->helper('formfields');
        $this->load->model('posts');
        $this->load->model('teams');
        
        if(!isset($_SESSION['user_id']))
        {
            redirect('/SignIn');
        }
    }
    
    public function index()
    {
        $this->data['pagebody'] = 'createpost';

        $post = $this->posts->create();
        $team = $this->teams->create();
        $this->data['title']   = makeTextField('Title', 'title', $post->title); 
        $this->data['content'] = makeTextField('Content', 'content', $post->content);
        $this->data['team_name'] = makeTextField('Team Name', 'team_name', $team->team_name);
        $this->data['max_team_count'] = makeTextField('Max Team Members', 'max_team_count', $team->max_team_count);
        
        $this->data['fsubmit'] = makeSubmitButton( 
                'Add Post', 
                "Click here to validate the post data", 
                'btn-success'); 
        
        $this->render();
    }
    
    public function confirm()
    {
        $team = $this->teams->create();
        $team->team_name = $this->input->post('team_name');
        $team->max_team_count = $this->input->post('max_team_count');
        $team->team_count = 1;
        $team->user_id1 = $_SESSION['user_id'];
        
        if (empty($team->team_id))
        {
            $this->teams->add($team);
        }
        else
        {
            $this->teams->update($team);
        }
        
        /* Get Team ID */
        $team_id = $this->teams->get_record('team_name', $team->team_name)->team_id;
        
        $post = $this->posts->create();
        // Extract submitted fields
        $post->title = $this->input->post('title');
        $post->content = $this->input->post('content');
        $post->poster_id = $_SESSION['user_id'];
        $post->team_id = $team_id;
        
        // Save stuff
        if (empty($post->post_id))
        {
            $this->posts->add($post);
        }
        else
        {
            $this->posts->update($post);
        }
        
        redirect('/Post');
    }
}
