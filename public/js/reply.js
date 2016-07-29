init();
function init() {
    $('.reply').on('click',openReply);
}
function closeReply() {
    $(this).siblings('.replyForm').remove();
    $(this).unbind('click');
    $(this).bind('click',openReply);
}

function openReply() {
    $('.replyForm').remove();
    var temp = $(this).parents('.comment').attr('data-id');
    var token = $('meta[name="csrf-token"]').attr('content');

    var id = $('.wrapper .content').attr('data-id');
    var reply = '<div class="form-group  replyForm">'
        + '<input type="hidden"  name="comment_id" value="' + temp + '">'
        + '<textarea required name="comment" class="form-control addReplyText"   rows="3" placeholder="New Comment"></textarea>'
        + '</div>'
        + '<input type="hidden" name="_token" value=' + token + '>'
        + '<input type="hidden"  name="post_id" value="' + id + '">';
    $(this).parent().append(reply);
    $(this).unbind('click');
    $(this).bind('click', closeReply);
    $('.addReplyText').on('keypress',addReply);
}
function addReply(e) {
    if(e.which == 13 && this.value != ''){

        var comment = $(this).val();
        var username = $('meta[name="username"]').attr('content');
        _this = this;
        $.ajax({
            url : '/addReply',
            type : 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data : {
                comment : comment,
                comment_id : $(_this).parents('.comment').first().attr('data-id')
            },
            success : function (res) {
                res = JSON.parse(res);
                $(_this).val('');
                var temp = '<div class="comment commentReply" data-id="'+res.id+'" vote="">'
                    +'<div class="vote">'
                    +'<div class="upvote glyphicon glyphicon-chevron-up"></div>'
                    +'<div class="downvote glyphicon glyphicon-chevron-down"></div>'
                    +'</div>'
                    +'<div class="content-body">'
                    +'<div class="content-body_down">'
                    +'<p>	<span class="point">0</span> points by '
                    +'<span class="content-body_down-username">'+username+'</span>'
                    +' just now | <span class="content-body_down-comments">0'
                    +'</span> replies <span class="content-body_down-delete">| <a> Delete</a></span></p></div> <div class="content-body_up"> <div class="content-body_up-title">'
                    +'<p> '+ comment +'</p><p class="reply">reply</p></div></div>'
                    +'</div> </div>';
                $(_this).parents('.comment').first().append(temp);
                temp = $('.commentReply');
                $(temp).each(function () {
                   if(res.id == $(this).attr('data-id')){
                       temp = $(this);
                   }
                });
                var t = $(_this).parents('.comment').first().find('.content-body_down-comments').first();
                $(t).text(parseInt($(t).text()) + 1);
                $(_this).parent().siblings('.reply').bind('click',openReply);
                $(_this).remove();
                $('.reply').on('click',openReply);
                $('.content-body_down-delete').on('click',deleteComment);
                $(temp).find('.upvote').first().on('click',upvoteEventC);
                $(temp).find('.downvote').first().on('click',downvoteEventC);
            }
        });
    }
}
