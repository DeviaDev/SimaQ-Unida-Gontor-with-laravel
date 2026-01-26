<?php

namespace App\Http\Controllers;

use App\Models\Mahasiswi;
use App\Models\Ujianmandiri;
use App\Models\Ujiantahsin;
use Illuminate\Http\Request;
use App\Exports\MandiriExport;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\TahsinExport;
use App\Exports\remedial;


class UjianController extends Controller
{
    // ================================================ UJIAN MANDIRI ==========================================================

    public function mandiri()
    {
        $data = array(
            'title'       => 'Data Ujian Mandiri',
            'menuUjian'   => 'active',
            'menuData'    => 'active', // Menyamakan konsep menuData active
            'mandiri' => Ujianmandiri::with('mahasiswi')->get(),
        );

        return view('ujian.tahfidz.mandiri', $data);
    }

    public function create5()
    {
        $data = array(
            'title'     => 'Tambah Data Ujian Mandiri',
            'menuUjian' => 'active',
            'mahasiswi' => Mahasiswi::all(),
        );

        return view('ujian.tahfidz.createmandiri', $data);
    }

    public function store5(Request $request) 
{
    // 1. Simpan data ke tabel Ujian Mandiri (sesuai Gambar 1)
    $mandiri = new \App\Models\Ujianmandiri();
    $mandiri->id_mahasiswi     = $request->id_mahasiswi;
    $mandiri->materi           = $request->materi;
    $mandiri->keterangan_ujian = $request->keterangan_ujian;
    $mandiri->nilai            = $request->nilai;
    $mandiri->save();

    // 2. LOGIKA OTOMATISASI REMEDIAL
    // Kita cek apakah nilainya C atau C-
    if (in_array(strtoupper($request->nilai), ['C', 'C-'])) {
        
        // AMBIL DATA MAHASISWI: Kita cari prodi & semester dari ID Mahasiswi
        $mhs = \App\Models\Mahasiswi::where('id_mahasiswi', $request->id_mahasiswi)->first();

        if ($mhs) {
            // Simpan ke tabel Remedial (sesuai Gambar 2)
            \App\Models\Remedial::create([
                'id_mahasiswi' => $request->id_mahasiswi,
                'prodi'        => $mhs->prodi,    // Diambil dari tabel Mahasiswi
                'semester'     => $mhs->semester, // Diambil dari tabel Mahasiswi
                'kategori'     => 'Mandiri',
                'materi'       => $request->materi,
                'nilai'        => $request->nilai,
            ]);
        }
    }

    return redirect()->route('mandiri')->with('success', 'Data Berhasil Disimpan!');
}

    public function edit5($id_ujian_mandiri) // Sesuaikan nama variabel agar enak dibaca
{
    $data = array(
        'title'     => 'Edit Data Ujian Mandiri',
        'menuUjian' => 'active',
        'ujian'     => Ujianmandiri::with('mahasiswi')->findOrFail($id_ujian_mandiri),
        'mahasiswi' => Mahasiswi::get(),
    );

    return view('ujian.tahfidz.edit', $data);
}

    public function update5(Request $request, $id) 
{
    $mandiri = \App\Models\Ujianmandiri::findOrFail($id);
    $mandiri->update($request->all());

    // Jika setelah diupdate nilainya C/C-, sinkronkan ke tabel remedial
    if ($request->nilai == 'C' || $request->nilai == 'C-') {
        \App\Models\Remedial::updateOrCreate(
            ['id_mahasiswi' => $mandiri->id_mahasiswi, 'materi' => $mandiri->materi], 
            [
                'prodi'    => $mandiri->mahasiswi->prodi ?? $request->prodi,
                'semester' => $mandiri->mahasiswi->semester ?? $request->semester,
                'nilai'    => $request->nilai
            ]
        );
    }

    return redirect()->route('mandiri')->with('success', 'Data Berhasil Diperbarui!');
}

    public function destroy5($id_ujian_mandiri)
    {
        $mandiri = Ujianmandiri::findOrFail($id_ujian_mandiri);
        $mandiri->delete();

        return redirect()->route('mandiri')->with('success', 'Data berhasil dihapus');
    }

    public function excel5(Request $request)
    {
        $filename = now()->format('d-m-Y_H.i.s');
        $search = $request->input('search');

        $query = Ujianmandiri::with(['mahasiswi']);

        if (!empty($search)) {
            $query->whereHas('mahasiswi', function ($q) use ($search) {
                $q->where('nama_mahasiswi', 'like', "%{$search}%")
                  ->orWhere('nim', 'like', "%{$search}%");
            })->orWhere('materi', 'like', "%{$search}%");
        }

        $mandiri = $query->get();

        return Excel::download(new MandiriExport($mandiri), 'DataUjian.xlsx');
    }

    public function pdf5(Request $request)
    {
        $filename = now()->format('d-m-Y_H.i.s');
        $search = $request->input('search');

        $query = Ujianmandiri::with(['mahasiswi']);

        if (!empty($search)) {
            $query->whereHas('mahasiswi', function ($q) use ($search) {
                $q->where('nama_mahasiswi', 'like', "%{$search}%");
            })->orWhere('materi', 'like', "%{$search}%");
        }

        $data = $query->get();
        $pdf = Pdf::loadView('ujian.tahfidz.pdf', ['mandiriData' => $data])
            ->setPaper('a4', 'potrait');
        return $pdf->stream('DataUjian.pdf');

        $data = $query->get(); 
    
    }

// ================================================ TAHSIN ==========================================================
    // app/Http/Controllers/UjianController.php

public function tahsin(Request $request) {
    $role = $request->query('role');
    $query = \App\Models\Ujiantahsin::with('mahasiswi');

    if ($role) {
        $query->where('kategori', $role);
    }

    $data = [
        'title'      => 'Data Ujian Tahsin',
        'tahsinData' => $query->get(), // HARUS 'tahsinData' agar sesuai Blade
        'currentRole' => $role,
    ];

    return view('ujian.tahsin.tahsin', $data);
}

    public function createTahsin(Request $request) 
{
    $role = $request->query('role');

    // Jika Mahasiswi, ambil dari tabel Mahasiswi
    // Jika Dosen, ambil dari tabel Dosen (atau sesuaikan dengan struktur tabelmu)
    if ($role == 'Dosen') {
        $peserta = \App\Models\Dosen::all(); // Contoh jika ada tabel dosen
    } elseif ($role == 'Mahasiswi') {
        $peserta = \App\Models\Mahasiswi::all();
    } else {
        $peserta = \App\Models\Mahasiswi::all(); // Default
    }

    $data = [
        'title' => 'Tambah Ujian Tahsin',
        'mahasiswi' => $peserta,
    ];

    return view('ujian.tahsin.createtahsin', $data);
}

    // app/Http/Controllers/UjianController.php

public function storeTahsin(Request $request) 
{
    // Kembali ke kode awal tanpa otomatisasi remedial
    $tahsin = new \App\Models\Ujiantahsin();
    $tahsin->id_mahasiswi = $request->id_mahasiswi;
    $tahsin->materi       = $request->materi;
    $tahsin->nilai        = $request->nilai;
    $tahsin->save();

    return redirect()->route('tahsin')->with('success', 'Data Tahsin Berhasil Disimpan!');
}

    public function editTahsin($id_tahsin)
    {
        $tahsin = \App\Models\Ujiantahsin::findOrFail($id_tahsin);
        $mahasiswi = \App\Models\Mahasiswi::all();

        $data = [
            'title'     => 'Edit Data Ujian Tahsin',
            'tahsin'    => $tahsin,
            'mahasiswi' => $mahasiswi,
        ];

        
        return view('ujian.tahsin.edittahsin', $data); // Baris 226
    }

    public function updateTahsin(Request $request, $id_tahsin)
{
    // 1. Validasi Input (Mencegah data kosong/invalid masuk DB)
    $request->validate([
        'id_mahasiswi' => 'required|exists:mahasiswi,id_mahasiswi', // Pastikan mahasiswi ada
        'prodi'        => 'nullable|string',
        'semester'     => 'nullable|numeric',
        'materi'       => 'required|string',
        'nilai'        => 'nullable|in:A,A-,B+,B,C+,C,C-', // Batasi nilai sesuai opsi
    ]);

    // 2. Ambil data lama
    $tahsin = \App\Models\Ujiantahsin::findOrFail($id_tahsin);

    // 3. Update SEMUA field, bukan hanya nilai
    $tahsin->id_mahasiswi = $request->id_mahasiswi;
    $tahsin->prodi        = $request->prodi;
    $tahsin->semester     = $request->semester;
    $tahsin->materi       = $request->materi;
    $tahsin->nilai        = $request->nilai;
    
    $tahsin->save();

    if (in_array($request->nilai, ['C', 'C-'])) {
        
        \App\Models\Remedial::updateOrCreate(
            // Kunci pencarian (agar tidak duplikat)
            // PENTING: Gunakan $tahsin->id_tahsin (sesuai struktur tabel Anda), bukan ->id
            ['id_tahsin' => $tahsin->id_tahsin], 
            
            // Data yang akan diupdate atau dibuat baru
            [
                'id_mahasiswi' => $tahsin->id_mahasiswi,
                'materi'       => $tahsin->materi,
                'status'       => 'Wajib Remedial'
            ]
        );
    } else {
        

    return redirect()->route('tahsin')->with('success', 'Data berhasil diperbarui!');
}
}

        public function destroyTahsin($id_tahsin)
            {
                // Cari data berdasarkan ID, kalau ketemu langsung hapus
                $tahsin = \App\Models\Ujiantahsin::findOrFail($id_tahsin);
                $tahsin->delete();

                // Balik ke tabel dengan pesan sukses
                return redirect()->route('tahsin')->with('success', 'Data Tahsin Berhasil Dihapus!');
            }

    public function exportExcel() {
    return Excel::download(new TahsinExport, 'data-tahsin.xlsx');
    }

    public function exportPdf() 
{
    // 1. Ambil data dari database
    $tahsinData = \App\Models\Ujiantahsin::with('mahasiswi')->get();
    
    // 2. Panggil view pdftahsin.blade.php (Pastikan foldernya benar)
    // Jika file ada di resources/views/ujian/tahsin/pdftahsin.blade.php
    $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('ujian.tahsin.pdftahsin', compact('tahsinData'));
    
    // 3. Set ukuran kertas (Opsional, tapi bagus untuk KOP surat)
    $pdf->setPaper('a4', 'portrait');

    // 4. Download file
    return $pdf->download('Data-Ujian-Tahsin.pdf');
}

// ================================================ REMEDIAL ==========================================================

public function remedial()
{
    $title = "Data Mahasiswi Remedial";
    $remedialData = \App\Models\Remedial::with('mahasiswi')->latest()->get();

    // Ubah dari 'ujian.remedial.index' menjadi 'ujian.remedial.remedial'
    // (Asumsinya file remedial.blade.php ada di folder resources/views/ujian/remedial/)
    // Ubah dari 'ujian.remedial.remedial' menjadi:
return view('ujian.tahfidz.remedial', compact('remedialData', 'title'));

}
// Untuk Export Excel
public function remedialExportExcel()
{
    // Pastikan kamu sudah membuat file App\Exports\RemedialExport
    return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\RemedialExport, 'Data_Remedial.xlsx');
}

// Untuk Export PDF
public function remedialExportPdf()
{
    $remedialData = \App\Models\Remedial::with('mahasiswi')->get();
    
    // Memanggil view khusus untuk tampilan PDF
    $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('ujian.tahfidz.remedialpdf', compact('remedialData'));
    
    return $pdf->download('Data_Remedial.pdf');
}

public function remedialUpdateInline(Request $request)
{
    // Cari data remedial berdasarkan ID yang dikirim
    $remedial = \App\Models\Remedial::find($request->id);
    
    if ($remedial) {
        // Update kolom nilai_remedial sesuai pilihan dropdown
        $remedial->nilai_remedial = $request->nilai;
        $remedial->save();
        
        return response()->json(['success' => true, 'message' => 'Nilai berhasil disimpan!']);
    }
    
    return response()->json(['success' => false, 'message' => 'Data gagal ditemukan.'], 404);
}

public function remedialEdit($id)
    {
        // Pastikan nama Model sesuai (Remedial atau Ujianremedial)
        $remedial = \App\Models\Remedial::findOrFail($id);
        
        // Jika perlu data mahasiswi untuk dropdown (opsional, tergantung view)
        $mahasiswi = \App\Models\Mahasiswi::all(); 

        $data = [
            'title'     => 'Edit Data Remedial',
            'remedial'  => $remedial,
            'mahasiswi' => $mahasiswi
        ];

        // Pastikan Anda sudah membuat file view ini: resources/views/ujian/remedial/edit.blade.php
        return view('ujian.tahfidz.remedialedit', $data);
    }

    // Function untuk Update data Remedial (Pasti Anda butuh ini setelah form disubmit)
    // --- UPDATE CONTROLLER: remedialUpdate ---
    public function remedialUpdate(\Illuminate\Http\Request $request, $id)
    {
        // 1. Validasi: Pastikan user memilih nilai (tidak boleh kosong)
        $request->validate([
            'nilai_remedial' => 'required', 
        ]);

        // 2. Cari data berdasarkan ID
        // Pastikan Model yang digunakan adalah 'Remedial' (sesuai file model Anda)
        $remedial = \App\Models\Remedial::findOrFail($id);

        // 3. Update Data
        $remedial->nilai_remedial = $request->nilai_remedial;

        // 4. Simpan ke Database
        $remedial->save();

        // 5. Redirect kembali ke halaman Index Remedial
        return redirect()->route('remedial')->with('success', 'Nilai Remedial berhasil diperbarui!');
    }

    // --- DELETE CONTROLLER: remedialDestroy ---
    public function remedialDestroy($id)
    {
        // 1. Cari data yang mau dihapus
        $remedial = \App\Models\Remedial::findOrFail($id);
        
        // 2. Hapus data
        $remedial->delete();

        // 3. Kembali ke halaman remedial dengan pesan sukses
        return redirect()->route('remedial')->with('success', 'Data Remedial berhasil dihapus!');
    }
    
}




