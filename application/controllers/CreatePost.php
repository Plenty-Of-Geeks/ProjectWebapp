<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class CreatePost extends Application
{
    public function __construct() 
    {
        parent::__construct();
        $this->load->helper('formfields');
    }
    
    public function index()
    {
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
        redirect('/Post');
    }
}
