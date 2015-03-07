<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Users extends MY_Model
{
    public function __construct()
    {
        parent::__construct('users', 'user_id');
    }
    
    public function get_by_username($username)
    {
        $this->db->where('username',$username);       
         $query = $this->db->get($this->_tableName);
          return $query->result()[0];
    }
}

