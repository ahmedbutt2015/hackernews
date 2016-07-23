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
            Vote::where('source_id', '=', $request->input('id'))
                ->where('user_id', '=', Auth::user()->id)
                ->where('type', '=',$request->input('type'))
                ->delete();
            $vote = new Vote();
            $vote->source_id = $request->input('id');
            $vote->type = $request->input('type');
            $vote->user_id = Auth::user()->id;
            if($request->input('vote') == 'upvote'){
                $vote->upvote = 1;
                $vote->downvote = 0;
            }else{
                $vote->upvote = 0;
                $vote->downvote = 1;
            }
            $vote->save();
            $point = $this->getPoints($request->input('id'),$request->input('type'));
            echo json_encode(['created' => true, 'point' => $point]);

        }
        elseif ($request->isMethod('DELETE')){
            Vote::where('source_id', '=', $request->input('id'))
                ->where('user_id', '=', Auth::user()->id)
                ->delete();
            $point = $this->getPoints($request->input('id'),$request->input('type'));
            echo json_encode(['created' => true, 'point' => $point]);
        }
    }

    public static function getPoints($id,$type){
        $up = Vote::where('source_id', '=', $id)
            ->where('type', '=', $type)
            ->where('upvote', '=', 1)
            ->count();
        $down = Vote::where('source_id', '=', $id)
            ->where('type', '=', $type)
            ->where('downvote', '=', 1)
            ->count();
        return ($up - $down);
    }
}
