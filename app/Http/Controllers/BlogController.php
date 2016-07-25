<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\Comment;
use App\Models\Vote;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Auth;

class BlogController extends Controller
{
    public function blog($id){
        
        $blogs = Blog::find($id);
        $blogs->point = VoteController::getPoints($blogs->id,'post');
        $c =  Vote::where('source_id','=',$blogs->id)
            ->where('user_id','=',Auth::user()->id)
            ->where('type','=','post')
            ->get();
        if(count($c) >0){
            $blogs->vote = 'downvote';
            if($c[0]->upvote == 1){
                $blogs->vote = 'upvote';
            }
        }
        $comments = Comment::where('post_id','=',$blogs->id)
                        ->join('users', 'comments.user_id', '=', 'users.id')
                        ->orderby('comments.id','desc')->get();
        $id = Comment::orderby('id','desc')->where('post_id','=',$blogs->id)->get();
        for ($i=0;$i<$id->count();$i++){
            $point =  Vote::where('source_id','=',$id[$i]->id)
                ->where('user_id','=',Auth::user()->id)
                ->where('type','=','comment')
                ->get();
            if(count($point) >0){
                $comments[$i]->vote = 'downvote';
                if($point[0]->upvote == 1){
                    $comments[$i]->vote = 'upvote';
                }
            }
            $comments[$i]->point = VoteController::getPoints($id[$i]->id,'comment');
            $c = Comment::where('comment_id','=',$id[$i]->id)->count();
            if($c > 0){
                $comments[$i]->reply = Comment::where('comment_id','=',$id[$i]->id)->get();
                $this->checkReply($comments[$i]->reply);
            }
        }
//        dd($comments);
        return view('blog')->withBlog($blogs)->withComments($comments)->withId($id);
    }
    private function checkReply($reply){
        for ($i=0;$i<$reply->count();$i++){
            $point =  Vote::where('source_id','=',$reply[$i]->id)
                ->where('user_id','=',Auth::user()->id)
                ->where('type','=','comment')
                ->get();
            if(count($point) >0){
                $reply[$i]->vote = 'downvote';
                if($point[0]->upvote == 1){
                    $reply[$i]->vote = 'upvote';
                }
            }
            $reply[$i]->point = VoteController::getPoints($reply[$i]->id,'comment');
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
            $comment->comment = $request->input('comment');
            if($comment->save()){
                return redirect('/blog/'.$request->input('post_id'));
            }else{
                return redirect('/blog/'.$request->input('post_id'))->withErrors($comment->errors());
            }
        }
    }
}
