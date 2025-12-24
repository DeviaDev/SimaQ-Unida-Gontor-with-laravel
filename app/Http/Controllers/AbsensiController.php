<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Absensis;

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

        if ($request->filled('prodi')) $query->where('prodi', $request->prodi);
        if ($request->filled('semester')) $query->where('semester', $request->semester);
        if ($request->filled('kelompok')) $query->where('kelompok', $request->kelompok);

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
}