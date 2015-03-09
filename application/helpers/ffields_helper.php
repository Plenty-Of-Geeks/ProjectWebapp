<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
if (!function_exists('makeContentForm')) {

    function makeContentForm($handler, $titleVal = "", $contentVal="", $classExtra = "", $contentLbl = "Content", $submitLbl = "Submit", $titleLbl = "Title", $explain = "")
    {
        $CI = &get_instance();
        $CI->load->helper("formfields");
                
        $array = array(
            "handler" => $handler,
            "title"   => makeTextField($titleLbl, 'title', $titleVal),
            "content" => makeTextArea($contentLbl, 'content', $contentVal, "", 1000, 25, 5, false),
            "fsubmit" => makeSubmitButton($submitLbl, $explain, $classExtra)
        );
        
        return $CI->parser->parse('_pogfield/_content_form', $array, true);
    }
}


if (!function_exists('makeButton')) {

    function makeButton($handler, $title, $classExtra = "")
    {
        $CI = &get_instance();
         
        $array = array(
            "handler" => $handler,
            "title"   => $title,
            "classExtra" => $classExtra
        );
        
        return $CI->parser->parse('_pogfield/button', $array, true);
    }
}