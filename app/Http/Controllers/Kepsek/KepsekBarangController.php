<?php

namespace App\Http\Controllers\Kepsek;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\Mutasi;
use App\Services\KategoriService;
use App\Services\LokasiService;
use Illuminate\Http\Request;

class KepsekBarangController extends Controller
{
    public function index(Request $request)
    {
        $filter   = $request->only(['search', 'id_lokasi', 'id_kategori', 'kondisi']);
        $kategori = (new KategoriService)->getAll();
        $lokasi   = (new LokasiService)->getAll();

        $query = Barang::with(['kategori', 'lokasi', 'supplier', 'sumberDana']);
        if (!empty($filter['search'])) {
            $s = $filter['search'];
            $query->where(function ($q) use ($s) {
                $q->where('kode_inventaris', 'like', "%{$s}%")
                  ->orWhere('nama_barang', 'like', "%{$s}%")
                  ->orWhere('merk_type', 'like', "%{$s}%");
            });
        }
        if (!empty($filter['id_lokasi']))   $query->where('id_lokasi', $filter['id_lokasi']);
        if (!empty($filter['id_kategori'])) $query->where('id_kategori', $filter['id_kategori']);
        if (!empty($filter['kondisi']))     $query->where('kondisi', $filter['kondisi']);

        $barang = $query->latest()->get();

        return view('kepsek.barang.index', compact('barang', 'kategori', 'lokasi', 'filter'));
    }
}
