@extends('layout')
@section('content')
    <div class="content" data-id="{{ $blog->id }}">
        <div class="no">{{ 1 }}.</div>
        <div class="vote">
            <div class="upvote glyphicon glyphicon-chevron-up"></div>
            <div class="downvote glyphicon glyphicon-chevron-down"></div>
        </div>
        <div class="content-body">
            <div class="content-body_up">
                <div class="content-body_up-title">
                    <p>	{{ $blog->title }}</p>
                </div>
                <div class="content-body_up-site">
                    (<em>{{ $blog->url }}</em>)
                </div>
            </div>
            <div class="content-body_down">
                <p>	<span class="point">{{ $blog->point }}</span> points by
                    <span class="content-body_down-username">{{ $blog->username }}</span>
                    {{ Feed::time_elapsed_string($blog->created_at) }} | <span class="content-body_down-comments">{{ $comments->count() }} comments</span></p>
            </div>
        </div>
    </div>
    <div class="addComment">
        <form class="form-horizontal" action="/addComment" method="POST">
            <input type="hidden"  name="post_id" value="{{ $blog->id }}">
            <div class="form-group">
                <label  class="col-sm-2 control-label">Comment</label>
                <div class="col-sm-8">
                    <textarea name="comment" class="form-control"   rows="5" placeholder="New Comment"></textarea>
                </div>
            </div>
            {{ csrf_field() }}
            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-8">
                    <button type="submit" class="btn btn-default">Add</button>
                </div>
            </div>
        </form>
    </div>

    <div class="a">
        <div class="b">

        </div>
        <div class="c">
        </div>
    </div>

    <div class="comments">
        @foreach($comments as $i => $comment)
        <div class="comment" data-id="{{ $id[$i]->id }}">
            <div class="vote">
                <div class="upvote glyphicon glyphicon-chevron-up"></div>
                <div class="downvote glyphicon glyphicon-chevron-down"></div>
            </div>
            <div class="content-body">
                <div class="content-body_down">
                    <p>	<span class="point">{{ $comment->point }}</span> points by
                        <span class="content-body_down-username">{{ $comment->username }}</span>
                        {{ Feed::time_elapsed_string($comment->created_at) }} | <span class="content-body_down-comments">
                                        @if (isset($comment->reply)){{$comment->reply->count() }}@else 0 @endif replies
                                    </span>
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
                @foreach($comment->reply as $comment)
                    <div class="comment " data-id="{{ $comment->id }}">
                        <div class="vote">
                            <div class="upvote glyphicon glyphicon-chevron-up"></div>
                            <div class="downvote glyphicon glyphicon-chevron-down"></div>
                        </div>
                        <div class="content-body">
                            <div class="content-body_down">
                                <p>	<span class="point">{{ $comment->point }}</span> points by
                                    <span class="content-body_down-username">{{ $comment->username }}</span>
                                        {{ Feed::time_elapsed_string($comment->created_at) }} | <span class="content-body_down-comments">
                                        @if (isset($comment->reply)){{$comment->reply->count() }}@else 0 @endif replies
                                    </span>
                                </p>
                            </div>
                            <div class="content-body_up">
                                <div class="content-body_up-title">
                                    <p>	{{ $comment->comment }}</p>
                                    <p class="reply">reply</p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
        @endforeach
    </div>
@endsection
