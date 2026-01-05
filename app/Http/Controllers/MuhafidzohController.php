<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Muhafidzoh; // Pakai model yang benar
use App\Models\Tempat;     // Perlu model Tempat untuk ambil list gedung

class MuhafidzohController extends Controller
{
    public function index(Request $request)
    {
        $title = 'Data Muhafidzoh';

        $gedungFilter = $request->gedung;

        // 1. Ambil List Gedung Unik dari tabel 'tempat'
        // Asumsi di tabel 'tempat' ada kolom 'nama_tempat' atau sejenisnya yg berisi nama gedung
        // Jika kolomnya 'nama_tempat', ganti 'gedung' dgn 'nama_tempat'
        $listGedung = Tempat::select('nama_tempat') 
            ->distinct()
            ->orderBy('nama_tempat')
            ->pluck('nama_tempat'); 
            // Kalau isinya gabungan gedung & ruang, kamu mungkin perlu query manual
            // atau list manual jika nama gedungnya statis.
        
        // Query Dasar Muhafidzoh dengan Relasi
        $query = Muhafidzoh::with(['kelompok', 'tempat']);

        // 2. Filter Berdasarkan Gedung (Via Relasi Tempat)
        if ($gedungFilter) {
            $query->whereHas('tempat', function($q) use ($gedungFilter) {
                // Sesuaikan 'nama_tempat' dengan kolom di tabel tempat kamu
                // yang menyimpan nama gedung (misal: 'Istanbul LT2')
                $q->where('nama_tempat', $gedungFilter); 
            });
        }

        $muhafidzoh = $query->orderBy('nama_muhafidzoh', 'asc')->get();

        return view('muhafidzoh.index', compact(
            'title',
            'listGedung', // Kirim list gedung untuk tombol filter
            'muhafidzoh'
        ));
    }
}