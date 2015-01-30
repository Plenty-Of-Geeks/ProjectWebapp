<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class MockQuest extends Application {

    public function index()
	{
		$this->data['pagebody'] = 'mockquest';
                $this->data['poster'] = "Fake User";
                $this->data['teamCount'] = "0";
                $this->data['maxTeamCount'] = "5";
                $this->render();
	}   

        
}