<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. LOGIC PEKAN KE-
        $startDate = Carbon::create(2026, 1, 1);
        $now = Carbon::now();
        $daysPassed = $startDate->diffInDays($now); // Simplified logic
        $pekan = ($now->lessThan($startDate)) ? 1 : floor($daysPassed / 7) + 1;

        if ($now->lessThan($startDate)) {
            $pekan = 1;
        } else {
            $daysPassed = $startDate->diffInDays($now);
            $pekan = floor($daysPassed / 7) + 1;
        }

        // 2. HITUNG JUMLAH KARTU
        $totalMahasiswi = DB::table('mahasiswi')->count();
        $totalMuhafidzoh = DB::table('muhafidzoh')->count();
        $totalKelompok = DB::table('kelompok_lt')->count();

        // 3. DATA GRAFIK ABSENSI
        // 3. DATA GRAFIK ABSENSI (LANGSUNG DARI TABEL ABSENSIS)
        // Karena di tabel absensis sudah ada kolom 'prodi', kita tidak perlu join
        $chartData = DB::table('absensis')
            ->select('prodi',
                DB::raw('SUM(CASE WHEN status = "hadir" THEN 1 ELSE 0 END) as total_hadir'),
                DB::raw('SUM(CASE WHEN status = "izin" THEN 1 ELSE 0 END) as total_izin'),
                DB::raw('SUM(CASE WHEN status = "alpha" THEN 1 ELSE 0 END) as total_alpha')
            )
            ->whereNotNull('prodi') // Pastikan prodi tidak kosong
            ->groupBy('prodi')
            ->orderByDesc('total_hadir')
            ->limit(15)
            ->get();

        // ARRAY DATA
        $prodiLabels = []; $dataHadir = []; $dataIzin = []; $dataAlpha = [];
        foreach ($chartData as $data) {
            $prodiLabels[] = $data->prodi;
            $dataHadir[] = (int) $data->total_hadir;
            $dataIzin[] = (int) $data->total_izin;
            $dataAlpha[] = (int) $data->total_alpha;
        }
        
        // JIKA DATA KOSONG (Agar grafik tidak error tapi kosong)
        $chartGedung = DB::table('absensia') // Pakai tabel absensia (muhafidzoh)
            ->select('gedung',
                DB::raw('SUM(CASE WHEN status = "hadir" THEN 1 ELSE 0 END) as total_hadir'),
                DB::raw('SUM(CASE WHEN status = "izin" THEN 1 ELSE 0 END) as total_izin'),
                DB::raw('SUM(CASE WHEN status = "alpha" THEN 1 ELSE 0 END) as total_alpha')
            )
            ->whereNotNull('gedung') // Pastikan gedung tidak null
            ->where('gedung', '!=', '') // Pastikan tidak string kosong
            ->groupBy('gedung')
            ->orderByDesc('total_hadir')
            ->get();

        $gedungLabels = [];
        $gedungHadir = [];
        $gedungIzin = [];
        $gedungAlpha = [];

        foreach ($chartGedung as $d) {
            $gedungLabels[] = $d->gedung;
            $gedungHadir[]  = (int) $d->total_hadir;
            $gedungIzin[]   = (int) $d->total_izin;
            $gedungAlpha[]  = (int) $d->total_alpha;
        }
        
        // Handling jika data kosong
        if (empty($gedungLabels)) {
             $gedungLabels = ['Belum Ada Data Gedung'];
             $gedungHadir = [0]; $gedungIzin = [0]; $gedungAlpha = [0];
        }

        // A. Ambil Data Mahasiswi
        $dataMhs = DB::table('mahatilawah')
            ->join('mahasiswi', 'mahatilawah.id_mahasiswi', '=', 'mahasiswi.id_mahasiswi')
            // PERUBAHAN: Tambahkan 'mahasiswi.semester' di select
            ->select('mahasiswi.nama_mahasiswi as nama', 'mahasiswi.prodi', 'mahasiswi.semester', 'mahatilawah.khatam_ke', 'mahatilawah.juz', DB::raw("'Mahasiswi' as status"))
            ->get();

        // B. Ambil Data Pengurus
        $dataPengurus = DB::table('tilawah_pengurus')
            ->join('pengurus', 'tilawah_pengurus.id_pengurus', '=', 'pengurus.id') 
            // PERUBAHAN: Tambahkan DB::raw("NULL as semester") agar tidak error "Undefined property" saat diloop
            ->select('pengurus.nama as nama', DB::raw("'Pengurus' as prodi"), DB::raw("NULL as semester"), 'tilawah_pengurus.khatam_ke', 'tilawah_pengurus.juz', DB::raw("'Pengurus' as status"))
            ->get();

        // C. Gabung Data (Merge)
        $mergedData = $dataMhs->merge($dataPengurus);

        // D. Hitung Score & Urutkan
        $leaderboard = $mergedData->map(function($item) {
            // Hitung jumlah juz (explode string)
            $juzCount = $item->juz ? count(explode(',', $item->juz)) : 0;
            
            // Rumus Score: ((Khatam - 1) * 30) + Juz Count
            $totalScore = (($item->khatam_ke - 1) * 30) + $juzCount;
            
            $item->total_score = $totalScore;
            $item->sisa_juz = $juzCount; // Simpan untuk display
            $item->jml_khatam = $item->khatam_ke - 1; // Simpan untuk display (berapa kali khatam penuh)
            
            return $item;
        })->sortByDesc('total_score')->take(5); // Ambil Top 10 Tertinggi

        $reminders = [];

        // A. Cek Kelompok yang BELUM Absen Tahfidz Pekan Ini
        // Ambil ID Kelompok yang SUDAH absen di pekan ini
        $kelompokSudahAbsen = DB::table('absensis')
            ->where('pertemuan', $pekan)
            ->pluck('kelompok') // Mengambil array ID kelompok
            ->toArray();

        // Ambil Data Kelompok yang ID-nya TIDAK ADA di daftar sudah absen
        // Kita limit 5 saja biar tidak kepanjangan di dashboard
        $kelompokBelumAbsen = DB::table('kelompok_lt')
            ->whereNotIn('id_kelompok', $kelompokSudahAbsen)
            // ->limit(5) 
            ->get();

        // Masukkan ke array reminders
        foreach ($kelompokBelumAbsen as $k) {
            $reminders[] = [
                'type' => 'danger', // Merah (Urgent)
                'icon' => 'fas fa-exclamation-triangle',
                'time' => 'Urgent',
                'message' => "Absensi Tahfidz <b>{$k->kode_kelompok}</b> Pekan ke-{$pekan} belum diisi. Mohon segera input data."
            ];
        }

        // B. Cek Muhafidzoh yang BELUM Absen Pekan Ini (Opsional)
        // Logika: Cek tabel 'absensia', bandingkan dengan tabel 'muhafidzoh'
        // B. REMINDER TAHFIDZ (MUHAFIDZOH) - GROUP BY GEDUNG
        $muhafidzohSudahAbsen = DB::table('absensia')
            ->where('pertemuan', $pekan)
            ->pluck('id_muhafidzoh')
            ->toArray();

        // Ambil Daftar Gedung yang Memiliki Muhafidzoh Belum Absen
        // Kita gunakan DISTINCT agar nama gedung tidak muncul berulang-ulang
        $gedungBelumAbsen = DB::table('muhafidzoh')
            ->leftJoin('tempat', 'muhafidzoh.id_tempat', '=', 'tempat.id_tempat') 
            ->whereNotIn('muhafidzoh.id_muhafidzoh', $muhafidzohSudahAbsen)
            ->select('tempat.nama_tempat as nama_gedung')
            ->distinct() // Agar gedung yang sama hanya muncul sekali
            ->get();

        foreach ($gedungBelumAbsen as $g) {
            $namaGedung = $g->nama_gedung ?? 'Tanpa Gedung'; 
            
            $reminders[] = [
                'type' => 'warning', // Kuning
                'icon' => 'fas fa-building', // Ganti icon jadi gedung
                'time' => 'Penting',
                'message' => "Absensi Tahfidz Muhafidzoh Gedung <b>{$namaGedung}</b> belum diisi lengkap."
            ];
        }

        // Jika semua sudah absen (Reminders kosong)
        if (empty($reminders)) {
            $reminders[] = [
                'type' => 'success',
                'icon' => 'fas fa-check-circle',
                'time' => 'Good Job',
                'message' => "Semua data absensi Pekan ke-{$pekan} telah lengkap!"
            ];
        }

        $data = [
            'title' => 'Dashboard',
            'pekan' => $pekan,
            'total_mahasiswi' => $totalMahasiswi,
            'total_muhafidzoh' => $totalMuhafidzoh,
            'total_kelompok' => $totalKelompok,
            
            // Data Mahasiswi
            'prodiLabels' => $prodiLabels,
            'dataHadir' => $dataHadir,
            'dataIzin' => $dataIzin,
            'dataAlpha' => $dataAlpha,

            // Data Muhafidzoh (BARU)
            'gedungLabels' => $gedungLabels,
            'gedungHadir'  => $gedungHadir,
            'gedungIzin'   => $gedungIzin,
            'gedungAlpha'  => $gedungAlpha,
            'leaderboard' => $leaderboard,
            'reminders' => $reminders
        ];

        return view('dashboard', $data);
    }
}