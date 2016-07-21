<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\Comment;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Auth;

class BlogController extends Controller
{
    public function blog($id)
    {
        $data = Blog::find($id);
        $comments = Comment::where('post_id','=',$data->id)
                        ->join('users', 'comments.user_id', '=', 'users.id')
                        ->orderby('comments.id','desc')->get();
        $id = Comment::orderby('id','desc')->where('post_id','=',$data->id)->get();
        for ($i=0;$i<$id->count();$i++){
            $c = Comment::where('comment_id','=',$id[$i]->id)->count();
            if($c > 0){
                $comments[$i]->reply = Comment::where('comment_id','=',$id[$i]->id)->get();
                $this->checkReply($comments[$i]->reply);
            }
        }
//        dd($comments);
        return view('blog')->withBlog($data)->withComments($comments)->withId($id);
    }
    private function checkReply($reply){
        for ($i=0;$i<$reply->count();$i++){
            $c = Comment::where('comment_id','=',$reply[$i]->id)->count();
            if($c > 0){
                $reply[$i]->reply = Comment::where('comment_id','=',$reply[$i]->id)->get();
                $this->checkReply($reply[$i]->reply);
            }
        }

    }
    public function addComment(Request $request){

        if($request->isMethod('POST')){
            $comment = new Comment();
            $comment->post_id = $request->input('post_id');
            $comment->user_id = Auth::user()->id;
            $comment->point = 0;
            $comment->comment = $request->input('comment');
            if($comment->save()){
                return redirect('/blog/'.$request->input('post_id'));
            }else{
                return redirect('/blog/'.$request->input('post_id'))->withErrors($comment->errors());
            }
        }
    }
    public function addReply(Request $request){

        if($request->isMethod('POST')){
            $comment = new Comment();
            $comment->comment_id = $request->input('comment_id');
            $comment->user_id = Auth::user()->id;
            $comment->point = 0;
            $comment->comment = $request->input('comment');
            if($comment->save()){
                return redirect('/blog/'.$request->input('post_id'));
            }else{
                return redirect('/blog/'.$request->input('post_id'))->withErrors($comment->errors());
            }
        }
    }
}
