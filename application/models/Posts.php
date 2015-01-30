<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Posts extends CI_Model
{
    var $data = array(
        'posts' => array(
            array(  'title' => 'Test Post',
                'content' => 'This idea is great. It is the best game in the world. Please take up my project', )
        )    
    );

    // Constructor
    public function __construct() {
        parent::__construct();
    }
    
    public function get($postNumber)
    {
        return $this->data[$postNumber];
    }
    
    // retrieve all the posts
    public function all() {
        return $this->data;
    }
}