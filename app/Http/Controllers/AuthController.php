<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Validator;
use Illuminate\Http\Request;

use App\Http\Requests;

class AuthController extends Controller
{
    public function login(Request $request){
        if($request->isMethod('POST')){
            $email = $request->input('email');
            $password = $request->input('password');

            $validator = Validator::make($request->all(), [
                'email' =>  'required|email',
                'password'  =>  'required|min:6',
            ]);

            if($validator->passes()){
                if(Auth::attempt(['email' => $email , 'password' => $password])){
                    return redirect('/');
                }else{
                    session()->flash('status', 'Incorrect Credentials!');
                    return redirect('/login');
                }
            }else{
                return redirect('/login')
                    ->withErrors($validator->errors());
            }
        }

        if($request->isMethod('GET')){
            return view('login');
        }
    }

    public function register(Request $req){
        if($req->isMethod('GET')){
            return view('register');
        }
        if($req->isMethod('POST')){
            $email = $req->input('email');
            $password = bcrypt($req->input('password'));
            $username = $req->input('username');
            $validator = Validator::make($req->all(),[
                'email' => 'required|email|unique:users',
                'username' => 'required|unique:users',
                'password' => 'required|min:6'

            ]);
            if($validator->passes()){

                $user = new User([
                    'email' => $email,
                    'password' => $password,
                    'username' => $username
                ]);
                if($user->save()){
                    return redirect('/login');
                }
            }
            else{
                return redirect('/register')->withErrors($validator->errors());
            }
        }

    }
}
