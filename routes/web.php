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

// --- PROTECTED ROUTES ---
Route::middleware('checkLogin')->group(function(){

    // Dashboard
    Route::get('dashboard', [DashboardController::class,'index'])->name('dashboard');

    // User Management
    Route::get('user', [UserController::class,'index'])->name('user');
    Route::get('user/create', [UserController::class,'create'])->name('userCreate');
    Route::get('/user/edit/{id}', [UserController::class, 'edit'])->name('userEdit');
    Route::post('/user/update/{id}', [UserController::class, 'update'])->name('userUpdate');
    Route::delete('/user/destroy/{id}', [UserController::class, 'destroy'])->name('userDestroy');
    Route::post('user/store', [UserController::class,'store'])->name('userStore');
    Route::post('/user/import', [UserController::class, 'importExcel'])->name('userImport');
    Route::get('/user/excel', [UserController::class, 'excel'])->name('userExport');
    Route::get('/admin/user/pdf', [UserController::class, 'pdf'])->name('userPdf');

    // DATA (General)
    Route::get('index', [DataController::class,'index'])->name('index');

    // Dosen
    Route::prefix('dosen')->group(function () {
        Route::get('/', [DataController::class, 'dosen'])->name('dosen');
        Route::get('/create', [DataController::class, 'create4'])->name('dosenCreate');
        Route::get('/edit/{id_dosen}', [DataController::class, 'edit4'])->name('dosenEdit');
        Route::post('/update/{id_dosen}', [DataController::class, 'update4'])->name('dosenUpdate');
        Route::delete('/destroy/{id_dosen}', [DataController::class, 'destroy4'])->name('dosenDestroy');
        Route::post('/store', [DataController::class, 'store4'])->name('dosenStore');
        Route::post('/import', [DataController::class, 'importExcel4'])->name('dosenImport');
        Route::get('/excel', [DataController::class, 'excel4'])->name('dosenExport');
        Route::get('/pdf', [DataController::class, 'pdf4'])->name('dosenPdf');
        Route::get('/get-tempat/{id_kelompok}', [DataController::class, 'getTempat']);
    });

    // Mahasiswi
    Route::prefix('mahasiswi')->group(function () {
        Route::get('/', [DataController::class, 'mahasiswi'])->name('mahasiswi');
        Route::get('/create', [DataController::class, 'create3'])->name('mahasiswiCreate');
        Route::get('/edit/{id_mahasiswi}', [DataController::class, 'edit3'])->name('mahasiswiEdit');
        Route::post('/update/{id_mahasiswi}', [DataController::class, 'update3'])->name('mahasiswiUpdate');
        Route::delete('/destroy/{id_mahasiswi}', [DataController::class, 'destroy3'])->name('mahasiswiDestroy');
        Route::post('/store', [DataController::class, 'store3'])->name('mahasiswiStore');
        Route::post('/import', [DataController::class, 'importExcel3'])->name('mahasiswiImport');
        Route::get('/excel', [DataController::class, 'excel3'])->name('mahasiswiExport');
        Route::get('/pdf', [DataController::class, 'pdf3'])->name('mahasiswiPdf');
    });

    // Muhafidzoh
    Route::prefix('muhafidzoh')->group(function () {
        Route::get('/', [DataController::class, 'muhafidzoh'])->name('muhafidzoh');
        Route::get('/create', [DataController::class, 'create2'])->name('muhafidzohCreate');
        Route::get('/edit/{id_muhafidzoh}', [DataController::class, 'edit2'])->name('muhafidzohEdit');
        Route::post('/update/{id_muhafidzoh}', [DataController::class, 'update2'])->name('muhafidzohUpdate');
        Route::delete('/destroy/{id_muhafidzoh}', [DataController::class, 'destroy2'])->name('muhafidzohDestroy');
        Route::post('/store', [DataController::class, 'store2'])->name('muhafidzohStore');
        Route::post('/import', [DataController::class, 'importExcel2'])->name('muhafidzohImport');
        Route::get('/excel', [DataController::class, 'excel2'])->name('muhafidzohExport');
        Route::get('/pdf', [DataController::class, 'pdf2'])->name('muhafidzohPdf');
    });

    // Pengurus
    Route::prefix('pengurus')->group(function () {
        Route::get('/', [DataController::class, 'pengurus'])->name('pengurus');
        Route::get('/create', [DataController::class, 'create1'])->name('pengurusCreate');
        Route::get('/edit/{id}', [DataController::class, 'edit1'])->name('pengurusEdit');
        Route::post('/update/{id}', [DataController::class, 'update1'])->name('pengurusUpdate');
        Route::delete('/destroy/{id}', [DataController::class, 'destroy1'])->name('pengurusDestroy');
        Route::post('/store', [DataController::class, 'store1'])->name('pengurusStore');
        Route::post('/import', [DataController::class, 'importExcel1'])->name('pengurusImport');
        Route::get('/excel', [DataController::class, 'excel1'])->name('pengurusExport');
        Route::get('/pdf', [DataController::class, 'pdf1'])->name('pengurusPdf');
    });

    // --- ABSENSI ---

    // Absensi Anggota
    Route::get('/absensi/anggota/tahfidz/mahasiswi',[AbsensiAnggotaController::class, 'absensiTahfidzMahasiswi'])->name('absensiTahfidzMahasiswi');
    Route::get('absensi/anggota/tahfidz/muhafidzoh', [AbsensiAnggotaController::class,'absensiTahfidzMuhafidzoh'])->name('absensiTahfidzMuhafidzoh');

    // Absensi Umum
    Route::get('/absensi', [AbsensiController::class, 'index'])->name('absensi.index');
    Route::post('/absensi/simpan', [AbsensiController::class, 'simpan'])->name('absensi.simpan');
    Route::post('/absensi/push-pertemuan', [AbsensiController::class, 'pushPertemuan'])->name('absensi.push');
    Route::post('/absensi/refresh', [AbsensiController::class, 'refresh'])->name('absensi.refresh');
    Route::post('/absensi/export', [AbsensiController::class, 'export'])->name('absensi.export');

    // Absensi Muhafidzoh (Group)
    Route::prefix('absensi-muhafidzoh')->name('absensi_muhafidzoh.')->group(function () {
        Route::get('/', [AbsensiMuhafidzohController::class, 'index'])->name('index');
        Route::post('/simpan', [AbsensiMuhafidzohController::class, 'simpan'])->name('simpan');
        Route::post('/push', [AbsensiMuhafidzohController::class, 'pushPertemuan'])->name('push');
        Route::post('/refresh', [AbsensiMuhafidzohController::class, 'refresh'])->name('refresh');
        Route::post('/export', [AbsensiMuhafidzohController::class, 'export'])->name('export');
    });

    // Tilawah Mahasiswi
    Route::get('/tilawah-mahasiswi', [TilawahMahasiswiController::class, 'index'])->name('absensiTilawahMahasiswi');
    Route::post('/tilawah-mahasiswi/simpan', [TilawahMahasiswiController::class, 'simpan'])->name('tilawah.simpan');
    Route::post('/tilawah-mahasiswi/simpan-semua', [TilawahMahasiswiController::class, 'simpanSemua'])->name('tilawah.simpanSemua');
    Route::post('/tilawah-mahasiswi/export', [TilawahMahasiswiController::class, 'exportDocx'])->name('tilawah.export');

    // Lain-lain (Absensi Anggota Tilawah)
    Route::get('absensi/anggota/tilawah/muhafidzoh', [AbsensiAnggotaController::class,'absensiTilawahMuhafidzoh'])->name('absensiTilawahMuhafidzoh');
    Route::get('absensi/anggota/tilawah/staf', [AbsensiAnggotaController::class,'absensiTilawahStaf'])->name('absensiTilawahStaf');
    Route::get('absensi/anggota/tilawah/dosen', [AbsensiAnggotaController::class,'absensiTilawahDosen'])->name('absensiTilawahDosen');

    // Laporan Kegiatan (Absensi Pengurus)
    Route::prefix('laporan-kegiatan')->group(function () {
        Route::get('/', [LaporanKegiatanController::class, 'index'])->name('laporan.index');
        Route::post('/store', [LaporanKegiatanController::class, 'store'])->name('laporan.store'); 
        Route::get('/{id}', [LaporanKegiatanController::class, 'show'])->name('laporan.show'); 
        Route::post('/update-absensi/{id}', [LaporanKegiatanController::class, 'updateAbsensi'])->name('laporan.update_absensi'); 
        Route::get('/export/{id}', [LaporanKegiatanController::class, 'export'])->name('laporan.export');
    });

    // Tilawah Pengurus
    Route::get('/pengurus/tilawah', [TilawahPengurusController::class, 'index'])->name('pengurusTilawah');
    Route::post('/pengurus/tilawah/simpan', [TilawahPengurusController::class, 'simpanSemua'])->name('pengurus.tilawah.simpan');
    Route::post('/pengurus/tilawah/export', [TilawahPengurusController::class, 'exportDocx'])->name('pengurus.tilawah.export');

    Route::get('/pengurus/taujihat', [AbsensiPengurusController::class,'pengurusTaujihat'])->name('pengurusTaujihat');


    // --- UJIAN ---

    // Ujian Index (Diganti namanya agar tidak bentrok dengan DataController)
    Route::get('ujian/index', [UjianController::class,'index'])->name('ujian.index');

    // Ujian Mandiri
    Route::prefix('mandiri')->group(function () {
        Route::get('/', [UjianController::class, 'mandiri'])->name('mandiri');
        Route::get('/create', [UjianController::class, 'create5'])->name('mandiriCreate');
        Route::get('/edit/{id_ujian_mandiri}', [UjianController::class, 'edit5'])->name('mandiriEdit');
        Route::post('/store', [UjianController::class, 'store5'])->name('mandiriStore');
        Route::put('/update/{id_ujian_mandiri}', [UjianController::class, 'update5'])->name('mandiriUpdate');
        Route::delete('/destroy/{id_ujian_mandiri}', [UjianController::class, 'destroy5'])->name('mandiriDestroy');
        Route::get('/excel', [UjianController::class, 'excel5'])->name('mandiriExport');
        Route::get('/pdf', [UjianController::class, 'pdf5'])->name('mandiriPdf');
    });

    // Ujian Remedial (Cleaned Up)
    Route::prefix('tahfidz/remedial')->group(function () {
        Route::get('/', [UjianController::class, 'remedial'])->name('remedial'); 
        Route::get('/export-excel', [UjianController::class, 'remedialExportExcel'])->name('remedialExportExcel');
        Route::get('/export-pdf', [UjianController::class, 'remedialExportPdf'])->name('remedialExportPdf');
        Route::get('/edit/{id}', [UjianController::class, 'remedialEdit'])->name('remedialEdit');
        Route::post('/update/{id}', [UjianController::class, 'remedialUpdate'])->name('remedialUpdate');
        Route::delete('/destroy/{id}', [UjianController::class, 'remedialDestroy'])->name('remedialDestroy');
        // Update inline sudah di dalam prefix, jadi path-nya cukup '/update-inline'
        Route::post('/update-inline', [UjianController::class, 'remedialUpdateInline'])->name('remedialUpdateInline');
    });

    // Ujian Tahsin
    Route::prefix('tahsin')->group(function () {
        Route::get('/', [UjianController::class, 'tahsin'])->name('tahsin');
        Route::get('/create', [UjianController::class, 'createTahsin'])->name('tahsinCreate');
        Route::post('/store', [UjianController::class, 'storeTahsin'])->name('tahsinStore');
        Route::get('/edit/{id_tahsin}', [UjianController::class, 'editTahsin'])->name('tahsinEdit');
        Route::post('/update/{id_tahsin}', [UjianController::class, 'updateTahsin'])->name('tahsinUpdate');
        Route::delete('/destroy/{id_tahsin}', [UjianController::class, 'destroyTahsin'])->name('tahsinDestroy');
        Route::get('/export-excel', [UjianController::class, 'exportExcel'])->name('tahsinExportExcel');
        Route::get('/export-pdf', [UjianController::class, 'exportPdf'])->name('tahsinExportPdf');
    });

    // Dokumentasi
    Route::get('dokumentasi', [DokumentasiController::class,'dokumentasi'])->name('dokumentasi');

});