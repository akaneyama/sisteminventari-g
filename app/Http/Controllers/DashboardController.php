<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function admin()
    {
        // Hitung statistik untuk kartu dashboard
        $total_aset = Barang::count();
        $aset_baik = Barang::where('kondisi', 'Baik')->count();
        $aset_rusak = Barang::whereIn('kondisi', ['Rusak Ringan', 'Rusak Berat'])->count();

        return view('admin.dashboard', compact('total_aset', 'aset_baik', 'aset_rusak'));
    }

    public function kepsek()
    {
        // Kepsek juga melihat data statistik yang sama
        $total_aset = Barang::count();
        $aset_baik = Barang::where('kondisi', 'Baik')->count();
        $aset_rusak = Barang::whereIn('kondisi', ['Rusak Ringan', 'Rusak Berat'])->count();

        return view('kepsek.dashboard', compact('total_aset', 'aset_baik', 'aset_rusak'));
    }
}