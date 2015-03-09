<div class="floatLeft content halfWidthPadded">
    <div id="quest" class="wheatBox">
        <div class="center">
            <h1 id="title">
                {title}
            </h1>
            <h3>Quest Giver: <a style=" text-decoration: none;" href="/Account/profile/{username}">{username}</a></h3>
        </div>
            <hr />
        <div id='post{post_id}' class="">
            <div class="desc">
                <h3>Description:</h3>
                <p>
                {content}
                </p>
            </div>
        </div>
            <hr />
            <div id="TeamCountBox" class="center">
                Party: {team_count} / {max_team_count}
            </div>
            <div class='center'>
                    <div class=" button {isFull} {hasJoined}">
                        <a href="/Post/join_team/{team_id}" > Join </a>
                    </div>
            </div>
    </div>
</div>       