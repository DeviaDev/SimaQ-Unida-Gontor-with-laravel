<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class pengurusController extends Controller
{
    public function pengurus(){
        $data = array(
            'title'         => 'pengurus',
            "menuData"  =>  "active",
            "menuPengurus"  =>  "active",
        );
        return view('pengurus',$data);
    }
}

