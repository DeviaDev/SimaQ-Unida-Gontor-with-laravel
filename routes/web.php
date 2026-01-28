<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DataController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\UjianController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DokumentasiController;
use App\Http\Controllers\AbsensiAnggotaController;
use App\Http\Controllers\AbsensiPengurusController;
use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\AbsensiMuhafidzohController;
use App\Http\Controllers\TilawahMahasiswiController;
use App\Http\Controllers\LaporanKegiatanController;
use App\Http\Controllers\TilawahPengurusController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

// --- AUTHENTICATION ---
Route::get('login', [LoginController::class,'login'])->name('login');
Route::post('login', [LoginController::class,'loginProses'])->name('loginProses');
Route::get('logout', [LoginController::class,'logout'])->name('logout');

// --- PROTECTED ROUTES (Halaman yang butuh Login) ---
Route::middleware('checkLogin')->group(function(){

    // 1. DASHBOARD
    Route::get('dashboard', [DashboardController::class,'index'])->name('dashboard');

    // 2. USER MANAGEMENT
    Route::prefix('user')->group(function () {
        Route::get('/', [UserController::class,'index'])->name('user');
        Route::get('/create', [UserController::class,'create'])->name('userCreate');
        Route::post('/store', [UserController::class,'store'])->name('userStore');
        Route::get('/edit/{id}', [UserController::class, 'edit'])->name('userEdit');
        Route::post('/update/{id}', [UserController::class, 'update'])->name('userUpdate');
        Route::delete('/destroy/{id}', [UserController::class, 'destroy'])->name('userDestroy');
        Route::post('/import', [UserController::class, 'importExcel'])->name('userImport');
        Route::get('/excel', [UserController::class, 'excel'])->name('userExport');
    });
    Route::get('/admin/user/pdf', [UserController::class, 'pdf'])->name('userPdf');

    // 3. MASTER DATA (DataController)
    Route::get('index', [DataController::class,'index'])->name('index');

    // Dosen
    Route::prefix('dosen')->group(function () {
        Route::get('/', [DataController::class, 'dosen'])->name('dosen');
        Route::get('/create', [DataController::class, 'create4'])->name('dosenCreate');
        Route::post('/store', [DataController::class, 'store4'])->name('dosenStore');
        Route::get('/edit/{id_dosen}', [DataController::class, 'edit4'])->name('dosenEdit');
        Route::post('/update/{id_dosen}', [DataController::class, 'update4'])->name('dosenUpdate');
        Route::delete('/destroy/{id_dosen}', [DataController::class, 'destroy4'])->name('dosenDestroy');
        Route::post('/import', [DataController::class, 'importExcel4'])->name('dosenImport');
        Route::get('/excel', [DataController::class, 'excel4'])->name('dosenExport');
        Route::get('/pdf', [DataController::class, 'pdf4'])->name('dosenPdf');
        Route::get('/get-tempat/{id_kelompok}', [DataController::class, 'getTempat']);
    });

    // Mahasiswi
    Route::prefix('mahasiswi')->group(function () {
        Route::get('/', [DataController::class, 'mahasiswi'])->name('mahasiswi');
        Route::get('/create', [DataController::class, 'create3'])->name('mahasiswiCreate');
        Route::post('/store', [DataController::class, 'store3'])->name('mahasiswiStore');
        Route::get('/edit/{id_mahasiswi}', [DataController::class, 'edit3'])->name('mahasiswiEdit');
        Route::post('/update/{id_mahasiswi}', [DataController::class, 'update3'])->name('mahasiswiUpdate');
        Route::delete('/destroy/{id_mahasiswi}', [DataController::class, 'destroy3'])->name('mahasiswiDestroy');
        Route::post('/import', [DataController::class, 'importExcel3'])->name('mahasiswiImport');
        Route::get('/excel', [DataController::class, 'excel3'])->name('mahasiswiExport');
        Route::get('/pdf', [DataController::class, 'pdf3'])->name('mahasiswiPdf');
    });

    // Muhafidzoh
    Route::prefix('muhafidzoh')->group(function () {
        Route::get('/', [DataController::class, 'muhafidzoh'])->name('muhafidzoh');
        Route::get('/create', [DataController::class, 'create2'])->name('muhafidzohCreate');
        Route::post('/store', [DataController::class, 'store2'])->name('muhafidzohStore');
        Route::get('/edit/{id_muhafidzoh}', [DataController::class, 'edit2'])->name('muhafidzohEdit');
        Route::post('/update/{id_muhafidzoh}', [DataController::class, 'update2'])->name('muhafidzohUpdate');
        Route::delete('/destroy/{id_muhafidzoh}', [DataController::class, 'destroy2'])->name('muhafidzohDestroy');
        Route::post('/import', [DataController::class, 'importExcel2'])->name('muhafidzohImport');
        Route::get('/excel', [DataController::class, 'excel2'])->name('muhafidzohExport');
        Route::get('/pdf', [DataController::class, 'pdf2'])->name('muhafidzohPdf');
    });

    // Pengurus
    Route::prefix('pengurus')->group(function () {
        Route::get('/', [DataController::class, 'pengurus'])->name('pengurus');
        Route::get('/create', [DataController::class, 'create1'])->name('pengurusCreate');
        Route::post('/store', [DataController::class, 'store1'])->name('pengurusStore');
        Route::get('/edit/{id}', [DataController::class, 'edit1'])->name('pengurusEdit');
        Route::post('/update/{id}', [DataController::class, 'update1'])->name('pengurusUpdate');
        Route::delete('/destroy/{id}', [DataController::class, 'destroy1'])->name('pengurusDestroy');
        Route::post('/import', [DataController::class, 'importExcel1'])->name('pengurusImport');
        Route::get('/excel', [DataController::class, 'excel1'])->name('pengurusExport');
        Route::get('/pdf', [DataController::class, 'pdf1'])->name('pengurusPdf');
    });

    // 4. ABSENSI
    Route::get('/absensi', [AbsensiController::class, 'index'])->name('absensi.index');
    Route::post('/absensi/simpan', [AbsensiController::class, 'simpan'])->name('absensi.simpan');
    Route::post('/absensi/push-pertemuan', [AbsensiController::class, 'pushPertemuan'])->name('absensi.push');
    Route::post('/absensi/refresh', [AbsensiController::class, 'refresh'])->name('absensi.refresh');
    Route::post('/absensi/export', [AbsensiController::class, 'export'])->name('absensi.export');

    Route::get('/absensi/anggota/tahfidz/mahasiswi',[AbsensiAnggotaController::class, 'absensiTahfidzMahasiswi'])->name('absensiTahfidzMahasiswi');
    Route::get('absensi/anggota/tahfidz/muhafidzoh', [AbsensiAnggotaController::class,'absensiTahfidzMuhafidzoh'])->name('absensiTahfidzMuhafidzoh');

    // Absensi Muhafidzoh (Group)
    Route::prefix('absensi-muhafidzoh')->name('absensi_muhafidzoh.')->group(function () {
        Route::get('/', [AbsensiMuhafidzohController::class, 'index'])->name('index');
        Route::post('/simpan', [AbsensiMuhafidzohController::class, 'simpan'])->name('simpan');
        Route::post('/push', [AbsensiMuhafidzohController::class, 'pushPertemuan'])->name('push');
        Route::post('/refresh', [AbsensiMuhafidzohController::class, 'refresh'])->name('refresh');
        Route::post('/export', [AbsensiMuhafidzohController::class, 'export'])->name('export');
    });

    // Tilawah & Lainnya
    Route::get('/tilawah-mahasiswi', [TilawahMahasiswiController::class, 'index'])->name('absensiTilawahMahasiswi');
    Route::post('/tilawah-mahasiswi/simpan', [TilawahMahasiswiController::class, 'simpan'])->name('tilawah.simpan');
    Route::post('/tilawah-mahasiswi/simpan-semua', [TilawahMahasiswiController::class, 'simpanSemua'])->name('tilawah.simpanSemua');
    Route::post('/tilawah-mahasiswi/export', [TilawahMahasiswiController::class, 'exportDocx'])->name('tilawah.export');

    Route::get('absensi/anggota/tilawah/muhafidzoh', [AbsensiAnggotaController::class,'absensiTilawahMuhafidzoh'])->name('absensiTilawahMuhafidzoh');
    Route::get('absensi/anggota/tilawah/staf', [AbsensiAnggotaController::class,'absensiTilawahStaf'])->name('absensiTilawahStaf');
    Route::get('absensi/anggota/tilawah/dosen', [AbsensiAnggotaController::class,'absensiTilawahDosen'])->name('absensiTilawahDosen');
    Route::get('absensi/anggota/tilawah/mahasiswi', [AbsensiAnggotaController::class,'absensiTilawahMahasiswi'])->name('absensiTilawahMahasiswi');

    // Laporan Kegiatan
    Route::prefix('laporan-kegiatan')->group(function () {
        Route::get('/', [LaporanKegiatanController::class, 'index'])->name('laporan.index');
        Route::post('/store', [LaporanKegiatanController::class, 'store'])->name('laporan.store'); 
        Route::get('/{id}', [LaporanKegiatanController::class, 'show'])->name('laporan.show'); 
        Route::post('/update-absensi/{id}', [LaporanKegiatanController::class, 'updateAbsensi'])->name('laporan.update_absensi'); 
        Route::get('/export/{id}', [LaporanKegiatanController::class, 'export'])->name('laporan.export');
    });

    // Pengurus Activity
    Route::get('/pengurus/tilawah', [TilawahPengurusController::class, 'index'])->name('pengurusTilawah');
    Route::post('/pengurus/tilawah/simpan', [TilawahPengurusController::class, 'simpanSemua'])->name('pengurus.tilawah.simpan');
    Route::post('/pengurus/tilawah/export', [TilawahPengurusController::class, 'exportDocx'])->name('pengurus.tilawah.export');
    Route::get('/pengurus/taujihat', [AbsensiPengurusController::class,'pengurusTaujihat'])->name('pengurusTaujihat');
    Route::get('/pengurus/lailatu', [AbsensiPengurusController::class,'pengurusLailatu'])->name('pengurusLailatu');


    // 5. UJIAN
    Route::get('ujian/index', [UjianController::class,'index'])->name('ujian.index');

    // Ujian Mandiri
    Route::prefix('mandiri')->group(function () {
        Route::get('/', [UjianController::class, 'mandiri'])->name('mandiri');
        Route::get('/create', [UjianController::class, 'create5'])->name('mandiriCreate');
        Route::post('/store', [UjianController::class, 'store5'])->name('mandiriStore');
        Route::get('/edit/{id_ujian_mandiri}', [UjianController::class, 'edit5'])->name('mandiriEdit');
        Route::put('/update/{id_ujian_mandiri}', [UjianController::class, 'update5'])->name('mandiriUpdate');
        Route::delete('/destroy/{id_ujian_mandiri}', [UjianController::class, 'destroy5'])->name('mandiriDestroy');
        Route::get('/excel', [UjianController::class, 'excel5'])->name('mandiriExport');
        Route::get('/pdf', [UjianController::class, 'pdf5'])->name('mandiriPdf');
    });

    // Ujian Remedial
    Route::prefix('tahfidz/remedial')->group(function () {
        Route::get('/', [UjianController::class, 'remedial'])->name('remedial'); 
        Route::get('/edit/{id}', [UjianController::class, 'remedialEdit'])->name('remedialEdit');
        Route::post('/update/{id}', [UjianController::class, 'remedialUpdate'])->name('remedialUpdate');
        Route::delete('/destroy/{id}', [UjianController::class, 'remedialDestroy'])->name('remedialDestroy');
        Route::post('/update-inline', [UjianController::class, 'remedialUpdateInline'])->name('remedialUpdateInline');
        
        Route::get('/export-excel', [UjianController::class, 'remedialExportExcel'])->name('remedialExportExcel');
        Route::get('/export-pdf', [UjianController::class, 'remedialExportPdf'])->name('remedialExportPdf');
    });

    // --- UJIAN TAHSIN (FIXED VERSION) ---
    Route::prefix('tahsin')->group(function () {
        Route::get('/', [UjianController::class, 'tahsin'])->name('tahsin');
        Route::get('/create', [UjianController::class, 'createTahsin'])->name('tahsinCreate');
        Route::post('/store', [UjianController::class, 'storeTahsin'])->name('tahsinStore');
        Route::get('/edit/{id_tahsin}', [UjianController::class, 'editTahsin'])->name('tahsinEdit');
        
        // Menggunakan PUT (Sesuai Controller & View)
        Route::put('/update/{id_tahsin}', [UjianController::class, 'updateTahsin'])->name('tahsinUpdate');
        
        // Menggunakan DELETE (Sesuai Controller)
        Route::delete('/destroy/{id_tahsin}', [UjianController::class, 'destroyTahsin'])->name('tahsinDestroy');
        
        Route::get('/export-excel', [UjianController::class, 'exportExcel'])->name('tahsinExportExcel');
        Route::get('/export-pdf', [UjianController::class, 'exportPdf'])->name('tahsinExportPdf');
    });

    // 6. DOKUMENTASI
    Route::get('dokumentasi', [DokumentasiController::class,'dokumentasi'])->name('dokumentasi');

}); // Tutup Middleware checkLogin