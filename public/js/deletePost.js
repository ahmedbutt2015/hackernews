var username = $('meta[name="username"]').attr('content');

$('.content').each(function () {
    if($(this).find('.content-body_down-username').text() == username){
        var temp = '<span class="content-body_down-delete">| <a> Delete</a></span>';
        $(this).find('.content-body_down p').append(temp);
    }
});

$('.content-body_down-delete').on('click',function () {

    var _this = $(this).parents('.content');
    var id = $(_this).attr('data-id');
    $.ajax({
        url : '/deleteBlog',
        type : 'DELETE',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data : {
            id : id
        },
        success : function (res) {
            $(_this).slideUp();
            $(_this).remove();
        }
    });
});