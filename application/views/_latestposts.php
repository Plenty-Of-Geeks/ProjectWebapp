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
        margin-bottom: 5%;
        background: wheat;
        width: 100%;
    }
    
    .wheatBox.desc{
        width: 50%;
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

{posts}
<section id="content">
    <div id="quest" class="wheatBox">
        <div class="titleBox">
            <h1 id="title">{title}</h1>
            <h3>Quest Giver: {poster}</h3>
        </div>
        <hr />
        <div class="desc">
            <h2>Description:</h2>
            <p>
            {content}
            </p>
        </div>
        <hr />
        <div id="TeamCountBox">
            Party: {teamCount} / {maxTeamCount}
        </div>
        <a href="#" class="button">Read more</a>
        <a href="#" class="button">Comments</a>
        <a href="#" class="button">Join</a>
    </div>
</section>                                                                                                                                                      
{/posts}
