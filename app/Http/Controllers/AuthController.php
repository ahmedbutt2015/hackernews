<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Validator;
use Illuminate\Http\Request;

use App\Http\Requests;

class AuthController extends Controller{

    public function login(Request $request){

        if($request->isMethod('POST')){
            $email = $request->input('email');
            $password = $request->input('password');

            $validator = Validator::make($request->all(), [
                'email' =>  'required|email',
                'password'  =>  'required|min:6',
            ]);

            if($validator->passes()){
                if(Auth::attempt(['email' => $email , 'password' => $password ])){
                    if(!Auth::user()->user_verified){
                        Auth::logout();
                        session()->flash('status', 'You are not verified. First verify your email!');
                        return redirect('/login');
                    }
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

            $token = str_random(15);
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
                    'user_verified' => 0,
                    'verify_token' => $token,
                    'username' => $username
                ]);

                if($user->save()){
                    Mail::send('emails.reminder', ['user' => $user], function ($m) use ($user){
                        $m->from('bahtasham@gmail.com', 'Acme');

                        $m->to($user->email, $user->username)->subject('Confirmation Email!');
                    });

                    session()->flash('email_status', 'Email has been Sent !!');
                    return redirect('/login');
                }
            }
            else{
                return redirect('/register')->withErrors($validator->errors());
            }
        }
    }

    public function verifyUser($token){
        $user = User::where('verify_token', '=', $token);

        if($user->count()){
            $user = $user->first();

            $user->verify_token = '';
            $user->user_verified = 1;

            if($user->save()){
                return redirect('/');
            }
        }
    }
}
