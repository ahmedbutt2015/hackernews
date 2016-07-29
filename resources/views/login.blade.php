@extends('layout')
@section('title')
    - Login
@endsection
@section('content')
    <div class="form">
        @if(Session::has('email_status'))
            {{Session::get('email_status')}}
        @endif
        <form class="form-signin" method="POST" action="/login">
            <h2 class="form-signin-heading">Please sign in</h2>
            <label for="inputUserName" class="sr-only">Email</label>
            <input type="email" name="email" class="form-control" placeholder="email" required>
        @if($errors->has('email'))
                {{$errors->first('email')}}
            @endif
            <label for="inputPassword" class="sr-only">Password</label>
            {{csrf_field()}}
            <input type="password" name="password" class="form-control" placeholder="Password" required>
            @if($errors->has('password'))
                {{$errors->first('password')}}
            @endif
            @if(Session::has('status'))
                {{Session::get('status')}}
            @endif


            <button class="btn btn-lg btn-danger btn-block" type="submit">Sign in</button>
        </form>
    </div>
@endsection