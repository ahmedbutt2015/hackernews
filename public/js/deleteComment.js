var username = $('meta[name="username"]').attr('content');

$('.comment').each(function () {

    if($(this).find('.content-body_down-username').first().text() == username){
        var temp = '<span class="content-body_down-delete">| <a> Delete</a></span>';
        $(this).find('.content-body_down p').first().append(temp);
    }
});

$('.content-body_down-delete').on('click',deleteComment);
function deleteComment() {

    var _this = $(this).parents('.comment').first();
    var id = $(_this).attr('data-id');
    $.ajax({
        url : '/deleteComment',
        type : 'DELETE',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data : {
            id : id
        },
        success : function () {
            if($(_this).hasClass('commentReply')){
                console.log('asd');
                var replies = $(_this).parents('.comment').first().find('.content-body_down-comments').first();
            }else{
                var replies = $('.wrapper').find('.content-body_down-comments').first();
            }
            var temp = parseInt($(replies).text()) - 1;
            $(replies).text(temp);
            $(_this).slideUp().remove();
        }
    });
}