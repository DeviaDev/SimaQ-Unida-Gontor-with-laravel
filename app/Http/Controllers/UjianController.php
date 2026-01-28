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
    // 1. Ambil role dari URL. Jika kosong, Default-nya 'Mahasiswi'
    // Jadi pas pertama buka, langsung rapi filter Mahasiswi, gak campur aduk.
    $role = $request->query('role', 'Mahasiswi'); 

    // 2. Siapkan Query dengan memuat relasi Mahasiswi DAN Dosen
    $query = \App\Models\Ujiantahsin::with(['mahasiswi', 'dosen', 'muhafidzoh']);
    // 3. Filter Query berdasarkan Kategori
    if ($role) {
        $query->where('kategori', $role);
    }

    $data = [
        'title'       => 'Data Ujian Tahsin',
        'tahsinData'  => $query->get(), // Ambil data yang sudah difilter
        'currentRole' => $role,         // Kirim info role ke view biar tombolnya bisa warna-warni
    ];

    return view('ujian.tahsin.tahsin', $data);
}

    public function createTahsin(Request $request) 
{
    // 1. Ambil role dari URL (Default: Mahasiswi)
    $role = $request->query('role', 'Mahasiswi');

    // 2. LOGIKA PENGAMBILAN DATA PESERTA
    if ($role == 'Dosen') {
        $peserta = \App\Models\Dosen::all(); 
        
    } elseif ($role == 'Muhafidzoh') {
        // --- BAGIAN INI YANG KITA UPDATE ---
        // Pastikan Model Muhafidzoh sudah ada. 
        // Jika belum, buat dulu via terminal: php artisan make:model Muhafidzoh
        $peserta = \App\Models\Muhafidzoh::all(); 
        
    } else {
        // Default: Mahasiswi
        $peserta = \App\Models\Mahasiswi::all(); 
    }

    $data = [
        'title'     => 'Tambah Ujian Tahsin',
        'mahasiswi' => $peserta, // Variabel ini sekarang bisa berisi data Muhafidzoh
        'role'      => $role,
    ];

    return view('ujian.tahsin.createtahsin', $data);
}

    // app/Http/Controllers/UjianController.php

public function storeTahsin(Request $request) 
{
    // 1. Validasi
    $request->validate([
        'id_mahasiswi' => 'required',
        'materi'       => 'required',
        'nilai'        => 'required',
    ]);

    // 2. Simpan Data
    $tahsin = new \App\Models\Ujiantahsin();
    
    // Simpan ID (bisa id mahasiswi/dosen/muhafidzoh)
    $tahsin->id_mahasiswi = $request->id_mahasiswi;
    
    // Simpan Prodi (Teks aman, strip boleh masuk)
    $tahsin->prodi = $request->prodi;

    // --- PERBAIKAN DI SINI ---
    // Logika: Jika semester isinya strip (-) atau bukan angka, ubah jadi 0
    if ($request->semester == '-' || !is_numeric($request->semester)) {
        $tahsin->semester = 0; 
    } else {
        $tahsin->semester = $request->semester;
    }
    // -------------------------

    $tahsin->kategori     = $request->kategori;
    $tahsin->materi       = $request->materi;
    $tahsin->nilai        = $request->nilai;
    
    $tahsin->save();

    // 3. REDIRECT KEMBALI KE FORM
    $currentRole = $request->kategori; 

    return redirect()->route('tahsinCreate', ['role' => $currentRole])
                     ->with('success', 'Data berhasil disimpan! Silakan input data berikutnya.');
}

    public function editTahsin($id)
{
    // 1. Ambil Data Ujian yang mau diedit
    $item = \App\Models\Ujiantahsin::findOrFail($id);

    // 2. Cek Kategorinya apa? (Dosen, Mahasiswi, atau Muhafidzoh)
    $kategori = $item->kategori; 

    // 3. Ambil List Peserta Sesuai Kategori
    if ($kategori == 'Dosen') {
        $peserta = \App\Models\Dosen::all();
    } elseif ($kategori == 'Muhafidzoh') {
        $peserta = \App\Models\Muhafidzoh::all();
    } else {
        // Default: Mahasiswi
        $peserta = \App\Models\Mahasiswi::all();
    }

    // 4. Kirim data ke View
    return view('ujian.tahsin.edittahsin', [
        'title'   => 'Edit Data Ujian Tahsin',
        'item'    => $item,    // Data ujian yang sedang diedit
        'peserta' => $peserta, // List nama (bisa dosen/mahasiswi/muhafidzoh)
        'role'    => $kategori // Kirim kategori biar view tau cara nampilinnya
    ]);
}

    public function updateTahsin(Request $request, $id)
{
    $tahsin = \App\Models\Ujiantahsin::findOrFail($id);

    $tahsin->id_mahasiswi = $request->id_mahasiswi;
    $tahsin->prodi        = $request->prodi;
    
    // --- LOGIKA SEMESTER (Agar tidak error database) ---
    if ($request->semester == '-' || !is_numeric($request->semester)) {
        $tahsin->semester = 0;
    } else {
        $tahsin->semester = $request->semester;
    }
    // ---------------------------------------------------

    $tahsin->materi       = $request->materi;
    $tahsin->nilai        = $request->nilai;
    // Kategori tidak perlu diupdate karena sudah hidden input, tapi kalau mau aman:
    // $tahsin->kategori = $request->kategori; 
    
    $tahsin->save();

    return redirect()->route('tahsin', ['role' => $tahsin->kategori])
                     ->with('success', 'Data berhasil diperbarui!');
}


        public function destroyTahsin($id)
{
    // 1. Cari data yang mau dihapus
    $item = \App\Models\Ujiantahsin::findOrFail($id);

    // 2. CATAT KATEGORINYA DULU (Penting!)
    // Kita simpan info kategori (Dosen/Muhafidzoh/Mahasiswi) ke variabel $role
    $role = $item->kategori;

    // 3. Baru hapus datanya
    $item->delete();

    // 4. Redirect kembali dengan membawa parameter ROLE
    // Sehingga saat halaman reload, tab yang aktif sesuai dengan $role tadi
    return redirect()->route('tahsin', ['role' => $role])
                     ->with('success', 'Data berhasil dihapus!');
}

    public function exportExcel(Request $request) 
{
    // 1. Ambil Role dari URL (dikirim oleh script JS tadi)
    $role = $request->query('role', 'Mahasiswi');

    // 2. Download Excel
    // Kita kirim variabel $role ke dalam class TahsinExport
    return \Maatwebsite\Excel\Facades\Excel::download(
        new \App\Exports\TahsinExport($role), 
        'Data-Ujian-'.$role.'.xlsx'
    );
}

    public function exportPdf(Request $request) 
{
    // 1. Tangkap Role dari Script tadi
    $role = $request->query('role', 'Mahasiswi');

    // 2. Filter Query
    $query = \App\Models\Ujiantahsin::with(['mahasiswi', 'dosen']);
    
    if ($role) {
        $query->where('kategori', $role);
    }

    $data = $query->get();
    
    // 3. Generate PDF
    // Pastikan view 'pdftahsin' nanti logic namanya juga sudah benar (pakai if dosen/mahasiswi)
    $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('ujian.tahsin.pdftahsin', ['tahsinData' => $data, 'role' => $role]);
    $pdf->setPaper('a4', 'portrait');

    return $pdf->download('Data-Ujian-'.$role.'.pdf');
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




