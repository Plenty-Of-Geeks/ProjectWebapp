<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

?>

<style>
    .wheatBox{
        //box-shadow: 5px 5px 5px 5px;
        //border-radius: 5px;
        //background: wheat;
        background-image: url("../assets/scroll.gif");
        background-size: 100% 100%;
        background-repeat: no-repeat;
        padding: 1.5em;
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
    
   .questInfo{
       display:none;
   }
   
   .center{
        text-align: center;
        padding: 0.5em;
   }
   
   
   .content{
       float: left;
        padding: 0em 1.5em 0em 1.5em;
        margin-bottom: 5%;
        width: 20em;
   }
   
   .content p{
       background-color: rgba(255,255,255,0.2);
       color: #000;
   }
   
   .button{
        background-color: #00A0EB;
        border-radius: 5px 5px 5px 5px;
        color: #FFFFFF;
        display: inline-block;
        font-size: 13px;
        font-weight: bold;
        padding: 8px 15px;
        text-decoration: none;
        text-transform: uppercase;
        margin: 0.1em;
   }
</style>
<script src="//code.jquery.com/jquery-1.11.2.min.js"></script>
<script type='text/javascript'>
    function openDesc(id){
        console.log("post" + id);
        document.getElementById("post" + id).style.display = "block";
        
        
        
    }
    function toggle(id){
        if(document.getElementById("post" + id).style.display == "block")
            document.getElementById("post" + id).style.display = "none";
        else
            document.getElementById("post" + id).style.display = "block";
    }
</script>



{posts}
<div class="content">
    <div id="quest" class="wheatBox bobbing">
        <div class="titleBox" onclick='toggle({post_id})'>
            <h1 id="title">{title}</h1>
            <h3>Quest Giver: {poster}</h3>
        </div>
            <hr />
        <div id='post{post_id}' class="questInfo">
            <div class="desc">
                <h2>Description:</h2>
                <p>
                {content}
                </p>
            </div>
        </div>
            <hr />
            <div id="TeamCountBox">
                Party: {teamCount} / {maxTeamCount}
            </div>
            <div class='center'>
                <form action="/Post/comment" method="post">
                    <button class="button" onclick='openDesc({post_id})'>Read more</button>
                    <input name='postId' value='{post_id}' hidden="true" />
                    <button type='submit' class="button">Comments</button>
                    <button class="button">Join</button>
                </form>
            </div>
    </div>
</div>                                                                                                                                                      
{/posts}
