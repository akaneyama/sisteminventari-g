<?php

namespace App\Services;

use App\Models\EvaluasiLaporan;

class EvaluasiService
{
    public function getAll($filter = [])
    {
        $query = EvaluasiLaporan::query();

        if (!empty($filter['tahun']) && !empty($filter['semester'])) {
            $periode = $filter['semester'] . ' ' . $filter['tahun'];
            $query->where('periode', 'like', '%' . $periode . '%');
        } elseif (!empty($filter['tahun'])) {
            $query->where('periode', 'like', '%' . $filter['tahun'] . '%');
        } elseif (!empty($filter['semester'])) {
            $query->where('periode', 'like', '%' . $filter['semester'] . '%');
        }

        if (isset($filter['status']) && $filter['status'] != '') {
            $query->where('status', $filter['status']);
        }

        return $query->latest()->paginate(10);
    }

    public function create(array $data)
    {
        return EvaluasiLaporan::create($data);
    }

    public function markAsRead($id)
    {
        $evaluasi = EvaluasiLaporan::findOrFail($id);
        $evaluasi->update(['status' => 'Sudah Dibaca']);
        return $evaluasi;
    }
}
