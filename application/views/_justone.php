<div class="content halfWidth">
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
                    <button class="button">Join</button>
            </div>
    </div>
</div>       