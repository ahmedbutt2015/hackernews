var _this = this;
$('.upvote').on('click',upvoteEvent);
$('.downvote').on('click',downvoteEvent);
$('.content').each(function () {
    if($(this).attr('vote') == 'upvote'){
        $(this).children().children('.upvote').unbind('click');
        $(this).children().children('.upvote').bind('click',dropUpvoteEvent);
    }
    else if($(this).attr('vote') == 'downvote'){
        // console.log(' assign event');
        $(this).children().children('.downvote').unbind('click');
        $(this).children().children('.downvote').bind('click',dropDownvoteEvent);
    }
});

function upvoteEvent() {
    // console.log(this);
    _this = this;
    var post_id = $(this).parent().parent().attr('data-id');
    if($(this).parent().parent().attr('vote') == 'downvote'){
        $(_this).parent().parent().attr('vote','');
        dropVote(post_id);
        pointUp(post_id,_this);
        $(_this).siblings().unbind('click');
        $(_this).siblings().bind('click', downvoteEvent);
    }
    $(this).parent().parent().attr('vote','upvote');
    vote(post_id,'upvote');
    pointUp(post_id,this);
    $(this).unbind('click');
    $(this).bind('click', dropUpvoteEvent);
}
function downvoteEvent() {
    _this = this;
    var post_id = $(this).parent().parent().attr('data-id');
    if($(this).parent().parent().attr('vote') == 'upvote') {
        $(_this).parent().parent().attr('vote','');
        dropVote(post_id);
        pointDown(post_id,_this);
        $(_this).siblings().unbind('click');
        $(_this).siblings().bind('click', upvoteEvent);
    }
    $(this).parent().parent().attr('vote','downvote');
    vote(post_id,'downvote');
    pointDown(post_id,this);
    $(this).unbind('click');
    $(this).bind('click', dropDownvoteEvent);
}
function dropUpvoteEvent() {
    $(this).parent().parent().attr('vote','');
    var post_id = $(this).parent().parent().attr('data-id');
    dropVote(post_id);
    pointDown(post_id,this);
    $(this).unbind('click');
    $(this).bind('click', upvoteEvent);
}
function dropDownvoteEvent() {
    $(this).parent().parent().attr('vote','');
    var post_id = $(this).parent().parent().attr('data-id');
    dropVote(post_id);
    pointUp(post_id,this);
    $(this).unbind('click');
    $(this).bind('click', downvoteEvent);
}
function dropVote(post_id) {
    $.ajax({
        url : '/vote',
        type : 'DELETE',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data : {
            post_id : post_id
        },
        success : function () {
        }
    });
}
function vote(post_id,vote) {
    $.ajax({
        url : '/vote',
        type : 'POST',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data : {
            post_id : post_id,
            vote : vote
        },
        success : function () {
        }
    });
}
function pointUp(post_id,_this) {
    var point = $(_this).parent().parent().find('.point');
    var temp = parseInt($(point).text());
    temp++;
    $(point).text(temp);
    $.ajax({
        url : '/',
        type : 'PUT',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data : {
            post_id : post_id,
            method : 'upvote'
        },
        success : function () {
        }
    });
}
function pointDown(post_id,_this) {
    var point = $(_this).parent().parent().find('.point');
    var temp = parseInt($(point).text());
    temp--;
    $(point).text(temp);
    $.ajax({
        url : '/',
        type : 'PUT',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data : {
            post_id : post_id,
            method : 'downvote'
        },
        success : function () {
        }
    });
}


var id = 0;
$('.content-body_up-title').on('mouseenter',function () {
    id = $(this).parent().parent().parent().attr('data-id');
});

function blog() {
    location.href = '/blog/'+id;
}

