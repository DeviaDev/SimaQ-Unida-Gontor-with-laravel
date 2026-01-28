<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Absensis;      // Pastikan Model Absensi sudah ada
use App\Models\Mahasiswi;    // Pakai Model Mahasiswi yang BARU
use App\Models\KelompokLT;   // Pakai Model KelompokLT
use PhpOffice\PhpWord\TemplateProcessor;

class AbsensiController extends Controller
{
    public function index(Request $request)
    {
        $title = 'Absensi Mahasiswi';
        
        // --- PERUBAHAN 1: Ambil Kelompok dari tabel KelompokLT ---
        // Kita ambil objeknya agar bisa dapat id_kelompok dan kode_kelompok
        $kelompokList = KelompokLT::orderBy('kode_kelompok', 'asc')->get();

        // Siapkan Query pakai Model Mahasiswi (bukan DB::table lagi)
        $query = Mahasiswi::query();

        $prodi    = $request->filled('prodi') ? trim($request->prodi) : null;
        $semester = $request->filled('semester') ? trim($request->semester) : null;
        $kelompok = $request->filled('kelompok') ? trim($request->kelompok) : null;

        if ($prodi)    $query->where('prodi', $prodi);
        if ($semester) $query->where('semester', $semester);
        
        // --- PERUBAHAN 2: Filter pakai id_kelompok ---
        if ($kelompok) $query->where('id_kelompok', $kelompok);

        // --- PERUBAHAN 3: Hitung total_hadir pakai Eloquent (lebih rapi) ---
        $query->withCount(['absensi as total_hadir' => function ($q) {
            $q->where('status', 'hadir')->where('pertemuan', '>', 0);
        }]);

        // Order by nama_mahasiswi (sesuai kolom baru)
        $mahasiswi = $query->orderBy('nama_mahasiswi', 'asc')->get();

        // --- AMBIL RIWAYAT (Struktur tetap sama, cuma ganti nama kolom & model) ---
        // Ambil array ID Mahasiswi (sekarang namanya id_mahasiswi)
        $mahasiswiIds = $mahasiswi->pluck('id_mahasiswi');

        $riwayatAbsensi = Absensis::select('id_mahasiswi', 'pertemuan', 'status')
            ->whereIn('id_mahasiswi', $mahasiswiIds)
            ->where('pertemuan', '>', 0)
            ->get()
            ->groupBy('id_mahasiswi');

        foreach ($mahasiswi as $m) {
            // Gunakan id_mahasiswi sebagai key
            if (isset($riwayatAbsensi[$m->id_mahasiswi])) {
                $m->riwayat = $riwayatAbsensi[$m->id_mahasiswi]->pluck('status', 'pertemuan')->toArray();
            } else {
                $m->riwayat = [];
            }
        }

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

        // --- PERUBAHAN: Pakai Model Mahasiswi & relasi Absensi ---
        $mahasiswi = Mahasiswi::with(['absensi' => function($q) {
                $q->where('pertemuan', '>', 0);
            }])
            ->where('prodi', $request->prodi)
            ->where('semester', $request->semester)
            ->where('id_kelompok', $request->kelompok) // filter by id_kelompok
            ->get();

        $result = [];

        foreach ($mahasiswi as $m) {
            $id = $m->id_mahasiswi; // Simpan id_mahasiswi ke variabel biar pendek

            $result[$id] = [
                'riwayat'     => [],
                'total_hadir' => 0,
            ];

            // Loop data dari relasi absensi
            foreach ($m->absensi as $a) {
                $p = (int) $a->pertemuan;
                $result[$id]['riwayat'][$p] = $a->status;

                if ($a->status === 'hadir') {
                    $result[$id]['total_hadir']++;
                }
            }
        }

        return response()->json([
            'success' => true,
            'data'    => $result
        ]);
    }

    public function simpan(Request $request)
    {
        $request->validate([
            'mahasiswi_id' => 'required|integer', // JS mengirim id_mahasiswi dengan nama param ini
            'tanggal'      => 'required|date',
            'status'       => 'required|in:hadir,izin,alpha'
        ]);

        DB::beginTransaction();

        try {
            // 1. Cek data existing pakai Model Absensi
            $mhs = Mahasiswi::findOrFail($request->mahasiswi_id);
            // Ingat: kolom FK di tabel absensi harusnya 'id_mahasiswi'
            $existing = Absensis::where('id_mahasiswi', $request->mahasiswi_id)
                ->where('tanggal', $request->tanggal)
                ->first();

            $pertemuanExisting = $existing ? $existing->pertemuan : 0;

            // 2. Update atau Insert
            Absensis::updateOrCreate(
                [
                    'id_mahasiswi' => $request->mahasiswi_id,
                    'tanggal'      => $request->tanggal,
                ],
                [
                    'status'    => $request->status,
                    'pertemuan' => $pertemuanExisting,
                    // updated_at otomatis diurus Eloquent
                    // --- TAMBAHAN PENTING DI SINI ---
                    'prodi'     => $mhs->prodi,       // Simpan Prodi
                    'semester'  => $mhs->semester,    // Simpan Semester
                    'kelompok'  => $mhs->id_kelompok, // Simpan Kelompok (sesuaikan nama kolom di DB absensis)
                ]
            );
            
            DB::commit();

            // Hitung ulang total hadir
            $totalHadir = $this->getRealTotalHadir($request->mahasiswi_id);

            return response()->json([
                'success'     => true,
                'status'      => $request->status,
                'pertemuan'   => $pertemuanExisting,
                'total_hadir' => $totalHadir
            ]);

        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function pushPertemuan(Request $request)
    {
        // Validasi Input
        $request->validate([
            'mahasiswi_id' => 'required|integer',
            'tanggal'      => 'required|date',
            'status'       => 'required|in:hadir,izin,alpha',
            'pertemuan'    => 'required|integer|min:1|max:12',
        ]);

        // 1. Cek Duplikasi Pertemuan di Tanggal LAIN
        // (Jika pertemuan 1 sudah ada di tanggal 01-01, jangan boleh isi pertemuan 1 lagi di tanggal 02-01)
        $sudahAdaDiTanggalLain = Absensis::where('id_mahasiswi', $request->mahasiswi_id)
            ->where('pertemuan', $request->pertemuan)
            ->where('tanggal', '!=', $request->tanggal)
            ->exists();

        if ($sudahAdaDiTanggalLain) {
            return response()->json([
                'success' => false,
                'message' => "Pertemuan ke-{$request->pertemuan} sudah terisi di tanggal lain."
            ], 400); // Bad Request
        }

        DB::beginTransaction();

        try {
            // 2. Gunakan updateOrCreate agar lebih aman dan rapi
            // Ini otomatis handle INSERT jika belum ada, atau UPDATE jika sudah ada
            $mhs = Mahasiswi::findOrFail($request->mahasiswi_id);

            Absensis::updateOrCreate(
                [
                    'id_mahasiswi' => $request->mahasiswi_id,
                    'tanggal'      => $request->tanggal,
                ],
                [
                    'pertemuan' => $request->pertemuan,
                    'status'    => $request->status,
                    // created_at & updated_at otomatis diisi oleh Eloquent
                    // --- TAMBAHAN PENTING DI SINI JUGA ---
                    'prodi'     => $mhs->prodi,
                    'semester'  => $mhs->semester,
                    'kelompok'  => $mhs->id_kelompok,
                ]
            );

            DB::commit();

            // Hitung total hadir terbaru
            $totalHadir = Absensis::where('id_mahasiswi', $request->mahasiswi_id)
                ->where('status', 'hadir')
                ->where('pertemuan', '>', 0)
                ->count();

            return response()->json([
                'success'     => true,
                'pertemuan'   => $request->pertemuan,
                'status'      => $request->status,
                'total_hadir' => $totalHadir
            ]);

        } catch (\Throwable $e) {
            DB::rollBack();
            
            // Return JSON error agar tidak muncul "<!DOCTYPE..." di console
            return response()->json([
                'success' => false,
                'message' => 'Server Error: ' . $e->getMessage()
            ], 500);
        }
    }

    private function getRealTotalHadir($mahasiswiId)
    {
        return Absensis::where('id_mahasiswi', $mahasiswiId)
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

        // --- PERUBAHAN: Pakai Model Mahasiswi ---
        $mahasiswi = Mahasiswi::where('prodi', $request->prodi)
            ->where('semester', $request->semester)
            ->where('id_kelompok', $request->kelompok)
            ->orderBy('nama_mahasiswi') // kolom baru
            ->get();

        $ids = $mahasiswi->pluck('id_mahasiswi');

        // --- PERUBAHAN: Pakai Model Absensi ---
        $absensis = Absensis::whereIn('id_mahasiswi', $ids)
            ->whereIn('pertemuan', $pertemuanDipilih)
            ->get()
            ->groupBy('id_mahasiswi'); // Group by kolom baru

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
                'nama'     => $m->nama_mahasiswi, // GANTI: nama_mahasiswi
                'prodi'    => $m->prodi,
                'semester' => $m->semester,
            ];

            for ($slot = 1; $slot <= 4; $slot++) {
                $row["tgl{$slot}"] = '-';
                $row["s{$slot}"]   = '-';

                if (isset($pertemuanDipilih[$slot - 1])) {
                    $p = $pertemuanDipilih[$slot - 1];

                    // GANTI KEY ARRAY ke id_mahasiswi
                    if (isset($absensis[$m->id_mahasiswi])) {
                        foreach ($absensis[$m->id_mahasiswi] as $a) {
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