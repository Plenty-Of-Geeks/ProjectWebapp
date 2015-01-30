<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
?>


<style>
    .wheatBox{
        box-shadow: 5px 5px 5px 5px;
        border-radius: 5px;
        padding: 2px;
        background: wheat;
    }
    
    
    .titleBox{
        text-align: center;
    }
    
    #quest{
        
    }
    
    #TeamCountBox{
        text-align: center;
    }
</style>




<section id="content">
    <div id="quest" class="wheatBox">
        <div class="titleBox">
            <h1 id="title">MockQuest Title</h1>
            <h3>Quest Giver: {poster}</h3>
        </div>
        <hr />
        <div id="desc">
            <h2>Description:</h2>
            <pre>
            Main character is a farmer, you farm all day. Oh what fun!!!
            </pre>
        </div>
        <hr />
        <div id="TeamCountBox">
            Party: {teamCount} / {maxTeamCount}
        </div>
    </div>
    
</section>



