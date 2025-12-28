<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Muhafidzoh;
use PhpOffice\PhpWord\TemplateProcessor;

class AbsensiMuhafidzohController extends Controller
{
    public function index(Request $request)
    {
        $title = 'Absensi Muhafidzoh';
        
        $gedung = request('gedung');
        $hasGedung = !empty($gedung);

        if (!$hasGedung) {
            return view('absensi.anggota.tahfidz.tahfidzmuhafidzoh', [
                'title' => $title,
                'muhafidzoh' => [],
                'hasGedung' => false
            ]);
        }

        $muhafidzoh = DB::connection('mysql_siwak')
            ->table('muhafidzoh2')
            ->where('gedung', $gedung)
            ->orderBy('nama', 'asc')
            ->get();

        $ids = $muhafidzoh->pluck('id');

        // Ambil riwayat dari tabel absensia
        $riwayatAbsensi = DB::connection('mysql_siwak')
            ->table('absensia')
            ->select('muhafidzoh_id', 'pertemuan', 'status')
            ->whereIn('muhafidzoh_id', $ids)
            ->where('pertemuan', '>', 0)
            ->get()
            ->groupBy('muhafidzoh_id');

        // Hitung total hadir
        $totalHadirData = DB::connection('mysql_siwak')
            ->table('absensia')
            ->select('muhafidzoh_id', DB::raw('count(*) as total'))
            ->whereIn('muhafidzoh_id', $ids)
            ->where('status', 'hadir')
            ->where('pertemuan', '>', 0)
            ->groupBy('muhafidzoh_id')
            ->pluck('total', 'muhafidzoh_id');

        foreach ($muhafidzoh as $m) {
            if (isset($riwayatAbsensi[$m->id])) {
                $m->riwayat = $riwayatAbsensi[$m->id]->pluck('status', 'pertemuan')->toArray();
            } else {
                $m->riwayat = [];
            }
            $m->total_hadir = $totalHadirData[$m->id] ?? 0;
        }

        return view('absensi.anggota.tahfidz.tahfidzmuhafidzoh', [
            'title'      => $title,
            'muhafidzoh' => $muhafidzoh,
            'hasGedung'  => true
        ]);
    }

    // --- REFRESH DATA (AJAX) ---
    public function refresh(Request $request)
    {
        $gedung = $request->gedung;
        
        // 1. Ambil data muhafidzoh berdasarkan gedung
        $muhafidzoh = DB::connection('mysql_siwak')
            ->table('muhafidzoh2')
            ->where('gedung', $gedung)
            ->get();

        $ids = $muhafidzoh->pluck('id');

        // 2. Ambil semua absensi mereka (yang sudah dipush/pertemuan > 0)
        $absensis = DB::connection('mysql_siwak')
            ->table('absensia')
            ->whereIn('muhafidzoh_id', $ids)
            ->where('pertemuan', '>', 0)
            ->get();

        // 3. Format data untuk JSON
        $result = [];
        foreach ($muhafidzoh as $m) {
            $result[$m->id] = [
                'riwayat' => [],
                'total_hadir' => 0
            ];
            
            $myAbsen = $absensis->where('muhafidzoh_id', $m->id);
            
            foreach($myAbsen as $a) {
                $p = (int)$a->pertemuan;
                $result[$m->id]['riwayat'][$p] = $a->status;
                if ($a->status == 'hadir') {
                    $result[$m->id]['total_hadir']++;
                }
            }
        }

        return response()->json(['success' => true, 'data' => $result]);
    }

    public function simpan(Request $request)
    {
        $request->validate([
            'muhafidzoh_id' => 'required|integer',
            'tanggal'       => 'required|date',
            'status'        => 'required|in:hadir,izin,alpha'
        ]);

        DB::connection('mysql_siwak')->beginTransaction();
        try {
            $existing = DB::connection('mysql_siwak')
                ->table('absensia')
                ->where('muhafidzoh_id', $request->muhafidzoh_id)
                ->where('tanggal', $request->tanggal)
                ->first();

            $pertemuanExisting = $existing ? $existing->pertemuan : 0;

            DB::connection('mysql_siwak')
                ->table('absensia')
                ->updateOrInsert(
                    [
                        'muhafidzoh_id' => $request->muhafidzoh_id,
                        'tanggal'       => $request->tanggal,
                    ],
                    [
                        'status'     => $request->status,
                        'pertemuan'  => $pertemuanExisting,
                        'updated_at' => now(),
                    ]
                );

            DB::connection('mysql_siwak')->commit();

            $totalHadir = DB::connection('mysql_siwak')
                ->table('absensia')
                ->where('muhafidzoh_id', $request->muhafidzoh_id)
                ->where('status', 'hadir')
                ->where('pertemuan', '>', 0)
                ->count();

            return response()->json([
                'success' => true,
                'total_hadir' => $totalHadir
            ]);

        } catch (\Throwable $e) {
            DB::connection('mysql_siwak')->rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function pushPertemuan(Request $request)
    {
        $request->validate([
            'muhafidzoh_id' => 'required|integer',
            'tanggal'       => 'required|date',
            'status'        => 'required|in:hadir,izin,alpha',
            'pertemuan'     => 'required|integer|min:1|max:12',
        ]);

        $isExist = DB::connection('mysql_siwak')
            ->table('absensia')
            ->where('muhafidzoh_id', $request->muhafidzoh_id)
            ->where('pertemuan', $request->pertemuan)
            ->where('tanggal', '!=', $request->tanggal)
            ->exists();

        if ($isExist) {
             return response()->json([
                'success' => false, 
                'message' => "Gagal! Pertemuan ke-{$request->pertemuan} sudah terisi di tanggal lain."
            ], 400);
        }

        DB::connection('mysql_siwak')->beginTransaction();
        try {
            DB::connection('mysql_siwak')
                ->table('absensia')
                ->where('muhafidzoh_id', $request->muhafidzoh_id)
                ->where('tanggal', $request->tanggal)
                ->update([
                    'pertemuan'  => $request->pertemuan,
                    'status'     => $request->status,
                    'updated_at' => now()
                ]);

            DB::connection('mysql_siwak')->commit();

            $totalHadir = DB::connection('mysql_siwak')
                ->table('absensia')
                ->where('muhafidzoh_id', $request->muhafidzoh_id)
                ->where('status', 'hadir')
                ->where('pertemuan', '>', 0)
                ->count();

            return response()->json([
                'success' => true,
                'total_hadir' => $totalHadir
            ]);

        } catch (\Throwable $e) {
            DB::connection('mysql_siwak')->rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
    public function export(Request $request)
    {
        $request->validate([
            'gedung'      => 'required',
            'pertemuan'   => 'required|array',
            'bulan'       => 'required|integer|min:1|max:12',
            'tanpa_staf'  => 'nullable|in:0,1', // Validasi input checkbox baru
            'tgl1'        => 'nullable|date',
            'tgl2'        => 'nullable|date',
            'tgl3'        => 'nullable|date',
            'tgl4'        => 'nullable|date',
        ]);

        $pertemuanDipilih = collect($request->pertemuan)
            ->map(fn($p) => (int) $p)
            ->sort()
            ->values();

        // 1. Query Muhafidzoh
        $query = DB::connection('mysql_siwak')
            ->table('muhafidzoh2')
            ->where('gedung', $request->gedung);

        // 🔥 LOGIKA EXPORT TANPA STAF 🔥
        if ($request->tanpa_staf == '1') {
            $query->where('ket', '!=', 'STAF');
        }

        $muhafidzoh = $query->orderBy('nama')->get();
        $ids = $muhafidzoh->pluck('id');

        // 2. Ambil Data Absensi
        $absensis = DB::connection('mysql_siwak')
            ->table('absensia') // Pastikan nama tabel benar
            ->whereIn('muhafidzoh_id', $ids)
            ->whereIn('pertemuan', $pertemuanDipilih)
            ->get()
            ->groupBy('muhafidzoh_id');

        // 3. Load Template
        // Path sesuai request: storage/app/absensia.docx
        $templatePath = storage_path('app/templates/absensia.docx'); 
        
        // Cek file ada tidak
        if (!file_exists($templatePath)) {
            return back()->with('error', 'File template absensia.docx tidak ditemukan di storage/app/');
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

        // Mapping Header Tanggal (tgl1 - tgl4)
        for ($i = 1; $i <= 4; $i++) {
            if ($request->filled("tgl{$i}")) {
                $template->setValue(
                    "tgl{$i}",
                    \Carbon\Carbon::parse($request->input("tgl{$i}"))->format('d-m-Y')
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
                'nama' => $m->nama,
                'ket'  => $m->ket ?? '-', // Mengisi variabel ${ket} di template
            ];

            // Loop 4 Slot Pertemuan untuk Header
            for ($slot = 1; $slot <= 4; $slot++) {
                // Default value
                $row["s{$slot}"] = '-';

                // Cek apakah slot ini dipilih user (dari checkbox pertemuan)
                // Contoh: user pilih pertemuan [1, 3]. 
                // Slot 1 = Pertemuan 1. Slot 2 = Pertemuan 3.
                if (isset($pertemuanDipilih[$slot - 1])) {
                    $p = $pertemuanDipilih[$slot - 1];

                    // Cek absensi orang ini
                    if (isset($absensis[$m->id])) {
                        $absenItem = $absensis[$m->id]->firstWhere('pertemuan', $p);
                        
                        if ($absenItem) {
                            // Konversi status jadi inisial (opsional, atau full text)
                            // Jika mau 'H', 'I', 'A' ganti baris ini:
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
        // Pastikan di template Word variabelnya: ${no}, ${nama}, ${ket}, ${s1}, ${s2}, ${s3}, ${s4}, ${total}
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