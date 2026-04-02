<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function admin()
    {
        // Hitung statistik untuk kartu dashboard
        $total_aset = Barang::count();
        $aset_baik = Barang::where('kondisi', 'Baik')->count();
        $aset_rusak = Barang::whereIn('kondisi', ['Rusak Ringan', 'Rusak Berat'])->count();

        // Data untuk Pie Chart Kategori
        $kategoriData = Barang::join('kategori', 'barang.id_kategori', '=', 'kategori.id_kategori')
            ->select('kategori.nama_kategori', DB::raw('count(barang.id_barang) as total'))
            ->groupBy('kategori.nama_kategori')
            ->get();

        // Data untuk Bar Chart Kondisi
        $kondisiData = Barang::select('kondisi', DB::raw('count(id_barang) as total'))
            ->groupBy('kondisi')
            ->get();

        return view('admin.dashboard', compact('total_aset', 'aset_baik', 'aset_rusak', 'kategoriData', 'kondisiData'));
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