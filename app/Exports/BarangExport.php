<?php

namespace App\Exports;

use App\Models\Barang;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class BarangExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    protected $filter;

    public function __construct($filter)
    {
        $this->filter = $filter;
    }

    public function collection()
    {
        // Query dengan filter jika ada
        $query = Barang::with(['kategori', 'lokasi']);
        
        if (!empty($this->filter['id_lokasi'])) {
            $query->where('id_lokasi', $this->filter['id_lokasi']);
        }
        if (!empty($this->filter['kondisi'])) {
            $query->where('kondisi', $this->filter['kondisi']);
        }

        return $query->get();
    }

    public function headings(): array
    {
        return ['No', 'Kode Inventaris', 'Nama Barang', 'Merk/Tipe', 'Kategori', 'Ruangan', 'Kondisi', 'Tahun Perolehan', 'Sumber Dana'];
    }

    public function map($barang): array
    {
        static $no = 0;
        $no++;
        return [
            $no,
            $barang->kode_inventaris,
            $barang->nama_barang,
            $barang->merk_type,
            $barang->kategori->nama_kategori ?? '-',
            $barang->lokasi->nama_ruangan ?? '-',
            $barang->kondisi,
            $barang->tahun_perolehan,
            $barang->sumber_dana,
        ];
    }
}