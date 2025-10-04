<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AbsensiPengurusController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }



     public function pengurusLailatu(){
        $data = array(
            'title'         => 'Malam Lailatu Tahfidz',
            'menuAbsensiPengurus' => 'active',
            'pengurusLailatu' => 'active',
        );
        return view('absensi/pengurus/lailatu',$data);
    }



     public function pengurusTilawah(){
        $data = array(
            'title'         => 'Absensi Tilawah',
            'menuAbsensiPengurus' => 'active',
            'pengurusTilawah' => 'active',
        );
        return view('absensi/pengurus/tilawah',$data);
    }


     public function pengurusTaujihat(){
        $data = array(
            'title'         => 'Absensi Taujihat',
            'menuAbsensiPengurus' => 'active',
            'pengurusTaujihat' => 'active',
        );
        return view('absensi/pengurus/taujihat',$data);
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
