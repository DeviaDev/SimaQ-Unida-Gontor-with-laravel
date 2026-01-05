<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pengurus2;        // Model Data Pengurus (Master)
use App\Models\LaporanKegiatan;  // Model Simpan Laporan

class PengurusAjaController extends Controller
{
    // Halaman Form Lailatu
    public function index()
    {
        // 1. Ambil data pengurus dari database untuk ditampilkan di tabel
        $pengurus = Pengurus2::all();

        // 2. Siapkan data untuk tampilan (Judul & Menu Active)
        // Kita samakan dengan controller lamamu agar sidebar tetap 'active'
        $data = [
            'title'               => 'Malam Lailatu Tahfidz',
            'menuAbsensiPengurus' => 'active',
            'pengurusLailatu'     => 'active',
            'pengurus'            => $pengurus // Masukkan data pengurus ke sini
        ];

        // 3. Return ke view yang sesuai lokasinya
        return view('absensi.pengurus.lailatu', $data);
    }

    // Proses Simpan Data
    public function store(Request $request)
    {
        // Validasi
        $request->validate([
            'nama_kegiatan' => 'required|string',
            'tanggal'       => 'required|date',
            'kehadiran'     => 'required|array',
        ]);

        // Simpan ke database laporan_kegiatan
        LaporanKegiatan::create([
            'nama_kegiatan'  => $request->nama_kegiatan,
            'tanggal'        => $request->tanggal,
            'detail_absensi' => $request->kehadiran 
        ]);

        return redirect()->back()->with('success', 'Alhamdulillah, data kegiatan berhasil disimpan!');
    }
}