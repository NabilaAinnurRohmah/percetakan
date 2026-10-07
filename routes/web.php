<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PesananController;
use App\Http\Controllers\PelangganController;
use App\Http\Controllers\AuthPegawaiController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/pegawai/login', [
    AuthPegawaiController::class, 'showLogin'
])->name('pegawai.login');
Route::post('/pegawai/login', [
    AuthPegawaiController::class, 'login'
])->name('pegawai.login.proses');
Route::get('/pegawai/logout', [
    AuthPegawaiController::class, 'logout'
])->name('pegawai.logout');



Route::get('/pegawai/pesanan', [
    PesananController::class, 'index'
])->name('pesanan.index');
Route::get('/pegawai/pesanan/create', [
    PesananController::class, 'create'
])->name('pesanan.create');
Route::post('/pegawai/pesanan', [
    PesananController::class, 'store'
])->name('pesanan.store');
Route::get('/pegawai/pesanan/{id}', [
    PesananController::class, 'show'
])->name('pesanan.show');
Route::get('/pegawai/pesanan/{id}/edit', [
    PesananController::class, 'edit'
])->name('pesanan.edit');
Route::put('/pegawai/pesanan/{id}', [
    PesananController::class, 'update'
])->name('pesanan.update');
Route::delete('/pegawai/pesanan/{id}', [
    PesananController::class, 'destroy'
])->name('pesanan.destroy');
Route::put('/pegawai/pesanan/{id}/status', [
    PesananController::class, 'updateStatus'
])->name('pesanan.status');


Route::get('/cek-status', [
    PelangganController::class, 'index'
])->name('pelanggan.cek-status');
Route::post('/cek-status', [
    PelangganController::class, 'cek'
])->name('pelanggan.cek-status.proses');
