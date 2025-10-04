<?php

namespace App\Http\Controllers;

use Illuminate\Auth\Events\Logout;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function login(){

        return view('auth/login');
    }

    public function loginProses(Request $request){
        $request->validate([
            'email'     => 'required',
            'password'     => 'required',
        ]);

    $data = array(
        'email'     => $request->email,
        'password'  => $request->password,
    );

    if(Auth::attempt($data)){
        return redirect()->route('dashboard')->with('success','Login Successfully ');
    }else{
        return redirect()->back()->with('error','incorrect email or password');
    }
    }

    public function logout(){
        Auth::logout();

        return redirect()->route('login')->with('success','Logout Successfully');
        }
}
