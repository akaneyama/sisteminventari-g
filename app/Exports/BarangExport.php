<?php

namespace App\Exports;

use App\Models\Barang;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class BarangExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles, WithTitle
{
    protected $filter;

    public function __construct($filter)
    {
        $this->filter = $filter;
    }

    public function title(): string
    {
        return 'Laporan Inventaris ' . date('d-m-Y');
    }

    public function collection()
    {
        $query = Barang::with(['kategori', 'lokasi', 'sumberDana', 'supplier']);

        if (!empty($this->filter['id_lokasi']))     $query->where('id_lokasi', $this->filter['id_lokasi']);
        if (!empty($this->filter['kondisi']))        $query->where('kondisi', $this->filter['kondisi']);
        if (!empty($this->filter['id_kategori']))    $query->where('id_kategori', $this->filter['id_kategori']);
        if (!empty($this->filter['id_sumber_dana'])) $query->where('id_sumber_dana_new', $this->filter['id_sumber_dana']);
        if (!empty($this->filter['tahun_perolehan'])) $query->where('tahun_perolehan', $this->filter['tahun_perolehan']);

        return $query->latest()->get();
    }

    public function headings(): array
    {
        return [
            'No',
            'Kode Inventaris',
            'Nama Barang',
            'Merk / Tipe',
            'Kategori',
            'Ruangan / Lokasi',
            'Jumlah (Unit)',
            'Kondisi',
            'Supplier',
            'Sumber Dana',
            'Tahun Dana',
            'Tahun Perolehan',
        ];
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
            $barang->jumlah_barang,
            $barang->kondisi,
            $barang->supplier->nama_supplier ?? '-',
            $barang->sumberDana->nama_sumber_dana ?? '-',
            $barang->sumberDana->tahun ?? '-',
            $barang->tahun_perolehan ?? '-',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Style header row
        $sheet->getStyle('A1:L1')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '1E40AF'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical'   => Alignment::VERTICAL_CENTER,
            ],
        ]);

        // Center-align jumlah column
        $sheet->getStyle('G')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        return [];
    }
}