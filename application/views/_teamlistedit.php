{teamlistview}

<div class="teamMember floatLeft">
    <a class="userLink" href="../Account/profile/{username}">{username}</a>
    <div class="content floatRight">
        <form action="/Admin/deletePostMembers/{post_id}" method="post">
            <input name='teamMemberId' value='{team_member_id}' hidden="true" />
            <input name='teamId' value='{team_id}' hidden="true" />
            <button type='submit' class='button'>Remove</button>
        </form>
    </div>  

</div>   
{/teamlistview}