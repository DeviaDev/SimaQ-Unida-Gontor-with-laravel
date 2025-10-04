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

Route::post('user/store', [UserController::class,'store'])->name('userStore');



//DATA
Route::get('index', [DataController::class,'index'])->name('index');

Route::get('pengurus', [DataController::class,'pengurus'])->name('pengurus');

Route::get('mahasiswi', [DataController::class,'mahasiswi'])->name('mahasiswi');

Route::get('muhafidzoh', [DataController::class,'muhafidzoh'])->name('muhafidzoh');

Route::get('dosen', [DataController::class,'dosen'])->name('dosen');


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



