<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Hacker News @yield('title')</title>
    @if(Auth::check())
        <meta name="csrf-token" content="{{ csrf_token() }}" />
        <meta name="username" content="{{ Auth::user()->username }}" />
        <meta name="user_id" content="{{ Auth::user()->id }}" />
    @endif
    <link rel="stylesheet" href="/bower_components/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="/css/app.css">
</head>
<body>
<div class="wrapper">
    <div class="header">
        <div class="header-title">
            <div class="header-title_logo"><a href="/"><img src="/images/y18.gif" ></a></div>
            <div class="header-title_text"><a href="/">Hacker News</a>
                @if(Auth::check())
                    <span class="header-title_submit"><a href="/submit">| submit |</a></span>
                @else
                    <span class="header-title_submit"><a href="/login">| login |</a></span>
                    <span class="header-title_submit"><a href="/register">| register |</a></span>
                @endif
            </div>
        </div>
        @if(Auth::check())
            <div class="username"><a href="/logout">logout</a></div>
            <div class="username">{{Auth::user()->username}}</div>
        @endif
    </div>
    @yield('content')
</div>
<script src="/bower_components/jquery/dist/jquery.min.js"></script>
<script src="/js/vote.js"></script>
<script src="/js/reply.js"></script>
<script src="/js/deletePost.js"></script>
<script src="/js/deleteComment.js"></script>
@yield('files')
</body>
</html>