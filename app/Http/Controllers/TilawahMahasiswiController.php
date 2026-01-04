<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Mahasiswi2; 
use App\Models\Mahatilawah; 
// 👇 PENTING: Tambahkan ini untuk halaman (1, 2, 3) di leaderboard
use Illuminate\Pagination\LengthAwarePaginator; 
use PhpOffice\PhpWord\TemplateProcessor;

class TilawahMahasiswiController extends Controller
{
    public function index(Request $request)
    {
        $title = 'Absensi Tilawah Mahasiswi';

        $hasProdi    = $request->filled('prodi');
        $hasSemester = $request->filled('semester');
        $hasKelompok = $request->filled('kelompok');
        
        $mahasiswi = collect([]); 
        $kelompokList = [];
        $leaderboard = null; // Siapkan variabel ini

        // 1. Ambil List Kelompok (Filter Logic)
        if ($hasProdi && $hasSemester) {
            $kelompokList = Mahasiswi2::where('prodi', $request->prodi)
                ->where('semester', $request->semester)
                ->select('kelompok')
                ->distinct()
                ->orderBy('kelompok')
                ->pluck('kelompok');
        }

        // 2. KONDISI A: Filter Lengkap (Mode Input/Edit)
        if ($hasProdi && $hasSemester && $hasKelompok) {
            $query = Mahasiswi2::query();
            $query->where('prodi', $request->prodi);
            $query->where('semester', $request->semester);
            $query->where('kelompok', $request->kelompok);
            
            $mahasiswi = $query->orderBy('nama')->get();

            $ids = $mahasiswi->pluck('id');
            $progress = DB::connection('mysql_siwak')
                ->table('mahatahsin')
                ->whereIn('mahasiswi_id', $ids)
                ->get()
                ->keyBy('mahasiswi_id');

            foreach ($mahasiswi as $m) {
                if (isset($progress[$m->id])) {
                    $p = $progress[$m->id];
                    $juzArray = $p->juz ? explode(',', $p->juz) : [];
                    $m->juz_sekarang_array = $juzArray;
                    $m->juz_count = count($juzArray);
                    $m->khatam_ke = $p->khatam_ke;
                    $m->total_juz = (($p->khatam_ke - 1) * 30) + count($juzArray);
                } else {
                    $m->juz_sekarang_array = [];
                    $m->juz_count = 0;
                    $m->khatam_ke = 1;
                    $m->total_juz = 0;
                }
            }
        } 
        // 3. KONDISI B: Filter Belum Lengkap (Mode Leaderboard)
        else {
            // Ambil semua data ringkas
            $allMahasiswi = Mahasiswi2::select('id', 'nama', 'prodi', 'semester', 'kelompok')->get();
            $allProgress = DB::connection('mysql_siwak')->table('mahatahsin')->get()->keyBy('mahasiswi_id');

            // Hitung total juz untuk semua
            $ranked = $allMahasiswi->map(function($m) use ($allProgress) {
                if (isset($allProgress[$m->id])) {
                    $p = $allProgress[$m->id];
                    $juzCount = $p->juz ? count(explode(',', $p->juz)) : 0;
                    $m->total_juz = (($p->khatam_ke - 1) * 30) + $juzCount;
                } else {
                    $m->total_juz = 0;
                }
                return $m;
            });

            // Urutkan Tertinggi -> Terendah
            $ranked = $ranked->sortByDesc('total_juz')->values();

            // Paginasi Manual (10 per halaman)
            $currentPage = LengthAwarePaginator::resolveCurrentPage();
            $perPage = 10;
            $currentItems = $ranked->slice(($currentPage - 1) * $perPage, $perPage)->all();
            $leaderboard = new LengthAwarePaginator($currentItems, count($ranked), $perPage);
            $leaderboard->setPath($request->url());
            $leaderboard->appends($request->query());
        }

        return view('absensi.anggota.tilawah.TilawahMahasiswi', [
            'title'            => $title,
            'mahasiswi'        => $mahasiswi,
            'kelompokList'     => $kelompokList,
            'leaderboard'      => $leaderboard, // Kirim ke blade
            'menuAbsensiAnggota' => 'active',
            'menuAbsensiTilawah' => 'active',
            'tilawahmahasiswi'   => 'active',
        ]);
    }

    // Fungsi simpanSemua TETAP SAMA (Tidak perlu diubah)
    public function simpanSemua(Request $request) {
        // ... (Kode simpan kamu yang sudah benar) ...
        $data = $request->input('data'); 
        if (!$data || !is_array($data)) return response()->json(['success' => false], 400);
        
        DB::connection('mysql_siwak')->beginTransaction();
        try {
            foreach ($data as $item) {
                if(isset($item['mahasiswi_id'])) {
                    $juzString = (isset($item['juz']) && is_array($item['juz'])) ? implode(',', $item['juz']) : '';
                    DB::connection('mysql_siwak')->table('mahatahsin')->updateOrInsert(
                        ['mahasiswi_id' => $item['mahasiswi_id']],
                        ['juz' => $juzString, 'khatam_ke' => $item['khatam_ke'], 'updated_at' => now()]
                    );
                }
            }
            DB::connection('mysql_siwak')->commit();
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            DB::connection('mysql_siwak')->rollBack();
            return response()->json(['success' => false], 500);
        }
    }
    public function exportDocx(Request $request)
    {
        // 1. Validasi Input Modal
        $request->validate([
            'limit' => 'required|numeric|min:1',
            'bulan' => 'required|string',
        ]);

        $limit = $request->limit;
        $bulan = $request->bulan;

        // 2. Ambil Data & Hitung Total Juz (Logic sama seperti Index Leaderboard)
        $allMahasiswi = Mahasiswi2::select('id', 'nama', 'prodi', 'semester')->get();
        $allProgress = DB::connection('mysql_siwak')->table('mahatahsin')->get()->keyBy('mahasiswi_id');

        $ranked = $allMahasiswi->map(function($m) use ($allProgress) {
            if (isset($allProgress[$m->id])) {
                $p = $allProgress[$m->id];
                $juzCount = $p->juz ? count(explode(',', $p->juz)) : 0;
                $m->total_juz = (($p->khatam_ke - 1) * 30) + $juzCount;
            } else {
                $m->total_juz = 0;
            }
            return $m;
        });

        // 3. Urutkan Tertinggi dan Ambil Sesuai Limit
        $dataExport = $ranked->sortByDesc('total_juz')->take($limit)->values();

        // 4. Proses Template Word
        try {
            // 1. Arahkan ke file sesuai gambar kamu: storage/app/templates/mahatilawah.docx
            $templatePath = storage_path('app/templates/mahatilawah.docx');
            
            if (!file_exists($templatePath)) {
                return back()->with('error', 'File template tidak ditemukan di: ' . $templatePath);
            }

            $templateProcessor = new TemplateProcessor($templatePath);

            // 2. Set Variabel Judul (Bulan)
            // Pastikan di Word kamu tulis: Laporan Bulan ${bulan}
            $templateProcessor->setValue('bulan', $request->bulan);

            // 3. Clone Baris Tabel
            // 'nama' adalah variabel patokan untuk copy baris
            $templateProcessor->cloneRow('nama', count($dataExport));

            // 4. Isi Data ke Tabel
            foreach ($dataExport as $index => $item) {
                $i = $index + 1; // Supaya nomor mulai dari 1
                
                // Masukkan data ke variabel ${...}
                $templateProcessor->setValue('no#' . $i, $i);
                $templateProcessor->setValue('nama#' . $i, $item->nama);
                $templateProcessor->setValue('prodi#' . $i, $item->prodi);
                $templateProcessor->setValue('smt#' . $i, $item->semester);
                // Tambahkan kata "Juz" dibelakang angka
                $templateProcessor->setValue('total#' . $i, $item->total_juz . ' Juz');
            }

            // 5. Simpan file hasil sementara
            $fileName = 'Laporan_Tilawah_' . date('Ymd_His') . '.docx';
            // Simpan sementara di folder storage/app/templates/ juga biar satu tempat
            $savePath = storage_path('app/templates/' . $fileName);
            
            $templateProcessor->saveAs($savePath);

            // 6. Download file lalu hapus dari server
            return response()->download($savePath)->deleteFileAfterSend(true);

        } catch (\Exception $e) {
            return back()->with('error', 'Gagal export: ' . $e->getMessage());
        }
    }
}