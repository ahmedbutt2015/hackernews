<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\Comment;
use App\Models\User;
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
        $comments = Comment::where('post_id','=',$blogs->id)->orderby('id','desc')->get();
        for ($i=0;$i<$comments->count();$i++){
            $temp = User::where('id','=',$comments[$i]->user_id)->get()->pluck('username');
            $comments[$i]->username = $temp[0];
        }
        $this->checkReply($comments);

        $blogs->count = $comments->count();
//        dd($comments);
        return view('blog')->withBlog($blogs)->withComments($comments);
    }

    private function addUsername($c){
        for ($i=0;$i<$c->count();$i++) {
            $temp = User::where('id', '=', $c[$i]->user_id)->first();
            $c[$i]->username = $temp->username;
        }
    }
    public function deleteBlog(Request $request){

        Blog::where('id', '=', $request->input('id'))
            ->where('username', '=', Auth::user()->username)
            ->delete();
    }

    public function deleteComment(Request $request){

        $this->deleteSubcomment($request->input('id'));
        Comment::where('id', '=', $request->input('id'))
            ->where('user_id', '=', Auth::user()->id)
            ->delete();
    }

    private function deleteSubcomment($id){

        $temp = Comment::where('comment_id', '=', $id )->get();
        if( count($temp) ){
            foreach ($temp as $i)
                $this->deleteSubcomment($i->id);
        }
        Comment::where('comment_id', '=', $id )->delete();
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
                $this->addUsername($reply[$i]->reply);
                $this->checkReply($reply[$i]->reply);
            }
        }

    }
    public function addComment(Request $request){

        $comment = new Comment();
        $comment->post_id = $request->input('post_id');
        $comment->user_id = Auth::user()->id;
        $comment->comment = $request->input('comment');
        $comment->save();
        $id = Comment::orderby('id','desc')->get();
        echo json_encode(array("id" => $id[0]->id ));
    }
    public function addReply(Request $request){

        $comment = new Comment();
        $comment->comment_id = $request->input('comment_id');
        $comment->user_id = Auth::user()->id;
        $comment->comment = $request->input('comment');
        $comment->save();
        $id = Comment::orderby('id','desc')->get();
        echo json_encode(array("id" => $id[0]->id ));

    }
}
