/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */



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