<?php

namespace App\Http\Controllers\Kepsek;

use App\Http\Controllers\Controller;
use App\Models\Mutasi;
use Illuminate\Http\Request;

class KepsekMutasiController extends Controller
{
    public function index(Request $request)
    {
        $filter = $request->only(['search', 'jenis_mutasi', 'tanggal_dari', 'tanggal_sampai']);

        $query = Mutasi::with(['barang', 'user', 'lokasiAsal', 'lokasiTujuan']);

        if (!empty($filter['search'])) {
            $s = $filter['search'];
            $query->whereHas('barang', function ($q) use ($s) {
                $q->where('kode_inventaris', 'like', "%{$s}%")
                  ->orWhere('nama_barang', 'like', "%{$s}%");
            });
        }
        if (!empty($filter['jenis_mutasi']))   $query->where('jenis_mutasi', $filter['jenis_mutasi']);
        if (!empty($filter['tanggal_dari']))   $query->whereDate('tanggal_mutasi', '>=', $filter['tanggal_dari']);
        if (!empty($filter['tanggal_sampai'])) $query->whereDate('tanggal_mutasi', '<=', $filter['tanggal_sampai']);

        $mutasi = $query->orderBy('tanggal_mutasi', 'desc')->get();

        return view('kepsek.mutasi.index', compact('mutasi', 'filter'));
    }
}
