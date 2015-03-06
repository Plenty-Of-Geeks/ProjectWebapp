{posts}
<div class="content">
    <div id="quest" class="wheatBox bobbing">
        <div class="titleBox" onclick='toggle({post_id})'>
            <h1 id="title">{title}</h1>
            <h3>Quest Giver: {username}</h3>
        </div>
            <hr />
        <div id='post{post_id}' class="questInfo hidden">
            <div class="desc">
                <h2>Description:</h2>
                <p>
                {content}
                </p>
            </div>
        </div>
            <hr />
            <div id="TeamCountBox">
                Party: {team_count} / {max_team_count}
            </div>
            <div class='center'>
                <form action="/Post/comment" method="post">
                    <button class="button" onclick='openDesc({post_id})'>Read more</button>
                    <input name='postId' value='{post_id}' hidden="true" />
                    <button type='submit' class="button">Comments</button>
                </form>
                <form action="/Post/join_team" method="post">
                    <input name='teamId' value='{team_id}' hidden="true" />
                    <button type='submit' class="button">Join</button>
                </form>
            </div>
    </div>
</div>                                                                                                                                                      
{/posts}
