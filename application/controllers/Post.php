<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Post extends Application {

    public function index()
	{
		$this->data['pagebody'] = 'post';
                
                /* Get Latest Posts */
                $sourcePosts = $this->posts->all();
                
                $this->data['latestposts'] = $this->parser->parse('_latestposts', $sourcePosts, true);
                
                $this->render();
	}   

        
}