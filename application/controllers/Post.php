<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Post extends Application {
    
    function __construct()
    {
	parent::__construct();
        
        $this->load->model('posts');
        $this->load->model('comments');
    }
    public function index()
    {
        $this->load->model('posts');
            $this->data['pagebody'] = 'post';

            /* Get Latest Posts */
            $this->data['posts'] = $this->posts->get_all_posts();

            $this->data['latestposts'] = $this->parser->parse('_latestposts', $this->data, true);

            $this->render();
    }
    
    public function create()
    {
        $this->load->helper('formfields');
        $this->data['pagebody'] = 'createpost';

        $post = $this->posts->create();
        $this->data['title']   = makeTextField('Title', 'title', $post->title); 
        $this->data['content'] = makeTextField('Content', 'content', $post->content);
        
        $this->data['fsubmit'] = makeSubmitButton( 
                'Add Post', 
                "Click here to validate the post data", 
                'btn-success'); 
        
        
        $this->render();
    }
    
    public function confirm()
    {
        $record = $this->posts->create();
        // Extract submitted fields
        $record->title = $this->input->post('title');
        $record->content = $this->input->post('content');
        
        // Save stuff
        if (empty($record->post_id))
        {
            $this->posts->add($record);
        }
        else
        {
            $this->posts->update($record);
        }
        
        redirect('../Post');
    }
    
    public function comment()
    {
        $this->data['pagebody'] = 'welcome';

        /* Get Selected Posts */
        $this->data['post_id'] = $this->input->post('postId');
        $sourcePosts = $this->posts->get($this->data['post_id']);
        
        //print_r($sourcePosts);
        
        $this->load->helper('formfields');

        $post = $this->posts->create();
        
        $this->data['title']   = makeTextField('Title', 'title', $post->title); 
        $this->data['content'] = makeTextArea('Comment', 'content', $post->content, "", -1, 25, 5, false);
        $this->data['fsubmit'] = makeSubmitButton( 
                'Add Comment', 
                "Click here to validate the post data", 
                'btn-success');
        
        $this->data['latestposts'] = $this->parser->parse('_justone', $sourcePosts, true);
        $this->data['latestposts'] .= $this->parser->parse('createcomment', $this->data, true);
        $this->render();
    }
    
    public function postComment()
    {
        $record = $this->comments->create();
        // Extract submitted fields
        $record->post_id = $this->input->post('postId');
        $record->title   = $this->input->post('title');
        $record->content = $this->input->post('content');
        // Save stuff
        if (empty($record->comment_id))
        {
            $this->comments->add($record);
        }
        else
        {
            $this->comments->update($record);
        }
        
        redirect('../Post');
        
    }
        
}