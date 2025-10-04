<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\pengurusController;
use App\Http\Controllers\DashboardController;

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


//halamanpengurus
Route::get('pengurus', [pengurusController::class,'pengurus'])->name('pengurus');

});

