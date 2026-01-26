<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Pengurus;
use App\Models\TilawahPengurus;
use Illuminate\Pagination\LengthAwarePaginator; // ✅ Tambahkan ini
use PhpOffice\PhpWord\TemplateProcessor;

class TilawahPengurusController extends Controller
{
    public function index(Request $request)
    {
        $title = 'Absensi Tilawah Pengurus';

        // 1. Ambil Semua Pengurus
        $pengurus = Pengurus::orderBy('nama', 'asc')->get();
        $ids = $pengurus->pluck('id');

        // 2. Ambil Progress Tilawah
        $progress = DB::table('tilawah_pengurus')
            ->whereIn('id_pengurus', $ids)
            ->get()
            ->keyBy('id_pengurus');

        // 3. Mapping Data (Hitung Juz)
        foreach ($pengurus as $p) {
            $id = $p->id;

            if (isset($progress[$id])) {
                $prog = $progress[$id];
                $juzArray = $prog->juz ? explode(',', $prog->juz) : [];
                $p->juz_sekarang_array = $juzArray;
                $p->khatam_ke = $prog->khatam_ke;
                $p->total_juz = (($prog->khatam_ke - 1) * 30) + count($juzArray);
            } else {
                $p->juz_sekarang_array = [];
                $p->khatam_ke = 1;
                $p->total_juz = 0;
            }
        }

        // --- 4. LOGIKA LEADERBOARD ---
        // Duplikasi data pengurus untuk disortir
        $ranked = $pengurus->sortByDesc('total_juz')->values();

        // Paginasi Manual (10 per halaman)
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $perPage = 10;
        $currentItems = $ranked->slice(($currentPage - 1) * $perPage, $perPage)->all();
        
        $leaderboard = new LengthAwarePaginator($currentItems, count($ranked), $perPage);
        $leaderboard->setPath($request->url());
        $leaderboard->appends($request->query());

        return view('absensi.pengurus.tilawah', [
            'title'               => $title,
            'pengurus'            => $pengurus,    // Data untuk Tabel Input (Atas)
            'leaderboard'         => $leaderboard, // Data untuk Leaderboard (Bawah)
            'menuAbsensiPengurus' => 'active',
            'pengurusTilawah'     => 'active',
        ]);
    }

    // ... (Function simpanSemua & exportDocx TETAP SAMA, tidak perlu diubah) ...
    public function simpanSemua(Request $request) {
        $data = $request->input('data'); 
        if (!$data || !is_array($data)) return response()->json(['success' => false], 400);
        
        DB::beginTransaction();
        try {
            foreach ($data as $item) {
                if(isset($item['pengurus_id'])) {
                    $juzString = (isset($item['juz']) && is_array($item['juz'])) ? implode(',', $item['juz']) : '';
                    
                    DB::table('tilawah_pengurus')->updateOrInsert(
                        ['id_pengurus' => $item['pengurus_id']], 
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
        $allPengurus = Pengurus::select('id', 'nama')->orderBy('nama', 'asc')->get();
        $allProgress = DB::table('tilawah_pengurus')->get()->keyBy('id_pengurus');

        $ranked = $allPengurus->map(function($p) use ($allProgress) {
            if (isset($allProgress[$p->id])) {
                $prog = $allProgress[$p->id];
                $juzCount = $prog->juz ? count(explode(',', $prog->juz)) : 0;
                $p->total_juz = (($prog->khatam_ke - 1) * 30) + $juzCount;
            } else {
                $p->total_juz = 0;
            }
            return $p;
        });

        $dataExport = $ranked->sortByDesc('total_juz')->values();

        try {
            $templatePath = storage_path('app/templates/mahatilawah.docx'); 
            
            if (!file_exists($templatePath)) {
                return back()->with('error', 'File template tidak ditemukan');
            }

            $templateProcessor = new TemplateProcessor($templatePath);
            $templateProcessor->setValue('bulan', $request->bulan ?? date('F'));
            $templateProcessor->cloneRow('nama', count($dataExport));

            foreach ($dataExport as $index => $item) {
                $i = $index + 1;
                $templateProcessor->setValue('no#' . $i, $i);
                $templateProcessor->setValue('nama#' . $i, $item->nama);
                $templateProcessor->setValue('prodi#' . $i, '-'); // Pengurus biasanya tidak ada prodi di laporan ini
                $templateProcessor->setValue('smt#' . $i, '-');

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

            $fileName = 'Laporan_Tilawah_Pengurus_' . date('Ymd_His') . '.docx';
            $savePath = storage_path('app/templates/' . $fileName);
            $templateProcessor->saveAs($savePath);

            return response()->download($savePath)->deleteFileAfterSend(true);

        } catch (\Exception $e) {
            return back()->with('error', 'Gagal export: ' . $e->getMessage());
        }
    }
}