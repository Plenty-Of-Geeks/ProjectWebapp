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
    
    /** Searches the table by username **/
    public function get_by_username($username)
    {
        $this->db->where('username',$username);       
         $query = $this->db->get($this->_tableName);
          return $query->result()[0];
    }
    
    /** Returns max length for parameter **/
    public function max_length($column)
    {
        return $this->db->query('select MAX(LENGTH('.$column.')) from '.$this->_tableName);
    }
    // Return filtered records as an array of records
    function some_like($what, $which) {
        $this->db->order_by($this->_keyField, 'asc');
        if (($what == 'period') && ($which < 9)) {
            $this->db->like($what, $which); // special treatment for period
        } else
            $this->db->like($what, $which);
        $query = $this->db->get($this->_tableName);
        return $query->result();
    }
}

