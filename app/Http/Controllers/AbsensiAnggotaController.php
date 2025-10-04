<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AbsensiAnggotaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }


    public function absensiTahfidzMahasiswi(){
        $data = array(
            'title'         => 'Absensi Tahfidz Mahasiswi',
            'menuAbsensiAnggota' => 'active',
            'menuAbsensiTahfidz' => 'active',
            'tahfidzmahasiswi' => 'active',
        );
        return view('absensi.anggota.tahfidz.tahfidzmahasiswi
',$data);
    }

    public function absensiTahfidzMuhafidzoh(){
        $data = array(
            'title'         => 'Absensi Tahfidz Muhafidzoh',
            'menuAbsensiAnggota' => 'active',
            'menuAbsensiTahfidz' => 'active',
            'tahfidzmuhafidzoh' => 'active',
        );
        return view('absensi/anggota/tahfidz/tahfidzmuhafidzoh',$data);
    }


    public function absensiTilawahMahasiswi(){
        $data = array(
            'title'         => 'Absensi Tilawah Mahasiswi',
            'menuAbsensiAnggota' => 'active',
            'menuAbsensiTilawah' => 'active',
            'tilawahmahasiswi' => 'active',
        );
        return view('absensi/anggota/tilawah/tilawahmahasiswi',$data);
    }

    public function absensiTilawahMuhafidzoh(){
        $data = array(
            'title'         => 'Absensi Tilawah Muhafidzoh',
            'menuAbsensiAnggota' => 'active',
            'menuAbsensiTilawah' => 'active',
            'tilawahmuhafidzoh' => 'active',
        );
        return view('absensi/anggota/tilawah/tilawahmuhafidzoh',$data);
    }

    public function absensiTilawahStaf(){
        $data = array(
            'title'         => 'Absensi Tilawah Staf',
            'menuAbsensiAnggota' => 'active',
            'menuAbsensiTilawah' => 'active',
            'tilawahstaf' => 'active',
        );
        return view('absensi/anggota/tilawah/tilawahstaf',$data);
    }

    public function absensiTilawahDosen(){
        $data = array(
            'title'         => 'Absensi Tilawah Dosen',
            'menuAbsensiAnggota' => 'active',
            'menuAbsensiTilawah' => 'active',
            'tilawahdosen' => 'active',
        );
        return view('absensi/anggota/tilawah/tilawahdosen',$data);
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
