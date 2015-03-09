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
        
        $this->data['pagebody'] = 'search';
        
        $this->data['username'] = makeTextField('Username', 'username', '');
        
        $this->data['searchlist'] = '';
        $this->data['resultslabel'] = '';
        $this->render();
    }
    public function deletePost($targetPostID)
    {
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
    
    public function deleteComment($postId, $cId)
    {
        $this->comments->delete($cId);
        redirect('../Post/showPost/' . $postId);
    }
    public function editComment($postId, $cId)
    {
        $_SESSION['commentToEdit'] = $cId;
        redirect('../Post/showPost/' . $postId);
    }
    public function saveComment($postId)
    {
        if(!isset($_SESSION['user_id'])) redirect("../SignIn");
        
        $record = $this->comments->get($_SESSION['commentToEdit']);
        
        // Extract submitted fields
        $record->title      = $this->input->post('title');
        $record->content    = $this->input->post('content');
        
        // Save stuff
        $this->comments->update($record); 
        
        unset($_SESSION['commentToEdit']);
        
        redirect('../Post/showPost/' . $postId);
    }
    
    public function editPostTitle($targetPostID)
    {
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
        redirect('../Post/showPost/' . $targetPostID);
        
    }
    public function editPostDesc($targetPostID)
    {
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
        redirect('../Post/showPost/' . $targetPostID);
    }
    public function deletePostMembers($postId)
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
        redirect('../Post/showPost/' . $postId);
    }
    
    public function editPostMembersNum($targetPostID)
    {
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
        redirect('../Post/showPost/' . $targetPostID);
    }
    public function search()
    {
        $this->data['pagebody'] = 'search';
        
        $this->data['username'] = makeTextField('Username', 'username', '');
        
        $searchusername = $this->input->post('username');
        
        $users = $this->users->some_like('username', $searchusername);
        
        $this->data['userlistview'] = $users;
        
        $this->data['searchlist'] = $this->parser->parse('_searchlist', $this->data, true);

        $this->data['resultslabel'] = 'Search Results:';
        
        $this->render();
        //redirect('/Admin/search');
    }
    
    public function logout()
    {
        unset($_SESSION['username']);
        unset($_SESSION['user_id']);
        redirect('/Welcome');
    }
}

