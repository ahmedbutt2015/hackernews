$('.comment .upvote').on('click',upvoteEventC);
$('.comment .downvote').on('click',downvoteEventC);
$('.comment').each(function () {

    if($(this).attr('vote') == 'upvote'){
        console.log(' assign event comm');
        $(this).children().children('.upvote').unbind('click');
        $(this).find('.glyphicon-chevron-up').first().css('color' , 'red');
        $(this).children().children('.upvote').bind('click',dropUpvoteEventC);
    }
    else if($(this).attr('vote') == 'downvote'){
        console.log(' assign event comm');
        $(this).find('.glyphicon-chevron-down').first().css('color' , 'red');
        $(this).children().children('.downvote').unbind('click');
        $(this).children().children('.downvote').bind('click',dropDownvoteEventC);
    }
});

function upvoteEventC() {
    console.log(' assign event comm');

    var id = $(this).parents('.comment').attr('data-id');
    $(this).parents('.comment').attr('vote','upvote');
    voteC('comment',id,'upvote',this);
}
function downvoteEventC(){
    console.log('Downvote');
    var id = $(this).parents('.comment').attr('data-id');
    $(this).parents('.comment').attr('vote','downvote');
    voteC('comment',id,'downvote',this);
}
function voteC(type,id,vote,_this) {
    $(_this).unbind('click');
    $(_this).siblings().unbind('click');
    $(_this).siblings().css('color' , '#080808');
    $(_this).css('color' , 'red');
    $.ajax({
        url : '/vote',
        type : 'POST',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data : {
            id : id,
            type : type,
            vote : vote
        },
        success : function (res) {
            res = JSON.parse(res);
            $(_this).parent().siblings('.content-body').find('.point').text(res.point);
            if(vote == 'upvote'){
                $(_this).bind('click', dropupvoteEventC);
                $(_this).siblings().bind('click', downvoteEventC);
            }else{
                $(_this).bind('click', dropDownvoteEventC);
                $(_this).siblings().bind('click', upvoteEventC);
            }
        }
    });
}
function dropVoteC(type,id,_this,vote) {
    $(_this).unbind('click');
    $(_this).siblings().unbind('click');
    $(_this).css('color' , '#080808');
    $.ajax({
        url : '/vote',
        type : 'DELETE',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data : {
            type :type,
            id : id
        },
        success : function (res) {
            res = JSON.parse(res);
            $(_this).parent().siblings('.content-body').find('.point').text(res.point);
            if(vote == 'upvote'){
                $(_this).bind('click', upvoteEventC);
                $(_this).siblings().bind('click', downvoteEventC);
            }else{
                $(_this).bind('click', downvoteEventC);
                $(_this).siblings().bind('click', upvoteEventC);
            }
        }
    });
}

function dropUpvoteEventC() {
    console.log('Drop upvote');
    $(this).parent().parent().attr('vote','');
    var id = $(this).parents('.comment').attr('data-id');
    dropVoteC('comment',id,this,'upvote');
}
function dropDownvoteEventC() {
    console.log('Drop downvote');
    $(this).parent().parent().attr('vote','');
    var id = $(this).parents('.comment').attr('data-id');
    dropVoteC('comment',id,this,'downvote');
}

