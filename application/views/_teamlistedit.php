{teamlistview}
<div class="content floatLeft">
    <a style=" text-decoration: none;" href="../Account/profile/{username}">{username}</a>
</div>   
<div class="content floatRight">
            <form action="/Admin/deletePostMembers" method="post">
                <input name='teamMemberId' value='{team_member_id}' hidden="true" />
                <input name='teamId' value='{team_id}' hidden="true" />
                <button type='submit' class='button'>Remove</button>
            </form>
</div>  
{/teamlistview}