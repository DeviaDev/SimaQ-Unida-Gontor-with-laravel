<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Mahasiswi; // ✅ PERBAIKAN: Pakai Model Mahasiswi yang Benar
use App\Models\Mahatilawah; 
use App\Models\KelompokLT;
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
        $leaderboard = null;

        // 1. Ambil List Kelompok (Filter Logic)
        if ($hasProdi && $hasSemester) {
            // Kita cari KelompokLT yang punya mahasiswi di prodi/smt tersebut
            $kelompokList = KelompokLT::whereHas('mahasiswi', function($q) use ($request) {
                $q->where('prodi', $request->prodi)
                  ->where('semester', $request->semester);
            })
            ->orderBy('id_kelompok') 
            ->get(); // ✅ PENTING: Pakai get() supaya dapat Object lengkap (id, nama, kode), JANGAN pluck()
        }

        // 2. KONDISI A: Filter Lengkap (Mode Input/Edit)
        if ($hasProdi && $hasSemester && $hasKelompok) {
            $query = Mahasiswi::query(); // ✅ Pakai Model Mahasiswi
            
            // Sertakan relasi 'kelompok' agar namanya muncul di view (opsional)
            $query->with('kelompok'); 

            $query->where('prodi', $request->prodi);
            $query->where('semester', $request->semester);
            $query->where('id_kelompok', $request->kelompok); // ✅ Kolom DB: id_kelompok
            
            // ✅ Kolom DB: nama_mahasiswi
            $mahasiswi = $query->orderBy('nama_mahasiswi')->get();

            // ✅ Kolom DB: id_mahasiswi
            $ids = $mahasiswi->pluck('id_mahasiswi');

            // Ambil data progress dari tabel 'mahatilawah'
            $progress = DB::table('mahatilawah')
                ->whereIn('id_mahasiswi', $ids) // ✅ Kolom DB: id_mahasiswi
                ->get()
                ->keyBy('id_mahasiswi');

            foreach ($mahasiswi as $m) {
                // Gunakan id_mahasiswi untuk kunci pencarian
                $id = $m->id_mahasiswi;

                if (isset($progress[$id])) {
                    $p = $progress[$id];
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
                
                // Mapping agar view blade tidak error jika panggil $m->nama atau $m->id
                $m->nama = $m->nama_mahasiswi; 
                // (Untuk ID sudah aman karena Blade sudah kita ubah jadi id_mahasiswi sebelumnya)
            }
        } 
        // 3. KONDISI B: Filter Belum Lengkap (Mode Leaderboard)
        else {
            // Ambil semua data mahasiswi untuk leaderboard
            $allMahasiswi = Mahasiswi::with('kelompok')
                ->select('id_mahasiswi', 'nama_mahasiswi', 'prodi', 'semester', 'id_kelompok')
                ->get();
            
            // Ambil semua data progress
            $allProgress = DB::table('mahatilawah')->get()->keyBy('id_mahasiswi');

            // Hitung total juz
            $ranked = $allMahasiswi->map(function($m) use ($allProgress) {
                if (isset($allProgress[$m->id_mahasiswi])) {
                    $p = $allProgress[$m->id_mahasiswi];
                    $juzCount = $p->juz ? count(explode(',', $p->juz)) : 0;
                    $m->total_juz = (($p->khatam_ke - 1) * 30) + $juzCount;
                } else {
                    $m->total_juz = 0;
                }
                
                // Mapping nama & kelompok
                $m->nama = $m->nama_mahasiswi;
                // ✅ Perbaikan Tampilan Kelompok Leaderboard
                $namaKlp = $m->kelompok ? ($m->kelompok->nama_kelompok ?? $m->kelompok->kode_kelompok) : $m->id_kelompok;
                $m->nama_kelompok_display = $namaKlp; 
                
                return $m;
            });

            // Urutkan & Paginasi
            $ranked = $ranked->sortByDesc('total_juz')->values();
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
            'leaderboard'      => $leaderboard, // ✅ Pastikan variabel ini terkirim
            'menuAbsensiAnggota' => 'active',
            'menuAbsensiTilawah' => 'active',
            'tilawahmahasiswi'   => 'active',
        ]);
    }

    public function simpanSemua(Request $request) {
        $data = $request->input('data'); 
        if (!$data || !is_array($data)) return response()->json(['success' => false], 400);
        
        DB::beginTransaction();
        try {
            foreach ($data as $item) {
                // Pastikan key dari JS adalah 'mahasiswi_id'
                if(isset($item['mahasiswi_id'])) {
                    $juzString = (isset($item['juz']) && is_array($item['juz'])) ? implode(',', $item['juz']) : '';
                    
                    DB::table('mahatilawah')->updateOrInsert(
                        ['id_mahasiswi' => $item['mahasiswi_id']], // ✅ Kolom DB: id_mahasiswi
                        ['juz' => $juzString, 'khatam_ke' => $item['khatam_ke'], 'updated_at' => now()]
                    );
                }
            }
            DB::commit();
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function exportDocx(Request $request)
    {
        $request->validate([
            'limit' => 'required|numeric|min:1',
            'bulan' => 'required|string',
        ]);

        $limit = $request->limit;
        
        // ✅ Pakai Model Mahasiswi & Nama Kolom Benar
        $allMahasiswi = Mahasiswi::select('id_mahasiswi', 'nama_mahasiswi', 'prodi', 'semester')->get();
        $allProgress = DB::table('mahatilawah')->get()->keyBy('id_mahasiswi');

        $ranked = $allMahasiswi->map(function($m) use ($allProgress) {
            if (isset($allProgress[$m->id_mahasiswi])) {
                $p = $allProgress[$m->id_mahasiswi];
                $juzCount = $p->juz ? count(explode(',', $p->juz)) : 0;
                $m->total_juz = (($p->khatam_ke - 1) * 30) + $juzCount;
            } else {
                $m->total_juz = 0;
            }
            $m->nama = $m->nama_mahasiswi;
            return $m;
        });

        $dataExport = $ranked->sortByDesc('total_juz')->take($limit)->values();

        try {
            $templatePath = storage_path('app/templates/mahatilawah.docx');
            
            if (!file_exists($templatePath)) {
                return back()->with('error', 'File template tidak ditemukan');
            }

            $templateProcessor = new TemplateProcessor($templatePath);
            $templateProcessor->setValue('bulan', $request->bulan);
            $templateProcessor->cloneRow('nama', count($dataExport));

            foreach ($dataExport as $index => $item) {
                $i = $index + 1;
                $templateProcessor->setValue('no#' . $i, $i);
                $templateProcessor->setValue('nama#' . $i, $item->nama);
                $templateProcessor->setValue('prodi#' . $i, $item->prodi);
                $templateProcessor->setValue('smt#' . $i, $item->semester);

                // --- LOGIKA BARU FORMAT KHATAM ---
                $jmlKhatam = floor($item->total_juz / 30);
                $sisaJuz   = $item->total_juz % 30;
                
                $formatText = "";
                if ($jmlKhatam > 0) {
                    $formatText .= $jmlKhatam . " Khatam ";
                }
                $formatText .= $sisaJuz . " Juz";

                $templateProcessor->setValue('total#' . $i, $formatText);
            }

            $fileName = 'Laporan_Tilawah_' . date('Ymd_His') . '.docx';
            $savePath = storage_path('app/templates/' . $fileName);
            $templateProcessor->saveAs($savePath);

            return response()->download($savePath)->deleteFileAfterSend(true);

        } catch (\Exception $e) {
            return back()->with('error', 'Gagal export: ' . $e->getMessage());
        }
    }
}