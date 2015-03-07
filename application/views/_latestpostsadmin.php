{posts}
<div class="content floatLeft">
    <div id="quest" class="wheatBox bobbing">
        
        
        <div class="titleBox center" onclick='toggle({post_id})'>
            <h1 id="title">{title}</h1>
        </div>
        
         <h3>Quest Giver: <a style=" text-decoration: none;" href="../Account/profile/{username}">{username}</a></h3>
        
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
                    <button class="button" onclick='openDesc({post_id})'>Read more</button>
                <form action="/Post/comment" method="post">
                    <input name='postId' value='{post_id}' hidden="true" />
                    <button type='submit' class="button">Comments</button>
                </form>
                <form action="/Post/join_team" method="post">
                    <input name='teamId' value='{team_id}' hidden="true" />
                    <button type='submit' class="button {isFull} {hasJoined}">Join</button>
                </form>
                 <form action="/Admin/deletePost" method="post">
                    <input name='postId' value='{post_id}' hidden="true" />
                    <button type='submit' class='button'>Delete</button>
                 </form>
            </div>
    </div>
</div>                                                                                                                                                      
{/posts}



