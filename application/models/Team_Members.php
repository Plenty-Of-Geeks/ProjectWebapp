<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Team_Members extends MY_Model
{
    public function __construct() {
        parent::__construct('team_members', 'team_member_id');
    }
    
    public function get_team_member_details($teamId = null)
    {
        //return a joined table between users, teams, and team_members
        $this->db->join('users', 'team_members.user_id = users.user_id');
        $this->db->join('teams', 'team_members.team_id = teams.team_id');
        $this->db->where('teams.team_id', $teamId);
        $this->db->order_by($this->_keyField, 'asc');
        
        $query = $this->db->get($this->_tableName);

        return $query->result();
    }
}