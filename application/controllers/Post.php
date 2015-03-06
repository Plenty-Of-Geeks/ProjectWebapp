<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Post extends Application {
    
    function __construct()
    {
	parent::__construct();
        
        $this->load->model('posts');
        $this->load->model('comments');
        $this->load->model('teams');
        $this->load->helpers('formfields');
        $this->load->library('form_validation');
    }
    public function index()
    {
        $this->load->model('posts');
        $this->data['pagebody'] = 'post';

        /* Get Latest Posts */
        $this->data['posts'] = $this->posts->get_all_posts();

        $this->data['latestposts'] = $this->parser->parse('_latestposts', $this->data, true);

        $this->render();
    }
    
    public function create_post()
    {
        if (!isset($_SESSION['user_id']))
        {
            redirect('../SignIn');
        }
        
        $this->data['pagebody'] = 'createpost';
 
        /* Create Input Field */
        $post = $this->posts->create();
        $team = $this->teams->create();
        $this->data['title']   = makeTextField('Title *', 'title', $post->title); 
        $this->data['content'] = makeTextField('Content *', 'content', $post->content);
        $this->data['team_name'] = makeTextField('Team Name *', 'team_name', $team->team_name);
        $this->data['max_team_count'] = makeTextField('Max Team Members *', 'max_team_count', $team->max_team_count);
        
        if (isset($_SESSION['create_post_error']))
        {
            $this->data['error_message'] = $_SESSION['create_post_error'];
        }

        $this->data['fsubmit'] = makeSubmitButton( 
                'Add Post', 
                "Click here to validate the post data", 
                'btn-success'); 
        
        $this->render();
    }
    
    public function submit_post()
    {
        $this->create_post_validation();
        
        $this->create_team_record();
        
        $this->create_post_record();
        
        unset($_SESSION['create_post_error']);
        redirect('/Post');
    }
    
    public function create_team_record()
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
    }
    
    public function create_post_record()
    {
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
    }
    
    public function create_post_validation()
    {
        /* Form Validation */
        $this->form_validation->set_rules('title', 'Title', 'required');
        $this->form_validation->set_rules('content', 'Content', 'required');
        $this->form_validation->set_rules('team_name', 'Team Name', 'required');
        $this->form_validation->set_rules('max_team_count', 'Max Team Count', 'required');
        if ($this->form_validation->run() == false)
        {
            $_SESSION['create_post_error'] = 'Missing Required Field.';
            redirect('../Post/create_post');
        }
    }
    
    public function comment()
    {
        $this->data['pagebody'] = 'welcome';

        /* Get Selected Posts */
        $this->data['post_id'] = $this->input->post('postId');
        $sourcePosts = $this->posts->get($this->data['post_id']);
        
        //print_r($sourcePosts);
        
        $this->load->helper('formfields');

        $post = $this->posts->create();
        
        $this->data['title']   = makeTextField('Title', 'title', $post->title); 
        $this->data['content'] = makeTextArea('Comment', 'content', $post->content, "", -1, 25, 5, false);
        $this->data['fsubmit'] = makeSubmitButton( 
                'Add Comment', 
                "Click here to validate the post data", 
                'btn-success');
        
        $this->data['latestposts'] = $this->parser->parse('_justone', $sourcePosts, true);
        $this->data['latestposts'] .= $this->parser->parse('createcomment', $this->data, true);
        $this->render();
    }
    
    public function postComment()
    {
        $record = $this->comments->create();
        // Extract submitted fields
        $record->post_id = $this->input->post('postId');
        $record->title   = $this->input->post('title');
        $record->content = $this->input->post('content');
        // Save stuff
        if (empty($record->comment_id))
        {
            $this->comments->add($record);
        }
        else
        {
            $this->comments->update($record);
        }
        
        redirect('../Post');
        
    }
        
}