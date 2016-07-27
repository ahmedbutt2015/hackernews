@extends('layout')
@section('content')
    <?php
        $blogs = array($blog);
    ?>
    @include('view.ShowBlog')
    <div class="addComment">
        <div class="form-group">
            <label  class="col-sm-2 control-label">Comment</label>
            <div class="col-sm-8">
                <textarea required name="comment" class="form-control addCommentText"   rows="5" placeholder="New Comment"></textarea>
            </div>
        </div>
    </div>
    <div class="comments">
        @foreach($comments as $i => $comment)
        <div class="comment" data-id="{{ $comment->id }}" vote="{{ $comment->vote or '' }}">
            <div class="vote">
                <div class="upvote glyphicon glyphicon-chevron-up"></div>
                <div class="downvote glyphicon glyphicon-chevron-down"></div>
            </div>
            <div class="content-body">
                <div class="content-body_down">
                    <p>	<span class="point">{{ $comment->point }}</span> points by
                        <span class="content-body_down-username">{{ $comment->username }}</span>
                            {{ Feed::time_elapsed_string($comment->created_at) }} | <span class="content-body_down-comments">
                                {{ isset($comment->reply) ? $comment->reply->count() : 0 }}
                            </span> replies
                    </p>
                </div>
                <div class="content-body_up">
                    <div class="content-body_up-title">
                        <p>	{{ $comment->comment }}</p>
                        <p class="reply">reply</p>
                    </div>
                </div>
            </div>
            @if(isset($comment->reply))
                {{ Helper::reply($comment->reply) }}
            @endif
        </div>
        @endforeach
    </div>
@endsection
@section('files')
    <script src="/js/commentVote.js"></script>
    <script src="/js/temp.js"></script>
    <script src="/js/addComment.js"></script>
@endsection