<?php

/**
 * core/MY_Controller.php
 */
class Application extends CI_Controller {

    protected $data = array();      // parameters for view components
    protected $id;                  // identifier for our content

    /**
     * Constructor.
     * Establish view parameters & load common helpers
     */
    function __construct() {
        parent::__construct();
        
        if(session_id() == '') 
        {
            session_start();
        }
        
        $this->data = array();
        $this->data['title'] = 'Plenty of Geeks';    // our default title
        $this->errors = array();
        $this->data['pageTitle'] = 'Welcome';   // our default page
        
        
        
        $this->data['menu_choices'] = array(
            'menudata' => array(
                    array('name' => 'Home', 'link' => '/Welcome'),
                    array('name' => 'Posts', 'link' => '/Post'),
                    array('name' => 'About', 'link' => '/About'),
                    array('name' => 'Contact', 'link' => '/Contact'),
                )
        );
        
        
        if (isset($_SESSION['username']))
        {
            array_push($this->data['menu_choices']['menudata'], array('name' => 'Account', 'link' => '/Account'));
            array_push($this->data['menu_choices']['menudata'], array('name' => 'Logout', 'link' => '/SignIn/logout'));
            
            //IF WE ARE LOGGED IN AS AN ADMIN, DISPLAY ADMIN PAGE IN MENU BAR
            if (isset($_SESSION['admin']))
            {
                array_push($this->data['menu_choices']['menudata'], array('name' => 'Admin', 'link' => '/Admin'));
            }
        }
        else
        {
            array_push($this->data['menu_choices']['menudata'], array('name' => 'Sign In', 'link' => '../SignIn'));
        }
        
        $this->data['sidebar_choices'] = array(
            'sidebardata' => array(
                array('name' => 'Designer Page', 'link' => '/Welcome'),
                array('name' => 'Programmer Page', 'link' => '/Welcome'),
            )
        );
    }

    /**
     * Render this page
     */
    function render() {
        $this->data['menubar'] = $this->parser->parse('_menubar', $this->data['menu_choices'], true);
        $this->data['content'] = $this->parser->parse($this->data['pagebody'], $this->data, true);
        $this->data['sidebar'] = $this->parser->parse('_sidebar', $this->data['sidebar_choices'], true);
        
        // finally, build the browser page!
        $this->data['data'] = &$this->data;
        $this->parser->parse('_template', $this->data);
    }

}