<?php

if (!defined('APPPATH'))
    exit('No direct script access allowed');




/**
 * Construct a comment box
 * 
 * @param strObj $comments a row in the comments database
 * @param boolean $admin True if user is an admin
 * @param int $commentToEdit comment_id that needs to be edited
 * 
 */
if (!function_exists('makeCommentBox')) {

    function makeCommentBox($comments, $admin = false, $commentToEdit = null ) {
        $CI = &get_instance();
        
        if (!function_exists('makePHPButton')) $CI->load->helper('button_helper.php');
        
        $commentBox = '';
        
        foreach($comments as $comment)
        {
            $comment->adminContent = "";
            if($admin)
            {
                if($commentToEdit == null || $commentToEdit != $comment->comment_id)
                {
                    $comment->adminContent .= makePHPButton("../Admin/editComment", 
                                                          "Edit", 
                                                          "cId", 
                                                          $comment->comment_id, 
                                                          "button floatLeft");
                }
                else
                {
                    $comment->adminContent   .= '<form action="../Admin/saveComment" method="post">';
                    $comment->adminContent   .= makeTextField('Title', 'title', $comment->title); 
                    $comment->adminContent   .= makeTextArea('Comment', 'content', $comment->content, "", 1000, 25, 5, false);
                    $comment->adminContent   .= makeSubmitButton("Save", "Save", "button");
                    $comment->adminContent   .= '</form>';
                    $comment->title = "";
                    $comment->content = "";
                }
                $comment->adminContent .= makePHPButton("../Admin/deleteComment", 
                                                          "Delete", 
                                                          "cId", 
                                                          $comment->comment_id, 
                                                          "button floatLeft");
            }            
            
            $commentBox .= $CI->parser->parse('_j1_comment_box', $comment, true);
        }
        
        return $commentBox;
    }

}

/* End of file */
