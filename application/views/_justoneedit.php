<div class="floatLeft content halfWidthPadded">
    <div id="quest" class="wheatBox">
        <div class="center">
            
                <h1 id="title" name='title'>
                    <form action="/Admin/editPostTitle" method="post">
                    {title}
                    <input name='postId' value='{post_id}' hidden="true" />
                    <button type='submit' class='button'>Save</button>
                </h1>
            </form>
            <h3>Quest Giver: {username}</h3>
        </div>
            <hr />
        <div id='post{post_id}' class="">
            <div class="desc">
                <h3>Description:</h3>
                <form action="/Admin/editPostDesc" method="post">
                <p>
                {content}
                </p>
                
                <input name='postId' value='{post_id}' hidden="true" />
                <button type='submit' class='button'>Save</button>
            </form>
            </div>
        </div>
            <hr />
            <form action="/Admin/editPostMembersNum" method="post">
            <div id="TeamCountBox" class="center">
                Party: {team_count} / {max_team_count}
            </div>
            
                <input name='postId' value='{post_id}' hidden="true" />
                <button type='submit' class='button'>Save</button>
            </form>
            <div class='center'>
                    <button class="button">Join</button>
            </div>
    </div>
</div>      