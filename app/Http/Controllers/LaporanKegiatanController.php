<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LaporanKegiatan;
use App\Models\Pengurus;
use App\Models\AbsensiPengurus;
use Illuminate\Support\Facades\DB;

class LaporanKegiatanController extends Controller
{
    // 1. Halaman Utama (List Kegiatan)
    public function index()
    {
        $kegiatan = LaporanKegiatan::orderBy('tanggal', 'desc')->get();
        // ✅ PERBAIKAN 1: Tambahkan 'absensi.' di depannya
        return view('absensi.pengurus.pengurus', [
            'title' => 'Laporan Kegiatan & Absensi',
            'kegiatan' => $kegiatan
        ]);
    }

    // 2. Simpan Kegiatan Baru (+ Generate Absensi Otomatis)
    public function store(Request $request)
    {
        $request->validate([
            'nama_kegiatan' => 'required',
            'tanggal'       => 'required|date',
        ]);

        DB::beginTransaction();
        try {
            // A. Simpan Data Kegiatan
            $kegiatan = LaporanKegiatan::create([
                'nama_kegiatan' => $request->nama_kegiatan,
                'tanggal'       => $request->tanggal,
                'waktu'         => $request->waktu,
                'tempat'        => $request->tempat,
                'link_foto'     => $request->link_foto,
                'berita_acara'  => $request->berita_acara,
            ]);

            // B. OTOMATIS Generate Absensi untuk SEMUA Pengurus
            $allPengurus = Pengurus::all(); // Ambil semua data pengurus
            
            foreach ($allPengurus as $p) {
                AbsensiPengurus::create([
                    'id_kegiatan' => $kegiatan->id,
                    'id_pengurus' => $p->id,
                    'status'      => 'hadir', // Default status awal
                ]);
            }

            DB::commit();
            // Redirect langsung ke halaman detail untuk isi absen
            return redirect()->route('laporan.show', $kegiatan->id)->with('success', 'Kegiatan dibuat, silakan isi absensi.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menyimpan: ' . $e->getMessage());
        }
    }

    // 3. Halaman Detail (Form Absensi)
    public function show($id)
    {
        $kegiatan = LaporanKegiatan::with('absensi.pengurus')->findOrFail($id);
        
        return view('absensi.pengurus.show', [
            'title' => 'Detail Kegiatan: ' . $kegiatan->nama_kegiatan,
            'kegiatan' => $kegiatan
        ]);
    }

    // 4. Simpan Update Absensi
    public function updateAbsensi(Request $request, $id)
    {
        // $id adalah id_kegiatan
        $input = $request->input('absensi'); // Array data dari form

        if ($input) {
            foreach ($input as $id_absensi => $data) {
                $status = $data['status'] ?? 'alpha';
                $ket    = $data['keterangan'] ?? null;

                AbsensiPengurus::where('id', $id_absensi)->update([
                    'status'     => $status,
                    'keterangan' => $ket,
                    'updated_at' => now()
                ]);
            }
        }

        return back()->with('success', 'Absensi berhasil diperbarui!');
    }

    public function export($id)
    {
        // Ambil data kegiatan beserta relasi absensi dan pengurus
        $kegiatan = LaporanKegiatan::with(['absensi.pengurus'])->findOrFail($id);

        try {
            // Lokasi template (Pastikan file ini sudah kamu buat!)
            $templatePath = storage_path('app/templates/laporan_kegiatan.docx');
            
            if (!file_exists($templatePath)) {
                return back()->with('error', 'File template laporan_kegiatan.docx tidak ditemukan di storage/app/templates');
            }

            // Load TemplateProcessor
            $templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor($templatePath);
            
            // --- 1. ISI BAGIAN HEADER ---
            // Mengisi ${nama_kegiatan}, ${tanggal}, dll
            $templateProcessor->setValue('nama_kegiatan', $kegiatan->nama_kegiatan);
            $templateProcessor->setValue('tanggal', \Carbon\Carbon::parse($kegiatan->tanggal)->format('d M Y'));
            $templateProcessor->setValue('waktu', $kegiatan->waktu ?? '-');
            $templateProcessor->setValue('tempat', $kegiatan->tempat ?? '-');
            
            // --- 2. ISI BAGIAN TABEL ---
            // Kita hitung jumlah data untuk meng-clone baris tabel
            $jumlahData = $kegiatan->absensi->count();
            
            // Clone baris berdasarkan variabel ${nama}
            $templateProcessor->cloneRow('nama', $jumlahData);

            foreach ($kegiatan->absensi as $index => $absen) {
                $i = $index + 1; // Nomor urut
                
                // Set value untuk setiap baris (pake #urutan)
                $templateProcessor->setValue('no#' . $i, $i);
                $templateProcessor->setValue('nama#' . $i, $absen->pengurus->nama ?? 'Mantan Pengurus');
                
                // Ubah status jadi Huruf Besar Awal (hadir -> Hadir)
                $templateProcessor->setValue('status#' . $i, ucfirst($absen->status));
                
                // Isi keterangan jika ada
                $templateProcessor->setValue('ket#' . $i, $absen->keterangan ?? '-');
            }

            // --- 3. DOWNLOAD FILE ---
            // Bersihkan nama file dari karakter aneh
            $cleanName = preg_replace('/[^A-Za-z0-9\-]/', '_', $kegiatan->nama_kegiatan);
            $fileName = 'Absensi_' . $cleanName . '.docx';
            
            // Simpan sementara lalu download
            $savePath = storage_path('app/public/' . $fileName);
            $templateProcessor->saveAs($savePath);

            return response()->download($savePath)->deleteFileAfterSend(true);

        } catch (\Exception $e) {
            return back()->with('error', 'Gagal export: ' . $e->getMessage());
        }
    }
}