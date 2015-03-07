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
        $this->load->model('teams');
        $this->load->model('team_members');
        $this->load->model('comments');
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
        $targetPostID = $this->input->post('postId');
        $targetPost = $this->posts->get($targetPostID);
        
        $teamID = $targetPost->team_id;
        
        $teams = $this->teams->some('team_id', $teamID);
        $teamMembers = $this->team_members->some('team_id', $teamID);
        $comments = $this->comments->some('post_id', $targetPostID);
        
        //delete the post table
        $this->posts->delete($targetPostID);
        
        //delete all the team member tables from this post
        foreach ($teamMembers as $user)
        {
            $teamMemberID = $user->team_member_id;
            $this->team_members->delete($teamMemberID);
        }
        
        //delete the team table from this post
        $this->teams->delete($teamID);
        
        //delete all the comment tables from this post
        foreach ($comments as $comment)
        {
            $commentID = $comment->comment_id;
            $this->comments->delete($commentID);
        }
        
        redirect('/Post');
    }
    
    public function editPostTitle()
    {
        $targetPostID = $this->input->post('postId');
        $targetPost = $this->posts->get($targetPostID);
        
        $teamID = $targetPost->team_id;
        
        $teams = $this->teams->some('team_id', $teamID);
        $teamMembers = $this->team_members->some('team_id', $teamID);
        $comments = $this->comments->some('post_id', $targetPostID);
        

        $newTitle = $this->input->post('title');
        
        //edit database
        $targetPost->title = $newTitle;
        $this->posts->update($targetPost);
        
        
        //go back to showpost
        redirect('../Post/showPost');
        
    }
    public function editPostDesc()
    {
        $targetPostID = $this->input->post('postId');
        $targetPost = $this->posts->get($targetPostID);
        
        $teamID = $targetPost->team_id;
        
        $teams = $this->teams->some('team_id', $teamID);
        $teamMembers = $this->team_members->some('team_id', $teamID);
        $comments = $this->comments->some('post_id', $targetPostID);
        
        $newDesc = $this->input->post('content');
        
        //edit database
        $targetPost->content = $newDesc;
        $this->posts->update($targetPost);
        
        //go back to showpost
        redirect('../Post/showPost');
    }
    public function deletePostMembers()
    {
        $teamMemberID = $this->input->post('teamMemberId');
        $teamID = $this->input->post('teamId');
        
        //$teamMember = $this->team_members->get($teamMemberID);
        
        $team = $this->teams->get($teamID);

        //decrement the number of team members in a team
        $oldTeamCount = $team->team_count;
        $team->team_count = ($oldTeamCount - 1);
        $this->teams->update($team);
        
        //delete the teamMember table
        $this->team_members->delete($teamMemberID);
        
        //go back to showpost
        redirect('../Post/showPost');
    }
    
    public function editPostMembersNum()
    {
        $targetPostID = $this->input->post('postId');
        $targetPost = $this->posts->get($targetPostID);
        
        $teamID = $targetPost->team_id;
        
        $team = $this->teams->get($teamID);
        
        $teamMembers = $this->team_members->some('team_id', $teamID);
        $comments = $this->comments->some('post_id', $targetPostID);
        
        $newMTC = $this->input->post('mtc');
        
        $teamCount = $team->team_count;
        
        if ($teamCount > $newMTC)
        {
            $newMTC = $teamCount;
        }
        
        //edit database
        $team->max_team_count = $newMTC;
        $this->teams->update($team);
       
        //go back to showpost
        redirect('../Post/showPost'); 
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

