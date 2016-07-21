<?php

namespace App\Http\Controllers;
use App\Models\Vote;
use DateTime;
use App\Models\Blog;
use App\Models\Comment;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Auth;

class FeedController extends Controller
{
    public function feedPage(Request $request){

        if($request->isMethod('GET')){
            $blogs = Blog::orderBy('id','desc')->get();
            for ($i=0;$i<$blogs->count();$i++){
                $blogs[$i]->count = Comment::where('post_id','=',$blogs[$i]->id)->count();
                $c =  Vote::where('post_id','=',$blogs[$i]->id)
                                            ->where('user_id','=',Auth::user()->id)
                                            ->count();
                if($c > 0){
                    $blogs[$i]->vote = Vote::where('post_id','=',$blogs[$i]->id)
                                            ->where('user_id','=',Auth::user()->id)
                                            ->get()->pluck('vote');
                }
            }
            return view('feed')->withBlogs($blogs);
        }
        elseif ($request->isMethod('PUT')){
            $id = $request->input('post_id');
            $blog = Blog::find($id);
            $point = $blog->point;
            if($request->input('method') == 'upvote'){
                $blog->point = ++$point;
            }
            else{
                $blog->point = --$point;
            }
            $blog->save();
        }
        else{
            return 'Error Page not Found';
        }
    }


    public function submit(Request $request){

        if($request->isMethod('GET')){
            return view('submit');
        }
        else if($request->isMethod('POST')){

            $title = $request->input('title');
            $url = $request->input('url');
            $blog = new Blog();
            $blog->title = $title;
            $blog->url = $url;
            $blog->point = 0;
            $blog->username = Auth::user()->username;
            if($blog->save()){
                return redirect('/');
            }else{
                return redirect('/submit')->withErrors($blog->errors());
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
