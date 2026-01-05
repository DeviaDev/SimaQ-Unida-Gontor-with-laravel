<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Mahasiswi;   // ✅ Pakai Model Baru
use App\Models\Muhafidzoh;  // ✅ Pakai Model Baru
use App\Models\KelompokLT;  // ✅ Tambah Model Kelompok
use App\Models\Tempat;      // ✅ Tambah Model Tempat (untuk Gedung)
use App\Models\Mahatilawah;

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
        // 1. Gunakan Model Mahasiswi yang baru
        $query = Mahasiswi::query();

        if ($request->filled('prodi')) {
            $query->where('prodi', $request->prodi);
        }

        if ($request->filled('semester')) {
            $query->where('semester', $request->semester);
        }

        if ($request->filled('kelompok')) {
            // ✅ Filter berdasarkan id_kelompok (bukan string 'kelompok')
            $query->where('id_kelompok', $request->kelompok);
        }

        // Ambil data beserta relasi kelompok agar namanya muncul
        $mahasiswi = $query->with('kelompok')->orderBy('nama_mahasiswi')->get();

        // 2. Perbaiki Logika List Kelompok (Agar return Object, bukan String)
        $kelompokList = [];

        if ($request->filled('prodi') && $request->filled('semester')) {
            // Ambil data dari tabel kelompok_lt yang memiliki mahasiswa di prodi/smt tersebut
            $kelompokList = KelompokLT::whereHas('mahasiswi', function($q) use ($request) {
                $q->where('prodi', $request->prodi)
                  ->where('semester', $request->semester);
            })
            ->orderBy('kode_kelompok')
            ->get(); 
            // 👆 Pakai get() agar hasilnya Object, jadi di Blade bisa panggil $k->id_kelompok
        }

        return view('absensi.anggota.tahfidz.tahfidzmahasiswi', [
            'title' => 'Absensi Tahfidz Mahasiswi',
            'mahasiswi' => $mahasiswi,
            'menuAbsensiAnggota' => 'active',
            'menuAbsensiTahfidz' => 'active',
            'tahfidzmahasiswi' => 'active',
            'kelompokList' => $kelompokList
        ]);
    }

    public function absensiTahfidzMuhafidzoh(Request $request)
    {
        // Gunakan Model Muhafidzoh dengan relasi Tempat & Kelompok
        $query = Muhafidzoh::with(['tempat', 'kelompok']);

        // 🔎 Filter GEDUNG (Via Relasi Tempat)
        if ($request->filled('gedung')) {
            // Karena 'gedung' ada di tabel 'tempat' (kolom nama_tempat)
            $query->whereHas('tempat', function($q) use ($request) {
                $q->where('nama_tempat', $request->gedung);
            });
        }

        // Data utama (Order by nama_muhafidzoh)
        $muhafidzoh = $query->orderBy('nama_muhafidzoh')->get();

        // Dropdown daftar gedung (Ambil dari tabel Tempat)
        $gedungList = Tempat::select('nama_tempat')
            ->distinct()
            ->orderBy('nama_tempat')
            ->pluck('nama_tempat');

        return view('absensi.anggota.tahfidz.tahfidzmuhafidzoh', [
            'title' => 'Absensi Tahfidz Muhafidzoh',
            'muhafidzoh' => $muhafidzoh,
            'menuAbsensiAnggota' => 'active',
            'menuAbsensiTahfidz' => 'active',
            'tahfidzmuhafidzoh' => 'active',
            'gedungList' => $gedungList,
        ]);
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
