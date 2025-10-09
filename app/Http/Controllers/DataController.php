<?php

namespace App\Http\Controllers;

use App\Models\Dosen;
use App\Models\Pengurus;
use App\Models\Mahasiswi;
use App\Models\Muhafidzoh;
use Illuminate\Http\Request;

class DataController extends Controller
{
    /**
     * Display a listing of the resource.
     */
   public function index(){
        
    }

    public function pengurus(){
        $data = array(
            'title'         => ' Data Pengurus',
            "menuData"  =>  "active",
            "menuPengurus"  =>  "active",
            'data'          => Pengurus::all(),
        );
        return view('data/pengurus',$data);
    }



    public function mahasiswi(){
        $data = array(
            'title'         => ' Data Mahasiswi',
            "menuData"  =>  "active",
            "menuMahasiswi"  =>  "active",
            'data'          => Mahasiswi::all(),
            'mahasiswi' => Mahasiswi::with(['dosen', 'muhafidzoh', 'kelompok', 'tempat'])->get(),
        );
        return view('data/mahasiswi',$data);
    }



    public function muhafidzoh(){
        $data = array(
            'title'         => ' Data Muhafidzoh',
            "menuData"  =>  "active",
            "menuMuhafidzoh"  =>  "active",
            'data'          => Muhafidzoh::all(),
            'Muhafidzoh' => Muhafidzoh::with([ 'kelompok', 'tempat'])->get(),
        );
        return view('data/muhafidzoh',$data);
    }



    public function dosen(){
        $data = array(
            'title'         => ' Data Dosen Pembimbing',
            "menuData"  =>  "active",
            "menuDosen"  =>  "active",
            'data'          => Dosen::all(),
            'dosen' => Dosen::with([ 'kelompok', 'muhafidzoh','tempat'])->get(),
        );
        return view('data/dosen',$data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    
}
