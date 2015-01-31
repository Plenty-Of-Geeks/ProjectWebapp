<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
?>

<script>
$(document).ready(function(){
    $("result").hide();
    
    $("#submit").click(function(){
        $("result").show();
    });
});
</script>

<!-- The jquery above was suppose to show/hide the resulting form info when 
    a user hit submit. BUt i forgot there's not php in the view...
-->


<div id="page" style="margin-left: 25%; margin-right: 25%; margin-top: 1em; width: 100%;">

    <h2>Contact</h2>					

    <p>If you wish to contact us, please fill out the form below and we will get back
        to you as soon as possible.
    </p>
    <form method="post" action="">
    <input type="hidden" name="recipient" value="something@somewhere.com">
    <strong>
        Name:<br>
        <input type="text" name="name" id="name" value="John Doe"><br><br>

        E-mail:<br>
        <input type="text" name="email" value="Johndoe@example.com"><br><br>

        Comment:<br>
        <textarea  name="comments" maxlength="1000" cols="25" rows="6"></textarea>

        <br>
        <br>

        <input type="submit" value="Submit" id="submit">
        <input type="reset" value="Reset"><br>
    </strong>
    </form>

</div>


</section>



