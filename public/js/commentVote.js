$('.comment .upvote').on('click',upvoteEvent);
$('.comment .downvote').on('click',downvoteEvent);
$('.comment').each(function () {

    if($(this).attr('vote') == 'upvote'){
        console.log(' assign event comm');
        $(this).children().children('.upvote').unbind('click');
        $(this).find('.glyphicon-chevron-up').first().css('color' , 'red');
        $(this).children().children('.upvote').bind('click',dropUpvoteEvent);
    }
    else if($(this).attr('vote') == 'downvote'){
        console.log(' assign event comm');
        $(this).find('.glyphicon-chevron-down').first().css('color' , 'red');
        $(this).children().children('.downvote').unbind('click');
        $(this).children().children('.downvote').bind('click',dropDownvoteEvent);
    }
});

function upvoteEvent() {
    console.log(' assign event comm');

    var id = $(this).parents('.comment').attr('data-id');
    $(this).parents('.comment').attr('vote','upvote');
    vote('comment',id,'upvote',this);
}
function downvoteEvent(){
    console.log(' assign event comm');

    console.log('Downvote');
    var id = $(this).parents('.comment').attr('data-id');
    $(this).parents('.comment').attr('vote','downvote');
    vote('comment',id,'downvote',this);
}
function vote(type,id,vote,_this) {
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
                $(_this).bind('click', dropUpvoteEvent);
                $(_this).siblings().bind('click', downvoteEvent);
            }else{
                $(_this).bind('click', dropDownvoteEvent);
                $(_this).siblings().bind('click', upvoteEvent);
            }
        }
    });
}
function dropVote(type,id,_this,vote) {
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
                $(_this).bind('click', upvoteEvent);
                $(_this).siblings().bind('click', downvoteEvent);
            }else{
                $(_this).bind('click', downvoteEvent);
                $(_this).siblings().bind('click', upvoteEvent);
            }
        }
    });
}

function dropUpvoteEvent() {
    console.log('Drop upvote');
    $(this).parent().parent().attr('vote','');
    var id = $(this).parents('.comment').attr('data-id');
    dropVote('comment',id,this,'upvote');
}
function dropDownvoteEvent() {
    console.log('Drop downvote');
    $(this).parent().parent().attr('vote','');
    var id = $(this).parents('.comment').attr('data-id');
    dropVote('comment',id,this,'downvote');
}

