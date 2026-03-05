<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Lokasi;
use Illuminate\Http\Request;
use App\Exports\BarangExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        // Tangkap parameter filter
        $filter = $request->only(['id_lokasi', 'kondisi']);
        
        $query = Barang::with(['kategori', 'lokasi']);
        if (!empty($filter['id_lokasi'])) $query->where('id_lokasi', $filter['id_lokasi']);
        if (!empty($filter['kondisi'])) $query->where('kondisi', $filter['kondisi']);
        
        $barang = $query->latest()->get();
        $lokasi = Lokasi::all();

        return view('laporan.index', compact('barang', 'lokasi', 'filter'));
    }

    public function exportExcel(Request $request)
    {
        $filter = $request->only(['id_lokasi', 'kondisi']);
        return Excel::download(new BarangExport($filter), 'Laporan_Inventaris_'.date('Ymd').'.xlsx');
    }

    public function exportPdf(Request $request)
    {
        $filter = $request->only(['id_lokasi', 'kondisi']);
        $query = Barang::with(['kategori', 'lokasi']);
        if (!empty($filter['id_lokasi'])) $query->where('id_lokasi', $filter['id_lokasi']);
        if (!empty($filter['kondisi'])) $query->where('kondisi', $filter['kondisi']);
        
        $barang = $query->get();

        // Load view khusus PDF (tanpa Tailwind agar format DomPDF rapi)
        $pdf = Pdf::loadView('laporan.pdf', compact('barang'))->setPaper('a4', 'landscape');
        return $pdf->download('Laporan_Inventaris_'.date('Ymd').'.pdf');
    }

    // Fitur Cetak Label QR Code untuk 1 Barang
    public function printLabel($id)
    {
        $barang = Barang::findOrFail($id);
        return view('laporan.label', compact('barang'));
    }
}