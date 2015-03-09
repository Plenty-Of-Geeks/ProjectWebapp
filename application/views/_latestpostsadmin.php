{posts}
<div class="content floatLeft">
    <div class="wheatBox bobbing">
        <div class="titleBox center" onclick='toggle({post_id})'>
            <h1>{title}</h1>
         <h3>Quest Giver: <a style=" text-decoration: none;" href="../Account/profile/{username}/{username}">{username}</a></h3>
        
        </div>
        
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
            <div class='center'>
                Party: {team_count} / {max_team_count}
            </div>
            <div class='center'>
                <div class="button">
                    <a onclick='openDesc({post_id})'>Read more</a>
                </div>
                <div class="button">
                    <a href="/Post/showPost/{post_id}">Comments/Edit</a>
                </div>
                <br />
                <div class="button {isFull} {hasJoined}">
                    <a href="/Post/join_team/{team_id}" >Join</a>
                </div>
                <div class="button">
                    <a href="/Admin/deletePost/{post_id}" >Delete</a>
                </div>
            </div>
    </div>
</div>                                                                                                                                                      
{/posts}



