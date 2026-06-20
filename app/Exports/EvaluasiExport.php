<?php

namespace App\Exports;

use App\Services\EvaluasiService;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class EvaluasiExport implements FromCollection, WithHeadings, WithMapping
{
    protected $filter;
    protected $evaluasiService;

    public function __construct(array $filter = [])
    {
        $this->filter = $filter;
        $this->evaluasiService = new EvaluasiService();
    }

    public function collection()
    {
        return $this->evaluasiService->getAll($this->filter);
    }

    public function headings(): array
    {
        return [
            'No',
            'Periode Evaluasi',
            'Catatan/Instruksi',
            'Status',
            'Tanggal Dikirim'
        ];
    }

    public function map($evaluasi): array
    {
        static $row = 0;
        $row++;
        
        return [
            $row,
            $evaluasi->periode,
            $evaluasi->catatan,
            $evaluasi->status,
            $evaluasi->created_at->format('Y-m-d H:i:s'),
        ];
    }
}
