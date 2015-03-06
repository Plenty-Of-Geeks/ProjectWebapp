<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class CreateTeam extends Application
{
    public function __construct() 
    {
        parent::__construct();
        $this->load->helper('formfields');
        $this->load->model('teams');
    }
    
    public function index()
    {
        $this->data['pagebody'] = 'createteam';

        $team = $this->teams->create();
        $this->data['team_name']   = makeTextField('Team Name', 'team_name', $team->name); 
        $this->data['max_team_count'] = makeTextField('Max Number of Members', 'max_team_count', $team->max_team_count);
        
        $this->data['fsubmit'] = makeSubmitButton( 
                'Create Team', 
                "Click here to validate the post data", 
                'btn-success'); 
        
        $this->render();
    }
    
    public function confirm()
    {
        $record = $this->teams->create();
        // Extract submitted fields
        $record->name = $this->input->post('team_name');
        $record->max_team_count = $this->input->post('max_team_count');
        $record->team_count = 1;
        
        // Save stuff
        if (empty($record->team_id))
        {
            $this->teams->add($record);
        }
        else
        {
            $this->teams->update($record);
        }
        redirect('/Post');
    }
}
