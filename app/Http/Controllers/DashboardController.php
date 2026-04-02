<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Mutasi;
use App\Models\Kategori;
use App\Models\Lokasi;
use App\Models\Supplier;
use App\Models\SumberDana;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function admin()
    {
        // ── Kartu Statistik Utama ──────────────────────────────────────────
        $total_jenis  = Barang::count();
        $total_unit   = Barang::sum('jumlah_barang') ?: 0;
        $aset_baik    = Barang::where('kondisi', 'Baik')->sum('jumlah_barang') ?: 0;
        $rusak_ringan = Barang::where('kondisi', 'Rusak Ringan')->sum('jumlah_barang') ?: 0;
        $rusak_berat  = Barang::where('kondisi', 'Rusak Berat')->sum('jumlah_barang') ?: 0;

        // ── Data Master ────────────────────────────────────────────────────
        $total_kategori   = Kategori::count();
        $total_lokasi     = Lokasi::count();
        $total_supplier   = Supplier::count();
        $total_sumberdana = SumberDana::count();

        // ── Mutasi Bulan Ini ───────────────────────────────────────────────
        $mutasi_bulan_ini = Mutasi::whereMonth('tanggal_mutasi', now()->month)
                                  ->whereYear('tanggal_mutasi', now()->year)
                                  ->count();

        $mutasi_terbaru = Mutasi::with(['barang', 'lokasiAsal', 'lokasiTujuan', 'user'])
                                ->orderBy('tanggal_mutasi', 'desc')
                                ->limit(5)
                                ->get();

        // ── Chart: Barang per Kategori (unit) ─────────────────────────────
        $kategoriData = Barang::join('kategori', 'barang.id_kategori', '=', 'kategori.id_kategori')
            ->select('kategori.nama_kategori', DB::raw('SUM(barang.jumlah_barang) as total'))
            ->groupBy('kategori.nama_kategori')
            ->orderByDesc('total')
            ->limit(8)
            ->get();

        // ── Chart: Kondisi Barang ──────────────────────────────────────────
        $kondisiData = Barang::select('kondisi', DB::raw('SUM(jumlah_barang) as total'))
            ->groupBy('kondisi')
            ->get();

        // ── Chart: Mutasi 6 Bulan Terakhir ────────────────────────────────
        $mutasiTrend = Mutasi::select(
                DB::raw("strftime('%Y-%m', tanggal_mutasi) as bulan"),
                DB::raw('count(*) as total')
            )
            ->where('tanggal_mutasi', '>=', now()->subMonths(5)->startOfMonth())
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->get();

        // Isi bulan yang kosong dengan 0
        $mutasiLabels = [];
        $mutasiValues = [];
        for ($i = 5; $i >= 0; $i--) {
            $bulan = now()->subMonths($i)->format('Y-m');
            $mutasiLabels[] = now()->subMonths($i)->translatedFormat('M Y');
            $found = $mutasiTrend->firstWhere('bulan', $bulan);
            $mutasiValues[] = $found ? $found->total : 0;
        }

        // ── Top 5 Lokasi Terbanyak ─────────────────────────────────────────
        $topLokasi = Barang::join('lokasi', 'barang.id_lokasi', '=', 'lokasi.id_lokasi')
            ->select('lokasi.nama_ruangan', DB::raw('SUM(barang.jumlah_barang) as total_unit'))
            ->groupBy('lokasi.nama_ruangan')
            ->orderByDesc('total_unit')
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact(
            'total_jenis', 'total_unit', 'aset_baik', 'rusak_ringan', 'rusak_berat',
            'total_kategori', 'total_lokasi', 'total_supplier', 'total_sumberdana',
            'mutasi_bulan_ini', 'mutasi_terbaru',
            'kategoriData', 'kondisiData',
            'mutasiLabels', 'mutasiValues',
            'topLokasi'
        ));
    }

    public function kepsek()
    {
        $total_jenis  = Barang::count();
        $total_unit   = Barang::sum('jumlah_barang') ?: 0;
        $aset_baik    = Barang::where('kondisi', 'Baik')->sum('jumlah_barang') ?: 0;
        $aset_rusak   = Barang::whereIn('kondisi', ['Rusak Ringan', 'Rusak Berat'])->sum('jumlah_barang') ?: 0;

        $kondisiData = Barang::select('kondisi', DB::raw('SUM(jumlah_barang) as total'))
            ->groupBy('kondisi')
            ->get();

        $kategoriData = Barang::join('kategori', 'barang.id_kategori', '=', 'kategori.id_kategori')
            ->select('kategori.nama_kategori', DB::raw('SUM(barang.jumlah_barang) as total'))
            ->groupBy('kategori.nama_kategori')
            ->orderByDesc('total')
            ->limit(8)
            ->get();

        return view('kepsek.dashboard', compact(
            'total_jenis', 'total_unit', 'aset_baik', 'aset_rusak', 'kondisiData', 'kategoriData'
        ));
    }
}