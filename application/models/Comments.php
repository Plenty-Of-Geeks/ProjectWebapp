<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Comments extends MY_Model
{    
    // Constructor
    public function __construct() {
        parent::__construct('comments', 'comment_id');
    }
    
    public function get_old($postNumber)
    {
        return $this->data[$postNumber];
    }
    
    public function all_old()
    {
        return $this->data;
    }
}