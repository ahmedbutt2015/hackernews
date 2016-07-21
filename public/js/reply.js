$('.reply').on('click',openReply);
function closeReply() {
    $(this).siblings('.navbar-form').remove();
    $(this).unbind('click');
    $(this).bind('click',openReply);
}

function openReply(e) {
    var temp = $(this).parent().parent().parent().parent().attr('data-id');
    var token = $('meta[name="csrf-token"]').attr('content');
    var id = $('.wrapper .content').attr('data-id');
    console.log(id);
    var reply = '<form class="navbar-form navbar-left" action="/addReply" method="post">'
        +'<div class="form-group">' 
        +'<input type="hidden"  name="comment_id" value="'+temp+'">'
        +'<input type="text" name="comment"class="form-control" placeholder="Search">'
        +'</div>'
        +'<input type="hidden" name="_token" value='+token+'>'
        +'<input type="hidden"  name="post_id" value="'+id+'">'
        +'<button type="submit" class="btn btn-default">Submit</button>'
        +'</form>' ;
    $(this).parent().append(reply);

    $(this).unbind('click');
    $(this).bind('click',closeReply);
}