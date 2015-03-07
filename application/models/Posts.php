<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Posts extends MY_Model
{   
    // Constructor
    public function __construct() {
        parent::__construct('posts', 'post_id');
    }
    
    public function get_full($postId)
    {
        
        $this->db->join('users', 'posts.poster_id = users.user_id');
        $this->db->join('teams', 'posts.team_id = teams.team_id');
        $this->db->where('post_id', $postId);
        $query = $this->db->get($this->_tableName);
                
        return $query->result()[0];        
    }
        
    public function get_all_posts($user_id = null)
    {
        $this->db->join('users', 'posts.poster_id = users.user_id');
        $this->db->join('teams', 'posts.team_id = teams.team_id');
        $this->db->order_by($this->_keyField, 'asc');
        $query = $this->db->get($this->_tableName);
        $posts = $query->result();
        
        /* Set up join validity */
        foreach($posts as $key => $post)
        {
            if ($post->team_count >= $post->max_team_count)
            {
                $posts[$key] = $this->append_object($post, 'isFull', 'hidden');
            }
            else
            {
                $posts[$key] = $this->append_object($post, 'isFull', '');
            } 
            
            if ($user_id != null)
            {
                $posts[$key] = $this->check_team_member_exist($posts[$key], $user_id);
            }
        }
        
        return $posts;
    }
    
    public function check_team_member_exist($post, $user_id)
    {
        /* Check if user is in team exists */
        $this->load->model('team_members');
        $team_member_records = $this->team_members->some('team_id', $post->team_id);
        $result_post = $this->append_object($post, 'hasJoined');
        foreach ($team_member_records as $record)
        {
            if ($record->user_id == $user_id)
            {
                $result_post->hasJoined = 'hidden';
                break;
            }
        } 
        
        return $result_post;
    }
    
    
    public function get_all_post_by_poster_id($poster_id)
    {
        $this->db->join('teams', 'posts.team_id = teams.team_id');
        $this->db->join('users', 'posts.poster_id = users.user_id');
        $this->db->where('poster_id',$poster_id);
        $this->db->order_by('post_id','desc');
        $query = $this->db->get($this->_tableName);
        return $query->result();
    }

}