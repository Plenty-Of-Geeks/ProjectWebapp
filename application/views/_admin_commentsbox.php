{comments}
<div class="floatLeft padded fullWidth box">
    <h2>{title}</h2>
    <p>{content}</p>
    <form action="/Post/editComment" method="post">
        <input name="cId" value="comment_id" />
        <button type="submit" class="button">Edit</button>
    </form>
</div>                                                                                                                                                      
{/comments}
