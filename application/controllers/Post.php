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
        $this->load->model('users');
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

        //unset sign up error
        unset($_SESSION['signup_error']);
        //unset sign in error
        unset($_SESSION['login_error']);
                
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
                'button'); 
        
        $this->render();
    }
    
    public function setup_post_input_fields()
    {
        /* Restore previous session */
        $post = $this->restore_post_session($this->posts->create());
        $team = $this->restore_team_session($this->teams->create());
            
        $this->data['title']   = makeTextField('Title *', 'title', $post->title); 
        $this->data['content'] = $this->data['content'] = makeTextArea('Content*', 'content', $post->content, "", 1000, 25, 5, false);
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
    
    public function showPost($currPost)
    {
       //Set up the base pagebody
        $this->data['pagebody'] = 'show_post';
        //Load helpers
        $this->load->helper('commentbox');
        $this->load->helper('button');
        $this->load->helper('ffields');
        
        //initialize information of the current post
        $allComments = $this->comments->some('post_id', $currPost);
        $currPostInfo = $this->posts->get_full($currPost);
        $this->data['currPost'] = $currPost;
        
        //initialize information about logged in user
        $isAdmin  = isset($_SESSION['admin'])   ? $_SESSION['admin']   : false;
        $currUser = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
        $commentToEdit = isset($_SESSION['commentToEdit']) ? $_SESSION['commentToEdit'] : null;
        
        
        
        //TEAMLIST
        $curTeamID = $currPostInfo->team_id;//get tareget team_Id
        //get all team members from target team
        $team_members = $this->team_members->some('team_id', $curTeamID);
        
        //team members
        $teams_users_members = $this->team_members->get_team_member_details($curTeamID);
                
        /*foreach ($team_members as $team_member)
        {
            $userID = $team_member->user_id;
            $user = $this->users->get($userID);
            $userName = $user->username;
            array_push($usernames, $user);          
        }*/
        
        foreach ($teams_users_members as $team_member) $team_member->post_id = $currPostInfo->post_id;       
        
        
        //team members
        $this->data['teamlistview'] = $teams_users_members;
        
        //Load the various view fragments
        if ($isAdmin || $currUser === $currPostInfo->poster_id)
        {
            $currPostInfo->title =
                $this->createEditField(
                    "/Admin/editPostTitle/" . $currPostInfo->post_id,
                    "Title", 
                    "title", 
                    $currPostInfo->title, 
                    "makeTextField");
            
            $currPostInfo->content =
                $this->createEditField(
                    "/Admin/editPostDesc/" . $currPostInfo->post_id,
                    "Description", 
                    "content", 
                    $currPostInfo->content, 
                    "makeTextArea");
            
            $currPostInfo->max_team_count =
                $this->createEditField(
                    "/Admin/editPostMembersNum/" . $currPostInfo->post_id,
                    "Max Number of Team Members:", 
                    "mtc", 
                    $currPostInfo->max_team_count, 
                    "makeTextField");
            
            //team members
            $this->data['teamlist'] = $this->parser->parse('_teamlistedit', $this->data, true);
        }
        else
        {
            //team members
            $this->data['teamlist'] = $this->parser->parse('_teamlist', $this->data, true);
        }
        
        //Fill the current post info
        $this->data['postInfo'] = $this->parser->parse('_justone', $currPostInfo, true);
        //Fill the form data for the comment box
        $this->data['newCommentForm'] = $currUser != null ?
            makeContentForm("/Post/postComment/". $currPostInfo->post_id, "", 
                            "", "button", "Comment", "Add Comment") :
            makeButton("/SignIn", "Sign In", "button center");
        //Fill up the comments in the current post
        $this->data['commentsBox'] = makeCommentBox($allComments, $commentToEdit, $currUser, $isAdmin);
        
        $this->render();
    }
    
    public function createEditField($handler, $title, $name, $val, $field, $extras = "")
    {
        $string = '';
        
        $string .= '<form class="center" action="' . $handler .'" method="post">';
        $string .= $field($title, $name, $val);
        $string .= "<button type='submit' class='button'>Save</button>";
        $string .= '</form>';
        
        return $string;
                        
    }    
    
    public function postComment($toPost)
    {
        
        if(!isset($_SESSION['user_id'])) redirect("../SignIn");
        
        $record = $this->comments->create();
        
        // Extract submitted fields
        $record->post_id    = $toPost;
        $record->title      = $this->input->post('title');
        $record->content    = $this->input->post('content');
        $record->poster_id  = $_SESSION['user_id'];
                
        // Save stuff
        if (empty($record->comment_id)) $this->comments->add($record); 
        else $this->comments->update($record); 
        
        redirect('../Post/showPost/' . $toPost);
    }
        
    public function join_team($teamId)
    {    
        if (!isset($_SESSION['user_id'])) redirect('../SignIn');
                
        $team_member = $this->team_members->create();
        $team_member->team_id = $teamId;
        $team_member->user_id = $_SESSION['user_id'];
        $this->team_members->add($team_member);
        
        /* Update Team Record */
        $team_record = $this->teams->get($teamId);
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