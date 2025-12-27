<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Absensis;
use PhpOffice\PhpWord\TemplateProcessor;

class AbsensiController extends Controller
{
    // ... (Function index biarkan sama seperti kodemu, itu sudah benar logikanya) ...

    public function index(Request $request)
    {
        // ... (COPY PASTE KODE INDEX KAMU YANG LAMA DISINI) ...
        // Logika index kamu sudah benar karena mengambil riwayatAbsensi 
        // berdasarkan 'pertemuan > 0', bukan berdasarkan tanggal request.
        
        $title = 'Absensi Mahasiswi';
        
        // ... Filter Kelompok ...
        $kelompokList = DB::connection('mysql_siwak')->table('mahasiswi2')
            ->select('kelompok')->distinct()->orderBy('kelompok')->pluck('kelompok');

        $query = DB::connection('mysql_siwak')->table('mahasiswi2');

        $prodi    = $request->filled('prodi') ? trim($request->prodi) : null;
        $semester = $request->filled('semester') ? trim($request->semester) : null;
        $kelompok = $request->filled('kelompok') ? trim($request->kelompok) : null;

        if ($prodi)    $query->where('prodi', $prodi);
        if ($semester) $query->where('semester', $semester);
        if ($kelompok) $query->where('kelompok', $kelompok);

        // SELECT DATA
        $query->select('mahasiswi2.id', 'mahasiswi2.nama', 'mahasiswi2.prodi', 'mahasiswi2.semester', 'mahasiswi2.kelompok');
        $query->selectSub(function ($q) {
            $q->from('absensis')
                ->whereColumn('absensis.mahasiswi_id', 'mahasiswi2.id')
                ->where('status', 'hadir')
                ->where('pertemuan', '>', 0) 
                ->selectRaw('count(*)');
        }, 'total_hadir');

        $mahasiswi = $query->orderBy('nama', 'asc')->get();

        // AMBIL RIWAYAT (INI SUDAH BENAR, JANGAN DIUBAH)
        $mahasiswiIds = $mahasiswi->pluck('id');
        $riwayatAbsensi = DB::connection('mysql_siwak')->table('absensis')
            ->select('mahasiswi_id', 'pertemuan', 'status')
            ->whereIn('mahasiswi_id', $mahasiswiIds)
            ->where('pertemuan', '>', 0) // Ambil semua pertemuan yg sudah di-push
            ->get()
            ->groupBy('mahasiswi_id');

        foreach ($mahasiswi as $m) {
            // Cek apakah ada data di array riwayatAbsensi untuk ID ini?
            if (isset($riwayatAbsensi[$m->id])) {
                // Ubah format jadi [ 'pertemuan' => 'status' ] contoh: [ 1 => 'hadir', 2 => 'izin' ]
                $m->riwayat = $riwayatAbsensi[$m->id]->pluck('status', 'pertemuan')->toArray();
            } else {
                $m->riwayat = [];
            }
        }

        // dd($riwayatGrouped, $mahasiswi->first()->riwayat);

        return view('absensi.index', [ 
            'title'        => $title,
            'mahasiswi'    => $mahasiswi,
            'kelompokList' => $kelompokList
        ]);
    }

    public function refresh(Request $request)
    {
        $request->validate([
            'prodi'    => 'required',
            'semester' => 'required',
            'kelompok' => 'required',
        ]);
        $prodi    = trim($request->prodi);
        $semester = trim($request->semester);
        $kelompok = trim($request->kelompok);
        // Ambil mahasiswi sesuai filter
        $mahasiswi = DB::connection('mysql_siwak')
            ->table('mahasiswi2')
            ->where('prodi', $request->prodi)
            ->where('semester', $request->semester)
            ->where('kelompok', $request->kelompok)
            ->get();

        $ids = $mahasiswi->pluck('id')->toArray();

        // Ambil semua absensi mereka
        $absensis = DB::connection('mysql_siwak')
            ->table('absensis')
            ->whereIn('mahasiswi_id', $ids)
            ->where('pertemuan', '>', 0)
            ->get();

        // Susun data per mahasiswi
        $result = [];

        foreach ($mahasiswi as $m) {
            $result[$m->id] = [
                'riwayat' => [],
                'total_hadir' => 0,
            ];

            foreach ($absensis->where('mahasiswi_id', $m->id) as $a) {
                $p = (int) $a->pertemuan;
                $result[$m->id]['riwayat'][$p] = $a->status;

                if ($a->status === 'hadir') {
                    $result[$m->id]['total_hadir']++;
                }
            }
        }

        return response()->json([
            'success' => true,
            'data' => $result
        ]);
    }

    // --- PERBAIKAN UTAMA ADA DI SINI ---
    public function simpan(Request $request)
    {
        $request->validate([
            'mahasiswi_id' => 'required|integer',
            'tanggal'      => 'required|date',
            'status'       => 'required|in:hadir,izin,alpha'
        ]);

        DB::connection('mysql_siwak')->beginTransaction();

        try {
            // 1. Cek dulu apakah data di tanggal ini sudah punya 'pertemuan' (sudah dipush)?
            $existing = DB::connection('mysql_siwak')->table('absensis')
                ->where('mahasiswi_id', $request->mahasiswi_id)
                ->where('tanggal', $request->tanggal)
                ->first();

            // Kalau sudah ada pertemuannya (misal pertemuan 1), JANGAN DI-RESET jadi 0.
            // Kalau belum ada, biarkan 0.
            $pertemuanExisting = $existing ? $existing->pertemuan : 0;

            DB::connection('mysql_siwak')
            ->table('absensis')
            ->updateOrInsert(
                [
                    'mahasiswi_id' => $request->mahasiswi_id,
                    'tanggal'      => $request->tanggal,
                ],
                [
                    'status'     => $request->status,
                    'pertemuan'  => $pertemuanExisting, // <--- INI KUNCINYA
                    'updated_at' => now(),
                    // created_at tidak perlu di updateOrInsert jika update, 
                    // tapi jika insert manual bisa pakai if. Biar simpel update_at saja cukup.
                ]
            );
            
            // Jika ini insert baru, kita perlu pastikan created_at terisi (opsional, updateOrInsert handle update)

            DB::connection('mysql_siwak')->commit();

            $totalHadir = $this->getRealTotalHadir($request->mahasiswi_id);

            return response()->json([
                'success'     => true,
                'status'      => $request->status,
                'pertemuan'   => $pertemuanExisting, // Kirim balik pertemuan biar JS bisa update kolom history juga
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
            'mahasiswi_id' => 'required|integer',
            'tanggal'      => 'required|date',
            'status'       => 'required|in:hadir,izin,alpha',
            'pertemuan'    => 'required|integer|min:1|max:12',
        ]);

        // CEK: pertemuan ini sudah dipakai atau belum
        $sudahAda = DB::connection('mysql_siwak')->table('absensis')
            ->where('mahasiswi_id', $request->mahasiswi_id)
            ->where('pertemuan', $request->pertemuan)
            ->exists();

        if ($sudahAda) {
            return response()->json([
                'success' => false,
                'message' => "Gagal! Pertemuan ke-{$request->pertemuan} sudah terisi di tanggal lain."
            ], 400);
        }

        DB::connection('mysql_siwak')->beginTransaction();

        try {
            // ✅ INI SATU-SATUNYA UPDATE YANG BOLEH ADA
            DB::connection('mysql_siwak')
                ->table('absensis')
                ->where('mahasiswi_id', $request->mahasiswi_id)
                ->where('tanggal', $request->tanggal)
                ->update([
                    'pertemuan'  => $request->pertemuan,
                    'status'     => $request->status,
                    'updated_at' => now(),
                ]);

            DB::connection('mysql_siwak')->commit();

            $totalHadir = $this->getRealTotalHadir($request->mahasiswi_id);

            return response()->json([
                'success'     => true,
                'pertemuan'   => $request->pertemuan,
                'status'      => $request->status,
                'total_hadir' => $totalHadir
            ]);

        } catch (\Throwable $e) {
            DB::connection('mysql_siwak')->rollBack();
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    private function getRealTotalHadir($mahasiswiId)
    {
        return DB::connection('mysql_siwak')
            ->table('absensis')
            ->where('mahasiswi_id', $mahasiswiId)
            ->where('status', 'hadir')
            ->where('pertemuan', '>', 0)
            ->count();
    }

    public function export(Request $request)
    {
        $request->validate([
            'prodi'      => 'required',
            'semester'   => 'required',
            'kelompok'   => 'required',
            'pertemuan'  => 'required|array',
            'bulan'      => 'required|integer|min:1|max:12',
            'tgl1'       => 'nullable|date',
            'tgl2'       => 'nullable|date',
            'tgl3'       => 'nullable|date',
            'tgl4'       => 'nullable|date',
        ]);

        $pertemuanDipilih = collect($request->pertemuan)
            ->map(fn($p) => (int) $p)
            ->sort()
            ->values();

        // Ambil data mahasiswi
        $mahasiswi = DB::connection('mysql_siwak')
            ->table('mahasiswi2')
            ->where('prodi', $request->prodi)
            ->where('semester', $request->semester)
            ->where('kelompok', $request->kelompok)
            ->orderBy('nama')
            ->get();

        $ids = $mahasiswi->pluck('id');

        $absensis = DB::connection('mysql_siwak')
            ->table('absensis')
            ->whereIn('mahasiswi_id', $ids)
            ->whereIn('pertemuan', $pertemuanDipilih)
            ->get()
            ->groupBy('mahasiswi_id');

        // Template Word
        $templatePath = storage_path('app/templates/absensi.docx');
        $template = new TemplateProcessor($templatePath);
        $namaBulan = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret',
            4 => 'April', 5 => 'Mei', 6 => 'Juni',
            7 => 'Juli', 8 => 'Agustus', 9 => 'September',
            10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];

        $template->setValue('bulan', strtoupper($namaBulan[$request->bulan]));
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

        $rows = [];

        foreach ($mahasiswi as $i => $m) {
            $total = 0;

            $row = [
                'no'       => $i + 1,
                'nama'     => $m->nama,
                'prodi'    => $m->prodi,
                'semester' => $m->semester,
            ];

            // 🔥 PAKSA SELALU 4 SLOT
            for ($slot = 1; $slot <= 4; $slot++) {

                // default isi strip
                $row["tgl{$slot}"] = '-';
                $row["s{$slot}"]   = '-';

                // kalau slot ini ada pertemuan yg dipilih
                if (isset($pertemuanDipilih[$slot - 1])) {
                    $p = $pertemuanDipilih[$slot - 1];

                    if (isset($absensis[$m->id])) {
                        foreach ($absensis[$m->id] as $a) {
                            if ($a->pertemuan == $p) {
                                $row["tgl{$slot}"] = \Carbon\Carbon::parse($a->tanggal)->format('d-m-Y');
                                $row["s{$slot}"]   = $a->status;

                                if ($a->status === 'hadir') {
                                    $total++;
                                }
                                break;
                            }
                        }
                    }
                }
            }

            // 🔥 INI BARIS PENTING YANG KURANG
            $row['total'] = $total;

            $rows[] = $row;
        }

        $template->cloneRowAndSetValues('no', $rows);

        $fileName = 'absensi-' . now()->format('Ymd-His') . '.docx';
        $outputPath = storage_path('app/temp/' . $fileName);

        if (!file_exists(dirname($outputPath))) {
            mkdir(dirname($outputPath), 0777, true);
        }

        $template->saveAs($outputPath);

        return response()->download($outputPath)->deleteFileAfterSend(true);
    }
}