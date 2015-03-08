{teamlistview}
<div class="content floatLeft">
    {username}
</div>   
<div class="content floatRight">
            <form action="/Admin/deletePostMembers" method="post">
                <input name='teamMemberId' value='{team_member_id}' hidden="true" />
                <input name='teamId' value='{team_id}' hidden="true" />
                <button type='submit' class='button'>Remove</button>
            </form>
</div>  
{/teamlistview}