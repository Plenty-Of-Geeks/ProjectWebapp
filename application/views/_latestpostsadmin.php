{posts}
<div class="content floatLeft">
    <div id="quest" class="wheatBox bobbing">
        
        
        <div class="titleBox center" onclick='toggle({post_id})'>
            <h1 id="title">{title}</h1>
        </div>
        
         <h3>Quest Giver: <a style=" text-decoration: none;" href="../Account/profile/{username}/{username}">{username}</a></h3>
        
            <hr />
        <div id='post{post_id}' class="questInfo hidden">
            <div class="desc">
                <h3>Description:</h3>
                <p>
                {content}
                </p>
            </div>
        </div>
            <hr />
            <div id="TeamCountBox" class='center'>
                Party: {team_count} / {max_team_count}
            </div>
            <div class='center'>
                <div class="button">
                    <a onclick='openDesc({post_id})'>Read more</a>
                </div>
                <div class="button">
                    <a href="/Post/showPost/{post_id}">Comment/Edit</a>
                </div>
                <br />
                <div class=" button {isFull} {hasJoined}">
                    <a href="/Post/join_team/{team_id}" > Join </a>
                </div>
                <div class="button">
                    <a href="/Admin/deletePost/{post_id}">Delete</a>
                </div>
            </div>
    </div>
</div>                                                                                                                                                      
{/posts}



