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
    
    public function get_all_posts()
    {
        $this->db->join('users', 'posts.poster_id = users.user_id');
        $this->db->join('teams', 'posts.team_id = teams.team_id');
        $this->db->order_by($this->_keyField, 'asc');
        $query = $this->db->get($this->_tableName);
        return $query->result();
    }
}