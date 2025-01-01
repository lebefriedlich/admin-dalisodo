<?php

use App\Http\Controllers\API\PotensiBerita;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::group(['middleware' => 'secret-key'], function () {
    Route::get('/data-desa', [PotensiBerita::class, 'index']);
    Route::get('/detail/{uuid}/{slug}/{profil}', [PotensiBerita::class, 'detail']);
});
