<?php

namespace App\Http\Controllers;

use App\Models\Vote;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Auth;

class VoteController extends Controller
{
    public function vote(Request $request){
        if($request->isMethod('POST')){
            Vote::where('post_id', '=', $request->input('post_id'))
                ->where('user_id', '=', Auth::user()->id)
                ->delete();
            $vote = new Vote();
            $vote->post_id = $request->input('post_id');
            $vote->user_id = Auth::user()->id;
            $vote->vote = $request->input('vote');
            if($vote->save()){
                return redirect('/');

            }
        }
        elseif ($request->isMethod('DELETE')){
            $vote = Vote::where('post_id', '=', $request->input('post_id'))
                ->where('user_id', '=', Auth::user()->id)
                ->delete();
            if ($vote) {
                return redirect('/');

            }
        }
    }
}
