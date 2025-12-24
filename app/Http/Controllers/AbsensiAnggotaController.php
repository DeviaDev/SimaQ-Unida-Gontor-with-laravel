<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Mahasiswi2;

class AbsensiAnggotaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    public function absensiTahfidzMahasiswi(Request $request)
    {
        $query = Mahasiswi2::query();

        if (request('prodi')) {
            $query->where('prodi', request('prodi'));
        }

        if (request('semester')) {
            $query->where('semester', request('semester'));
        }

        if (request('kelompok')) {
            $query->where('kelompok', request('kelompok'));
        }

        $mahasiswi2 = $query->get();

        $kelompokList = [];

        if (request('prodi') && request('semester')) {
            $kelompokList = Mahasiswi2::where('prodi', request('prodi'))
                ->where('semester', request('semester'))
                ->select('kelompok')
                ->distinct()
                ->orderBy('kelompok')
                ->pluck('kelompok');
        }

        return view('absensi.anggota.tahfidz.tahfidzmahasiswi', [
            'title' => 'Absensi Tahfidz Mahasiswi',
            'mahasiswi' => $mahasiswi2,
            'menuAbsensiAnggota' => 'active',
            'menuAbsensiTahfidz' => 'active',
            'tahfidzmahasiswi' => 'active',
            'kelompokList' => $kelompokList
        ]);
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
