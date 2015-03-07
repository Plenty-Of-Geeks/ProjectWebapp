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
        $this->load->model('team_members');
        $this->load->helpers('formfields');
        $this->load->library('form_validation');
    }
    
    public function index()
    {
        $this->data['pagebody'] = 'post';

        /* Get Latest Posts */
        if (isset($_SESSION['user_id']))
        {
            $this->data['posts'] = $this->posts->get_all_posts($_SESSION['user_id']);
        }
        else
        {
            $this->data['posts'] = $this->posts->get_all_posts();
        }


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
    
    public function create_post()
    {
        if (!isset($_SESSION['user_id']))
        {
            redirect('../SignIn');
        }
        
        $this->data['pagebody'] = 'createpost';
 
        $this->setup_post_input_fields();
        
        $this->setup_post_error_message();

        $this->setup_join_error_message();
        
        $this->data['fsubmit'] = makeSubmitButton( 
                'Add Post', 
                "Click here to validate the post data", 
                'btn-success'); 
        
        $this->render();
    }
    
    public function setup_post_input_fields()
    {
        /* Restore previous session */
        $post = $this->restore_post_session($this->posts->create());
        $team = $this->restore_team_session($this->teams->create());
            
        $this->data['title']   = makeTextField('Title *', 'title', $post->title); 
        $this->data['content'] = $this->data['content'] = makeTextArea('Content*', 'content', $post->content, "", 100, 25, 5, false);
        $this->data['team_name'] = makeTextField('Team Name *', 'team_name', $team->team_name);
        $this->data['max_team_count'] = makeTextField('Max Team Members *', 'max_team_count', $team->max_team_count);
    }
    
    public function restore_post_session($post)
    {
        if (isset($_SESSION['post_title']))
        {
            $post->title = $_SESSION['post_title'];
        }
        if (isset($_SESSION['post_content']))
        {
            $post->content = $_SESSION['post_content'];
        }

        return $post;
    }
    
    public function restore_team_session($team)
    {
        if (isset($_SESSION['post_team_name']))
        {
            $team->team_name = $_SESSION['post_team_name'];
        }
        if (isset($_SESSION['post_max_team_count']))
        {
            $team->max_team_count = $_SESSION['post_max_team_count'];
        }
        
        return $team;
    }
    
    public function setup_post_error_message()
    {
        if (isset($_SESSION['create_post_error']))
        {
            $this->data['error_message'] = $_SESSION['create_post_error'];
            unset($_SESSION['create_post_error']);
        }
        else
        {
            $this->data['error_message'] = '';
        }
    }
    
    public function submit_post()
    {
        $this->create_post_validation();
        
        $team = $this->create_team_record();
        
        $this->create_team_member($team);
        
        $this->create_post_record($team);

        $this->cleanup_post_session();
        
        redirect('/Post');
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
            $_SESSION['post_title'] = $this->input->post('title');
            $_SESSION['post_content'] = $this->input->post('content');
            $_SESSION['post_team_name'] = $this->input->post('team_name');
            $_SESSION['post_max_team_count'] = $this->input->post('max_team_count');
            redirect('../Post/create_post');
        }
        
        $this->form_validation->set_rules('max_team_count', 'Max Team Count', 'numeric');
        if ($this->form_validation->run() == false)
        {
            $_SESSION['create_post_error'] = 'Max Team Count must be numeric.';
            $_SESSION['post_title'] = $this->input->post('title');
            $_SESSION['post_content'] = $this->input->post('content');
            $_SESSION['post_team_name'] = $this->input->post('team_name');
            $_SESSION['post_max_team_count'] = $this->input->post('max_team_count');
            redirect('../Post/create_post');
        }
    }
    
    /* Creates and returns the team record */
    public function create_team_record()
    {
        $team = $this->teams->create();        
        $team->team_name = $this->input->post('team_name');
        $team->max_team_count = $this->input->post('max_team_count');
        $team->team_count = 1;
        
        
        if (empty($team->team_id))
        {
            $this->teams->add($team);
        }
        else
        {
            $this->teams->update($team);
        }
        
        $team->team_id = $this->db->insert_id();
        
        return $team;
    }
    
    public function create_team_member($team)
    {
        $team_member = $this->team_members->create();
        $team_member->user_id = $_SESSION['user_id'];
        $team_member->team_id = $team->team_id;
        $this->team_members->add($team_member);
    }
    
    public function create_post_record($team)
    {
        /* Get Team ID */
        $team_id = $team->team_id;
        
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
    
    public function cleanup_post_session()
    {
        unset($_SESSION['post_title']);
        unset($_SESSION['post_content']);
        unset($_SESSION['post_team_name']);
        unset($_SESSION['post_max_team_count']);
    }
    
    public function comment()
    {
        /* Get Selected Posts */
        $_SESSION['currentPost'] = $this->input->post('postId');
        
        redirect('../Post/showPost');

    }
    
    public function showPost()
    {
        //Set up the base pagebody
        $this->data['pagebody'] = 'show_post';
        //Create a new row for posts
        $comment = $this->comments->create();
        
        
        $this->data['comments'] = $this->comments->get_where('post_id', $_SESSION['currentPost']);
        
        $sourcePost = $this->posts->get_full($_SESSION['currentPost']);
        
        //print_r($sourcePost);
        //print_r($this->data['comments']);
        
        //Fill the form data for the comment box
        if(isset($_SESSION['user_id']))
        {
            $this->data['title']   = makeTextField('Title', 'title', $comment->title); 
            $this->data['content'] = makeTextArea('Comment', 'content', $comment->content, "", 100, 25, 5, false);
        $this->data['fsubmit'] = makeSubmitButton(  
                'Add Comment', 
                "Click here to validate the post data", 
                'btn-success button');
        }
        else
        {
            $this->data['title']   = ""; 
            $this->data['content'] = "";
            $this->data['fsubmit'] = makeSubmitButton(  
                'SignIn', 
                "Click here to validate the post data", 
                'btn-success button');
        }
        //Load the various view fragments
        $this->data['postInfo'] = $this->parser->parse('_justone', $sourcePost, true);
        $this->data['newComment'] = $this->parser->parse('createcomment', $this->data, true);
        $this->data['commentsBox'] = $this->parser->parse('commentsbox', $this->data, true);
        
        
        
        
        $this->render();
    }
    
    public function postComment()
    {
        
        if(!isset($_SESSION['user_id'])) redirect("../SignIn");
        
        $record = $this->comments->create();
        
        // Extract submitted fields
        $record->post_id = $_SESSION['currentPost'];
        $record->title   = $this->input->post('title');
        $record->content = $this->input->post('content');
        $record->poster_id = $_SESSION['user_id'];
        

        $this->data['latestposts'] = $this->parser->parse('_latestposts', $this->data, true);
        
        // Save stuff
        if (empty($record->comment_id)) $this->comments->add($record); 
        else $this->comments->update($record); 
        
        redirect('../Post/showPost');
    }
        
    public function join_team()
    {    
        if (!isset($_SESSION['user_id']))
        {
            redirect('../SignIn');
        }
        
        $team_id = $this->input->post('teamId');
        
        $team_member = $this->team_members->create();
        $team_member->team_id = $team_id;
        $team_member->user_id = $_SESSION['user_id'];
        $this->team_members->add($team_member);
        
        /* Update Team Record */
        $team_record = $this->teams->get($team_id);
        $team_record->team_count = $team_record->team_count + 1;
        $this->teams->update($team_record);
        
        redirect('../Post');
    }
    
    public function setup_join_error_message()
    {      
        if(isset($_SESSION['join_error_message']))
        {
            $this->data['join_error_message'] = $_SESSION['join_error_message'];         
            unset($_SESSION['join_error_message']);
        }
        else
        {
            $this->data['join_error_message'] = '';
        }
        $this->data['join_error_message'] = 3;
    }
}