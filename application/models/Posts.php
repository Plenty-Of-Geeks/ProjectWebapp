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
                'content' => 'This idea is great. It is the best game in the '
                . 'world. Please take up my project. I am very excited about '
                . 'all the posibilities of this project and hope to meet some '
                . 'new and friendly people.  This is not a dating site, so '
                . 'please do not expect any romantic relationships.', 
                'poster' => 'Fake User',
                'teamCount' => '0',
                'maxTeamCount' => '5',),
            array(  'title' => 'MockeQuest Title',
                'content' => 'Main character is a farmer, you farm all day. Oh what fun!!!',
                'poster' => 'Real User',
                'teamCount' => '12',
                'maxTeamCount' => '13',),
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