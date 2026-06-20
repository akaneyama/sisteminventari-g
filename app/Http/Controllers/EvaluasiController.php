<?php

namespace App\Http\Controllers;

use App\Models\EvaluasiLaporan;
use App\Services\EvaluasiService;
use Illuminate\Http\Request;
use App\Exports\EvaluasiExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class EvaluasiController extends Controller
{
    protected $evaluasiService;

    public function __construct(EvaluasiService $evaluasiService)
    {
        $this->evaluasiService = $evaluasiService;
    }

    public function index(Request $request)
    {
        $filter = $request->only(['tahun', 'semester', 'status']);
        $evaluasis = $this->evaluasiService->getAll($filter);

        return view('laporan.evaluasi.index', compact('evaluasis', 'filter'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tahun_evaluasi' => 'required|string|max:255',
            'semester_evaluasi' => 'required|string|max:255',
            'catatan' => 'required|string',
        ]);

        $periode = $request->semester_evaluasi . ' ' . $request->tahun_evaluasi;

        $this->evaluasiService->create([
            'periode' => $periode,
            'catatan' => $request->catatan,
        ]);

        return back()->with('success', 'Evaluasi laporan berhasil dikirim ke Admin.');
    }

    public function markAsRead($id)
    {
        $this->evaluasiService->markAsRead($id);

        return back()->with('success', 'Catatan ditandai sebagai sudah dibaca.');
    }

    public function exportExcel(Request $request)
    {
        $filter = $request->only(['tahun', 'semester', 'status']);
        return Excel::download(new EvaluasiExport($filter), 'Data_Evaluasi_'.date('Ymd').'.xlsx');
    }

    public function exportPdf(Request $request)
    {
        $filter = $request->only(['tahun', 'semester', 'status']);
        $evaluasis = $this->evaluasiService->getAll($filter);

        $pdf = Pdf::loadView('laporan.evaluasi.pdf', compact('evaluasis', 'filter'))->setPaper('a4', 'landscape');
        return $pdf->download('Data_Evaluasi_'.date('Ymd').'.pdf');
    }
}
