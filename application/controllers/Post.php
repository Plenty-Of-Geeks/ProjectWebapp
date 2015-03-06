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
    }
    public function index()
    {
        $this->load->model('posts');
            $this->data['pagebody'] = 'post';

            /* Get Latest Posts */
            $this->data['posts'] = $this->posts->get_all_posts();


            //check to see if your an admin, if so load admin controls
            if (isset($_SESSION['admin']))
            {
                $this->data['latestposts'] = $this->parser->parse('_latestpostsadmin', $this->data, true);
            }
            else
            {
                $this->data['latestposts'] = $this->parser->parse('_latestposts', $this->data, true);
            }

            $this->render();
    }
    
    public function create()
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
    
    public function comment()
    {
        $this->data['pagebody'] = 'welcome';

        /* Get Selected Posts */
        $this->data['post_id'] = $this->input->post('postId');
        $sourcePosts = $this->posts->get($this->data['post_id']);
        
        //print_r($sourcePosts);
        //print_r($this->input->post('postId'));
        $this->load->helper('formfields');

        $comment = $this->comments->create();
        
        $this->data['title']   = makeTextField('Title', 'title', $comment->title); 
        $this->data['content'] = makeTextArea('Comment', 'content', $comment->content, "", -1, 25, 5, false);
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