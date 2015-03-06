<div id="content" style="text-align: center;">
    <h2> Create a New Post </h2>    
    <form action="/Post/submit_post" method="post">
    
    {title}
    {content}
    {team_name}
    {max_team_count}
    <div>* Required Field </div>
    <div class='error_message'>{error_message}</div>
    {fsubmit}
    </form> 
</div>
