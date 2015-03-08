<?php
if (!defined('APPPATH'))
    exit('No direct script access allowed');
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


/**
 * Construct a php button
 * 
 * @param string $handler url to the php handler function
 * @param string $title title of button
 * @param string $name name to access input value to access through php
 * @param string $val value of button input
 * @param string $css_class_extras extra css classes 
 * 
 */
if (!function_exists('makePHPButton')) {

    function makePHPButton($handler, $title, $name, $val, $css_class_extras = "") {
        $CI = &get_instance();
        $parms = array(
            'handlerUrl' => $handler,
            'name' => $name,
            'val' => $val,
            '$css_class_extras' => $css_class_extras,
            'title' => $title
        );
        return $CI->parser->parse('stuff/button', $parms, true);
    }

}