<?php

namespace App\Services;

use App\Models\Barang;

class LaporanService
{
    public function getBarangLaporan($filter = [])
    {
        $query = Barang::with(['kategori', 'lokasi', 'sumberDana', 'supplier']);

        if (!empty($filter['id_lokasi'])) {
            $query->where('id_lokasi', $filter['id_lokasi']);
        }
        if (!empty($filter['kondisi'])) {
            $query->where('kondisi', $filter['kondisi']);
        }
        if (!empty($filter['id_kategori'])) {
            $query->where('id_kategori', $filter['id_kategori']);
        }
        if (!empty($filter['id_sumber_dana'])) {
            $query->where('id_sumber_dana_new', $filter['id_sumber_dana']);
        }
        
        // Filter by Tahun and Semester based on created_at
        if (!empty($filter['tahun'])) {
            $query->whereYear('created_at', $filter['tahun']);
        }
        
        if (!empty($filter['semester'])) {
            if ($filter['semester'] == 'Genap') {
                $query->whereMonth('created_at', '>=', 1)->whereMonth('created_at', '<=', 6);
            } elseif ($filter['semester'] == 'Ganjil') {
                $query->whereMonth('created_at', '>=', 7)->whereMonth('created_at', '<=', 12);
            }
        }

        return $query; // Returning query builder so caller can append ->latest()->get() or ->get()
    }
    
    public function getStats($barangCollection)
    {
        return [
            'total_jenis'   => $barangCollection->count(),
            'total_unit'    => $barangCollection->sum('jumlah_barang'),
            'kondisi_baik'  => $barangCollection->where('kondisi', 'Baik')->sum('jumlah_barang'),
            'rusak_ringan'  => $barangCollection->where('kondisi', 'Rusak Ringan')->sum('jumlah_barang'),
            'rusak_berat'   => $barangCollection->where('kondisi', 'Rusak Berat')->sum('jumlah_barang'),
        ];
    }
}
