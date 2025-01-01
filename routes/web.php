<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BeritaDesaController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PotensiDesaController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('/', [AuthController::class, 'index'])->name('login');
    Route::post('/', [AuthController::class, 'login'])->name('login-post');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/list-potensi-desa', [PotensiDesaController::class, 'index'])->name('list-potensi-desa');
    Route::get('/tambah-potensi-desa', [PotensiDesaController::class, 'create'])->name('tambah-potensi-desa');
    Route::post('/tambah-potensi-desa', [PotensiDesaController::class, 'store'])->name('add-potensi-desa');
    Route::get('/edit-potensi-desa/{uuid}', [PotensiDesaController::class, 'show'])->name('edit-potensi-desa');
    Route::post('/edit-potensi-desa/{uuid}', [PotensiDesaController::class, 'update'])->name('update-potensi-desa');
    Route::delete('/delete-potensi-desa/{uuid}', [PotensiDesaController::class, 'destroy'])->name('delete-potensi-desa');
    Route::get('/preview-potensi-desa/{uuid}', [PotensiDesaController::class, 'preview'])->name('preview-potensi-desa');

    Route::get('/list-berita-desa', [BeritaDesaController::class, 'index'])->name('list-berita-desa');
    Route::get('/tambah-berita-desa', [BeritaDesaController::class, 'create'])->name('tambah-berita-desa');
    Route::post('/tambah-berita-desa', [BeritaDesaController::class, 'store'])->name('add-berita-desa');
    Route::get('/edit-berita-desa/{uuid}', [BeritaDesaController::class, 'show'])->name('edit-berita-desa');
    Route::post('/edit-berita-desa/{uuid}', [BeritaDesaController::class, 'update'])->name('update-berita-desa');
    Route::delete('/delete-berita-desa/{uuid}', [BeritaDesaController::class, 'destroy'])->name('delete-berita-desa');
    Route::get('/preview-berita-desa/{uuid}', [BeritaDesaController::class, 'preview'])->name('preview-berita-desa');

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});