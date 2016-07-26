@extends('layout')

@section('title')
    - Register
@endsection

@section('content')
    <div class="form">
        <form class="form-signin" method="POST" action="/register">
            <h2 class="form-signin-heading">Please sign up</h2>
            <input type="username" name="username" class="form-control " placeholder="username" required>
            @if($errors->has('username'))
                {{$errors->first('username')}}
            @endif
            <input type="email" name="email" class="form-control " placeholder="Email" required>
            @if($errors->has('email'))
                {{$errors->first('email')}}
            @endif
            <input type="password" name="password"  class="form-control " placeholder="Password" required>
            @if($errors->has('password'))
                {{$errors->first('password')}}
            @endif
            {{csrf_field()}}
            <input class="btn btn-lg btn-danger btn-block" type="submit" value="Sign up">
        </form>
    </div>
@endsection