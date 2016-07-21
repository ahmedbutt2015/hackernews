@extends('layout')

@section('title')
    - Submit
@endsection

@section('content')
    <form class="form-horizontal" action="/submit" method="POST">
        <div class="form-group">
            <label for="inputEmail3" class="col-sm-2 control-label">Title</label>
            <div class="col-sm-8">
                <input name="title" class="form-control" placeholder="Title">
            </div>
        </div>
        <div class="form-group">
            <label  class="col-sm-2 control-label">URL</label>
            <div class="col-sm-8">
                <input type="text" name="url" class="form-control" placeholder="URL">
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-offset-2 col-sm-8">
                <label>
                        Or
                </label>
            </div>
        </div>
        <div class="form-group">
            <label  class="col-sm-2 control-label">Text</label>
            <div class="col-sm-8">
                <textarea disabled name="body" class="form-control"  cols="30" rows="10" placeholder="Blog Body"></textarea>
            </div>
        </div>
        {{ csrf_field() }}
        <div class="form-group">
            <div class="col-sm-offset-2 col-sm-8">
                <button type="submit" class="btn btn-default">Submit</button>
            </div>
        </div>
    </form>
    <div style="height: 50px"></div>
@endsection