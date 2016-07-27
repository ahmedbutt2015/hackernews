<?php

namespace App\Http\Controllers;
use App\Models\Vote;
use DateTime;
use App\Models\Blog;
use App\Models\Comment;
use Validator;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Auth;

class FeedController extends Controller
{
    public function feedPage(Request $request){

        $blogs = Blog::orderBy('id','desc')->get();
        for ($i=0;$i<$blogs->count();$i++){
            $blogs[$i]->count = Comment::where('post_id','=',$blogs[$i]->id)->count();
            $blogs[$i]->point = VoteController::getPoints($blogs[$i]->id,'post');
            $c =  Vote::where('source_id','=',$blogs[$i]->id)
                    ->where('user_id','=',Auth::user()->id)
                    ->where('type','=','post')
                    ->get();
            if(count($c) >0){
                $blogs[$i]->vote = 'downvote';
                if($c[0]->upvote == 1){
                    $blogs[$i]->vote = 'upvote';
                }
            }
        }
        return view('feed')->withBlogs($blogs);
    }


    public function submit(Request $request){

        if($request->isMethod('GET')){
            return view('submit');
        }
        else if($request->isMethod('POST')){
            $validator = Validator::make($request->all(),[
                'url' => 'URL:blogs',
            ]);
            if($validator->passes()){
                $title = $request->input('title');
                $url = $request->input('url');
                $blog = new Blog();
                $blog->title = $title;
                $blog->url = $url;
                $blog->username = Auth::user()->username;
                if($blog->save()){
                    return redirect('/');
                }else{
                    return redirect('/submit')->withErrors($blog->errors());
                }
            }else{
                return redirect('/submit')->withErrors($validator->errors());
            }
        }
    }

    static function time_elapsed_string($datetime, $full = false) {
        $now = new DateTime;
        $ago = new DateTime($datetime);
        $diff = $now->diff($ago);

        $diff->w = floor($diff->d / 7);
        $diff->d -= $diff->w * 7;

        $string = array(
            'y' => 'year',
            'm' => 'month',
            'w' => 'week',
            'd' => 'day',
            'h' => 'hour',
            'i' => 'minute',
            's' => 'second',
        );
        foreach ($string as $k => &$v) {
            if ($diff->$k) {
                $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
            } else {
                unset($string[$k]);
            }
        }

        if (!$full) $string = array_slice($string, 0, 1);
        return $string ? implode(', ', $string) . ' ago' : 'just now';
    }
}
