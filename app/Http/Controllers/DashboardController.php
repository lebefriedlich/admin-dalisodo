<?php

namespace App\Http\Controllers;

use App\Models\BeritaModel;
use App\Models\PotensiModel;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $potensiDesa = PotensiModel::count();
        $beritaDesa = BeritaModel::count();

        return view('Admin.Dashboard.index', [
            'potensiDesa' => $potensiDesa,
            'beritaDesa' => $beritaDesa
        ]);
    }
}
