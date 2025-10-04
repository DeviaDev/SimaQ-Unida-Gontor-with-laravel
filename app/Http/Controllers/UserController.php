<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(){
        $data = array(
            'title'         => 'Data User',
            'menuAdminUser' => 'active',
            'user'          => User::get(),
        );
        return view('admin/user/index',$data);
    }

    public function create(){
        $data = array(
            'title'         => 'Tambah Data User',
            'menuAdminUser' => 'active',
            
        );
        return view('admin/user/create',$data);
    }


    public function store(Request $request){
        $request->validate([
            'nama'     => 'required',
            'email'    => 'required|unique:users,email',
            'password' => 'required|confirmed|min:8'
        ],[
            'nama.required'     => 'Nama Tidak Boleh Kosong',
            'email.required'    => 'Email Tidak Boleh Kosong',
            'email.unique'      => 'Email Sudah Digunakan',
            'password.required' => 'Password Tidak Boleh Kosong',
            'password.confirmed' => 'Password Tidak Sama',
            'password.min' => 'Password minimal 8 karakter',
        ]);
    }
}
