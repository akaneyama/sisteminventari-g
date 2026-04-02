<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Lokasi;
use App\Models\Kategori;
use App\Models\SumberDana;
use Illuminate\Http\Request;
use App\Exports\BarangExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $filter = $request->only(['id_lokasi', 'kondisi', 'id_kategori', 'id_sumber_dana', 'tahun_perolehan']);

        $query = Barang::with(['kategori', 'lokasi', 'sumberDana', 'supplier']);
        if (!empty($filter['id_lokasi']))      $query->where('id_lokasi', $filter['id_lokasi']);
        if (!empty($filter['kondisi']))         $query->where('kondisi', $filter['kondisi']);
        if (!empty($filter['id_kategori']))     $query->where('id_kategori', $filter['id_kategori']);
        if (!empty($filter['id_sumber_dana']))  $query->where('id_sumber_dana_new', $filter['id_sumber_dana']);
        if (!empty($filter['tahun_perolehan'])) $query->where('tahun_perolehan', $filter['tahun_perolehan']);

        $barang = $query->latest()->get();

        // Statistik ringkasan
        $stats = [
            'total_jenis'   => $barang->count(),
            'total_unit'    => $barang->sum('jumlah_barang'),
            'kondisi_baik'  => $barang->where('kondisi', 'Baik')->sum('jumlah_barang'),
            'rusak_ringan'  => $barang->where('kondisi', 'Rusak Ringan')->sum('jumlah_barang'),
            'rusak_berat'   => $barang->where('kondisi', 'Rusak Berat')->sum('jumlah_barang'),
        ];

        $lokasi     = Lokasi::all();
        $kategori   = Kategori::all();
        $sumberDana = SumberDana::all();

        return view('laporan.index', compact('barang', 'lokasi', 'kategori', 'sumberDana', 'filter', 'stats'));
    }

    public function exportExcel(Request $request)
    {
        $filter = $request->only(['id_lokasi', 'kondisi', 'id_kategori', 'id_sumber_dana']);
        return Excel::download(new BarangExport($filter), 'Laporan_Inventaris_'.date('Ymd').'.xlsx');
    }

    public function exportPdf(Request $request)
    {
        $filter = $request->only(['id_lokasi', 'kondisi', 'id_kategori', 'id_sumber_dana', 'tahun_perolehan']);
        $query  = Barang::with(['kategori', 'lokasi', 'sumberDana', 'supplier']);
        if (!empty($filter['id_lokasi']))      $query->where('id_lokasi', $filter['id_lokasi']);
        if (!empty($filter['kondisi']))         $query->where('kondisi', $filter['kondisi']);
        if (!empty($filter['id_kategori']))     $query->where('id_kategori', $filter['id_kategori']);
        if (!empty($filter['id_sumber_dana']))  $query->where('id_sumber_dana_new', $filter['id_sumber_dana']);
        if (!empty($filter['tahun_perolehan'])) $query->where('tahun_perolehan', $filter['tahun_perolehan']);

        $barang = $query->get();

        $stats = [
            'total_jenis'   => $barang->count(),
            'total_unit'    => $barang->sum('jumlah_barang'),
            'kondisi_baik'  => $barang->where('kondisi', 'Baik')->sum('jumlah_barang'),
            'rusak_ringan'  => $barang->where('kondisi', 'Rusak Ringan')->sum('jumlah_barang'),
            'rusak_berat'   => $barang->where('kondisi', 'Rusak Berat')->sum('jumlah_barang'),
        ];

        $pdf = Pdf::loadView('laporan.pdf', compact('barang', 'stats', 'filter'))->setPaper('a4', 'landscape');
        return $pdf->download('Laporan_Inventaris_'.date('Ymd').'.pdf');
    }

    // Cetak Label QR Code untuk 1 Barang
    public function printLabel($id)
    {
        $barang = Barang::with(['kategori', 'lokasi', 'supplier'])->findOrFail($id);
        return view('laporan.label', compact('barang'));
    }

    // Cetak Label QR Code Batch (banyak barang)
    public function printLabelBatch(Request $request)
    {
        $ids = $request->input('ids', []);
        if (empty($ids)) {
            return back()->with('error', 'Pilih minimal 1 barang untuk dicetak labelnya.');
        }
        $barangs = Barang::with(['kategori', 'lokasi'])->whereIn('id_barang', $ids)->get();
        return view('laporan.label_batch', compact('barangs'));
    }
}