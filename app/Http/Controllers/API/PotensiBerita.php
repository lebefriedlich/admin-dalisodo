<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\BeritaModel;
use App\Models\PotensiModel;

class PotensiBerita extends Controller
{
    public function index()
    {
        $data_berita = BeritaModel::with('media')->orderBy('tanggal', 'desc')->get();
        $data_potensi = PotensiModel::with('media')->orderBy('created_at', 'desc')->get();

        return response()->json([
            'status_code' => 200,
            'message' => 'Data Berhasil Diambil',
            'data_berita' => $data_berita,
            'data_potensi' => $data_potensi
        ], 200);
    }
    
    public function detail($profil, $slug){
        if ($profil == 'potensi'){
            $data = PotensiModel::with('media')->where('slug', $slug)->first();

            if ($data){
                return response()->json([
                    'status_code' => 200,
                    'message' => 'Data Berhasil Diambil',
                    'type' => 'potensi',
                    'data' => $data
                ], 200);
            } else {
                return response()->json([
                    'status_code' => 404,
                    'message' => 'Data Tidak Ditemukan'
                ], 404);
            }
        } else if ($profil == 'berita'){
            $data = BeritaModel::with('media')->where('slug', $slug)->first();

            if ($data){
                return response()->json([
                    'status_code' => 200,
                    'message' => 'Data Berhasil Diambil',
                    'type' => 'berita',
                    'data' => $data
                ], 200);
            } else {
                return response()->json([
                    'status_code' => 404,
                    'message' => 'Data Tidak Ditemukan'
                ], 404);
            }
        } else {
            return response()->json([
                'status_code' => 404,
                'message' => 'Profil Tidak Ditemukan'
            ], 404);
        }
    }
}
