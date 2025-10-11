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

Route::get('/', function () {
    return view('welcome');
});


//login
Route::get('login', [LoginController::class,'login'])->name('login');

Route::post('login', [LoginController::class,'loginProses'])->name('loginProses');

//Logout
Route::get('logout', [LoginController::class,'logout'])->name('logout');

route::middleware('checkLogin')->group(function(){

//dashboard
Route::get('dashboard', [DashboardController::class,'index'])->name('dashboard');


//user
Route::get('user', [UserController::class,'index'])->name('user');
Route::get('user/create', [UserController::class,'create'])->name('userCreate');
Route::get('/user/edit/{id}', [UserController::class, 'edit'])->name('userEdit');
Route::post('/user/update/{id}', [UserController::class, 'update'])->name('userUpdate');
Route::delete('/user/destroy/{id}', [UserController::class, 'destroy'])->name('userDestroy');
Route::post('user/store', [UserController::class,'store'])->name('userStore');
Route::post('/user/import', [UserController::class, 'importExcel'])->name('userImport');
Route::get('/user/excel', [UserController::class, 'excel'])->name('userExport');
Route::get('/admin/user/pdf', [UserController::class, 'pdf'])->name('userPdf');




//DATA
Route::get('index', [DataController::class,'index'])->name('index');


//dosen
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
    Route::get('/get-tempat/{id_kelompok}', [App\Http\Controllers\DataController::class, 'getTempat']);

});

//mahasiswi
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


//muhafidzoh
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


//pengurus
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






//absensi Anggota

Route::get('absensi/anggota/tahfidz/mahasiswi', [AbsensiAnggotaController::class,'absensiTahfidzMahasiswi'])->name('absensiTahfidzMahasiswi');

Route::get('absensi/anggota/tahfidz/muhafidzoh', [AbsensiAnggotaController::class,'absensiTahfidzMuhafidzoh'])->name('absensiTahfidzMuhafidzoh');


//tahsin
Route::get('absensi/anggota/tilawah/mahasiswi', [AbsensiAnggotaController::class,'absensiTilawahMahasiswi'])->name('absensiTilawahMahasiswi');

Route::get('absensi/anggota/tilawah/muhafidzoh', [AbsensiAnggotaController::class,'absensiTilawahMuhafidzoh'])->name('absensiTilawahMuhafidzoh');

Route::get('absensi/anggota/tilawah/staf', [AbsensiAnggotaController::class,'absensiTilawahStaf'])->name('absensiTilawahStaf');

Route::get('absensi/anggota/tilawah/dosen', [AbsensiAnggotaController::class,'absensiTilawahDosen'])->name('absensiTilawahDosen');



//absensi Pengurus


Route::get('/pengurus/lailatu', [AbsensiPengurusController::class,'pengurusLailatu'])->name('pengurusLailatu');

Route::get('/pengurus/tilawah', [AbsensiPengurusController::class,'pengurusTilawah'])->name('pengurusTilawah');

Route::get('/pengurus/taujihat', [AbsensiPengurusController::class,'pengurusTaujihat'])->name('pengurusTaujihat');


//Ujian
Route::get('index', [UjianController::class,'index'])->name('index');

Route::get('/tahfidz/mandiri', [UjianController::class,'mandiri'])->name('mandiri');

Route::get('/tahfidz/serentak', [UjianController::class,'serentak'])->name('serentak');

Route::get('/tahfidz/remedial', [UjianController::class,'remedial'])->name('remedial');

Route::get('/tahsin/tahsin', [UjianController::class,'tahsin'])->name('tahsin');


//dokumentasi

Route::get('dokumentasi', [DokumentasiController::class,'dokumentasi'])->name('dokumentasi');



});



