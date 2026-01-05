<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Muhafidzoh;
use App\Models\Absensia;
use App\Models\Tempat;
use PhpOffice\PhpWord\TemplateProcessor;
use Carbon\Carbon;

class AbsensiMuhafidzohController extends Controller
{
    public function index(Request $request)
    {
        $title = 'Absensi Muhafidzoh';
        
        $gedung = request('gedung');
        $hasGedung = !empty($gedung);

        if (!$hasGedung) {
            // Ambil list gedung untuk filter dropdown
            $gedungList = Tempat::distinct()->pluck('nama_tempat');

            return view('absensi.anggota.tahfidz.tahfidzmuhafidzoh', [
                'title' => $title,
                'muhafidzoh' => [],
                'hasGedung' => false,
                'gedungList' => $gedungList
            ]);
        }

        // 1. Ambil Data Muhafidzoh + Relasi (Gunakan Model)
        $muhafidzoh = Muhafidzoh::with(['kelompok', 'tempat'])
            ->whereHas('tempat', function($q) use ($gedung) {
                $q->where('nama_tempat', $gedung);
            })
            ->orderBy('nama_muhafidzoh', 'asc') // Sesuaikan kolom: nama_muhafidzoh
            ->get();

        $ids = $muhafidzoh->pluck('id_muhafidzoh'); // Sesuaikan kolom: id_muhafidzoh

        // 2. Ambil riwayat dari tabel absensia
        $riwayatAbsensi = Absensia::whereIn('id_muhafidzoh', $ids) // Sesuaikan: id_muhafidzoh
            ->where('pertemuan', '>', 0)
            ->get()
            ->groupBy('id_muhafidzoh');

        // 3. Hitung total hadir
        $totalHadirData = Absensia::select('id_muhafidzoh', DB::raw('count(*) as total'))
            ->whereIn('id_muhafidzoh', $ids)
            ->where('status', 'hadir')
            ->where('pertemuan', '>', 0)
            ->groupBy('id_muhafidzoh')
            ->pluck('total', 'id_muhafidzoh');

        // 4. Map data ke object Muhafidzoh
        foreach ($muhafidzoh as $m) {
            $id = $m->id_muhafidzoh;
            if (isset($riwayatAbsensi[$id])) {
                $m->riwayat = $riwayatAbsensi[$id]->pluck('status', 'pertemuan')->toArray();
            } else {
                $m->riwayat = [];
            }
            $m->total_hadir = $totalHadirData[$id] ?? 0;
        }

        // Ambil list gedung lagi agar filter tetap muncul
        $gedungList = Tempat::distinct()->pluck('nama_tempat');

        return view('absensi.anggota.tahfidz.tahfidzmuhafidzoh', [
            'title'      => $title,
            'muhafidzoh' => $muhafidzoh,
            'hasGedung'  => true,
            'gedungList' => $gedungList
        ]);
    }

    // --- REFRESH DATA (AJAX) ---
    public function refresh(Request $request)
    {
        $gedung = $request->gedung;
        
        // 1. Ambil ID muhafidzoh berdasarkan gedung (via Model)
        $muhafidzohIds = Muhafidzoh::whereHas('tempat', function($q) use ($gedung) {
                $q->where('nama_tempat', $gedung);
            })
            ->pluck('id_muhafidzoh');

        // 2. Ambil semua absensi mereka
        $absensis = Absensia::whereIn('id_muhafidzoh', $muhafidzohIds)
            ->where('pertemuan', '>', 0)
            ->get();

        // 3. Format data untuk JSON
        $result = [];
        foreach ($muhafidzohIds as $id) {
            $result[$id] = [
                'riwayat' => [],
                'total_hadir' => 0
            ];
            
            $myAbsen = $absensis->where('id_muhafidzoh', $id);
            
            foreach($myAbsen as $a) {
                $p = (int)$a->pertemuan;
                $result[$id]['riwayat'][$p] = $a->status;
                if ($a->status == 'hadir') {
                    $result[$id]['total_hadir']++;
                }
            }
        }

        return response()->json(['success' => true, 'data' => $result]);
    }

    // --- SIMPAN SATUAN (AJAX) ---
    public function simpan(Request $request)
    {
        $request->validate([
            'muhafidzoh_id' => 'required',
            'tanggal'       => 'required|date',
            'status'        => 'required|in:hadir,izin,alpha'
        ]);

        DB::beginTransaction();
        try {
            // Cek data lama untuk mempertahankan nilai pertemuan jika ada
            $existing = Absensia::where('id_muhafidzoh', $request->muhafidzoh_id)
                ->where('tanggal', $request->tanggal)
                ->first();

            $pertemuanExisting = $existing ? $existing->pertemuan : 0;
            // Jika ada kiriman pertemuan dari request (saat push), pakai itu
            if ($request->has('pertemuan')) {
                $pertemuanExisting = $request->pertemuan;
            }

            // Gunakan updateOrCreate pada Model Absensia
            Absensia::updateOrCreate(
                [
                    'id_muhafidzoh' => $request->muhafidzoh_id,
                    'tanggal'       => $request->tanggal,
                ],
                [
                    'status'    => $request->status,
                    'pertemuan' => $pertemuanExisting
                ]
            );

            DB::commit();

            // Hitung Total Hadir Realtime
            $totalHadir = Absensia::where('id_muhafidzoh', $request->muhafidzoh_id)
                ->where('status', 'hadir')
                ->where('pertemuan', '>', 0)
                ->count();

            return response()->json([
                'success' => true,
                'total_hadir' => $totalHadir
            ]);

        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    // --- PUSH PERTEMUAN (AJAX) ---
    public function pushPertemuan(Request $request)
    {
        $request->validate([
            'muhafidzoh_id' => 'required',
            'tanggal'       => 'required|date',
            'status'        => 'required|in:hadir,izin,alpha',
            'pertemuan'     => 'required|integer|min:1|max:12',
        ]);

        // Cek apakah pertemuan ini sudah dipakai di tanggal lain oleh orang yang sama
        $isExist = Absensia::where('id_muhafidzoh', $request->muhafidzoh_id)
            ->where('pertemuan', $request->pertemuan)
            ->where('tanggal', '!=', $request->tanggal)
            ->exists();

        if ($isExist) {
             return response()->json([
                'success' => false, 
                'message' => "Gagal! Pertemuan ke-{$request->pertemuan} sudah terisi di tanggal lain."
            ], 400);
        }

        DB::beginTransaction();
        try {
            // Update atau Create data absensi dengan pertemuan yang dipush
            Absensia::updateOrCreate(
                [
                    'id_muhafidzoh' => $request->muhafidzoh_id,
                    'tanggal'       => $request->tanggal,
                ],
                [
                    'pertemuan' => $request->pertemuan,
                    'status'    => $request->status
                ]
            );

            DB::commit();

            $totalHadir = Absensia::where('id_muhafidzoh', $request->muhafidzoh_id)
                ->where('status', 'hadir')
                ->where('pertemuan', '>', 0)
                ->count();

            return response()->json([
                'success' => true,
                'total_hadir' => $totalHadir
            ]);

        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    // --- EXPORT WORD ---
    public function export(Request $request)
    {
        $request->validate([
            'gedung'      => 'required',
            'pertemuan'   => 'required|array',
            'bulan'       => 'required|integer|min:1|max:12',
            'tanpa_staf'  => 'nullable|in:0,1',
            'tgl1'        => 'nullable|date',
            'tgl2'        => 'nullable|date',
            'tgl3'        => 'nullable|date',
            'tgl4'        => 'nullable|date',
        ]);

        $pertemuanDipilih = collect($request->pertemuan)
            ->map(fn($p) => (int) $p)
            ->sort()
            ->values();

        // 1. Query Muhafidzoh via Model
        $query = Muhafidzoh::with(['tempat', 'kelompok'])
            ->whereHas('tempat', function($q) use ($request) {
                $q->where('nama_tempat', $request->gedung);
            });

        // 🔥 Filter Tanpa Staf (Menggunakan kolom 'keterangan')
        if ($request->tanpa_staf == '1') {
            $query->where('keterangan', '!=', 'STAF');
        }

        $muhafidzoh = $query->orderBy('nama_muhafidzoh')->get();
        $ids = $muhafidzoh->pluck('id_muhafidzoh');

        // 2. Ambil Data Absensi
        $absensis = Absensia::whereIn('id_muhafidzoh', $ids)
            ->whereIn('pertemuan', $pertemuanDipilih)
            ->get()
            ->groupBy('id_muhafidzoh');

        // 3. Load Template
        $templatePath = storage_path('app/templates/absensia.docx'); 
        
        if (!file_exists($templatePath)) {
            return back()->with('error', 'File template absensia.docx tidak ditemukan di storage/app/templates/');
        }

        $template = new TemplateProcessor($templatePath);

        // Mapping Bulan
        $namaBulan = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret',
            4 => 'April', 5 => 'Mei', 6 => 'Juni',
            7 => 'Juli', 8 => 'Agustus', 9 => 'September',
            10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];
        $template->setValue('bulan', strtoupper($namaBulan[$request->bulan]));

        // Mapping Header Tanggal
        for ($i = 1; $i <= 4; $i++) {
            if ($request->filled("tgl{$i}")) {
                $template->setValue(
                    "tgl{$i}",
                    Carbon::parse($request->input("tgl{$i}"))->format('d-m-Y')
                );
            } else {
                $template->setValue("tgl{$i}", '-');
            }
        }

        // 4. Susun Baris Tabel
        $rows = [];
        foreach ($muhafidzoh as $i => $m) {
            $total = 0;
            $row = [
                'no'   => $i + 1,
                'nama' => $m->nama_muhafidzoh, // ✅ PENTING: Sesuai nama kolom DB
                'ket'  => $m->keterangan ?? '-', // ✅ PENTING: Sesuai nama kolom DB
            ];

            // Loop 4 Slot Pertemuan untuk Kolom di Word
            for ($slot = 1; $slot <= 4; $slot++) {
                $row["s{$slot}"] = '-';

                if (isset($pertemuanDipilih[$slot - 1])) {
                    $p = $pertemuanDipilih[$slot - 1];
                    $id = $m->id_muhafidzoh;

                    if (isset($absensis[$id])) {
                        $absenItem = $absensis[$id]->firstWhere('pertemuan', $p);
                        
                        if ($absenItem) {
                            $statusMap = ['hadir'=>'H', 'izin'=>'I', 'alpha'=>'A'];
                            $row["s{$slot}"] = $statusMap[$absenItem->status] ?? '-'; 

                            if ($absenItem->status === 'hadir') {
                                $total++;
                            }
                        }
                    }
                }
            }

            $row['total'] = $total;
            $rows[] = $row;
        }

        // 5. Clone Row dan Isi Data
        $template->cloneRowAndSetValues('no', $rows);

        // 6. Simpan & Download
        $fileName = 'Absensi_Muhafidzoh_' . now()->format('Ymd_His') . '.docx';
        $outputPath = storage_path('app/temp/' . $fileName);

        if (!file_exists(dirname($outputPath))) {
            mkdir(dirname($outputPath), 0777, true);
        }

        $template->saveAs($outputPath);

        return response()->download($outputPath)->deleteFileAfterSend(true);
    }
}