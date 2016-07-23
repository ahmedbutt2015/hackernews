@extends('layout')
@section('content')
    @foreach($blogs as $index => $blog)
        <div class="content" data-id="{{ $blog->id }}" vote=@if(isset($blog->vote)){{$blog->vote}}@endif >
            <div class="no">{{ $index+1 }}.</div>
            <div class="vote">
                <div class="upvote glyphicon glyphicon-chevron-up"></div>
                <div class="downvote glyphicon glyphicon-chevron-down"></div>
            </div>
            <div class="content-body">
                <div class="content-body_up">
                    <div class="content-body_up-title" onclick="blog()">
                        <p>	{{ $blog->title }}</p>
                    </div>
                    <div class="content-body_up-site">
                        <a target="_blank" href="{{ $blog->url }}">(<em>{{ parse_url($blog->url, PHP_URL_HOST) }}</em>)</a>
                    </div>
                </div>
                <div class="content-body_down">
                    <p>	<span class="point">{{ $blog->point }}</span> points by
                        <span class="content-body_down-username">{{ $blog->username }}</span>
                        {{ Feed::time_elapsed_string($blog->created_at) }} | <span class="content-body_down-comments">{{ $blog->count }} comments</span></p>
                </div>
            </div>
        </div>
    @endforeach
@endsection
