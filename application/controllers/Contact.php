<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Contact extends Application {

    public function index()
	{
		$this->data['pagebody'] = 'contact';
                $this->render();
	}   

        
}