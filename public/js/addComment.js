$('.addCommentText').on('keypress',function (e) {
    if(e.which == 13 && this.value != ''){
        var comment = $(this).val();
        var username = $('meta[name="username"]').attr('content');
        _this = this;
        $.ajax({
            url : '/addComment',
            type : 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data : {
                comment : comment,
                post_id : $('.content').attr('data-id')
            },
            success : function (res) {
                res = JSON.parse(res);
                $(_this).val('');
                var temp = '<div class="comment" data-id="'+res.id+'" vote="">'
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
                $('.comments').prepend(temp);
                var t = $('.wrapper').find('.content-body_down-comments').first();
                $(t).text(parseInt($(t).text()) + 1);
                $('.reply').on('click',openReply);
                $('.content-body_down-delete').on('click',deleteComment);
                var t = $('.comment').first();
                $(t[0]).find('.upvote').first().on('click',upvoteEventC);
                $(t[0]).find('.downvote').first().on('click',downvoteEventC);
            }
        });
    }
});