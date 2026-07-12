<?php

namespace App\Http\Controllers\Kepsek;

use App\Http\Controllers\Controller;
use App\Services\BarangService;
use App\Services\MutasiService;
use Illuminate\Http\Request;

class ApprovalController extends Controller
{
    protected $barangService;
    protected $mutasiService;

    public function __construct(BarangService $barangService, MutasiService $mutasiService)
    {
        $this->barangService = $barangService;
        $this->mutasiService = $mutasiService;
    }

    public function index()
    {
        $pendingBarang = $this->barangService->getPendingApproval();
        return view('kepsek.approval.index', compact('pendingBarang'));
    }

    public function approve($id)
    {
        $this->barangService->approveDelete($id);
        return back()->with('success', 'Penghapusan aset disetujui.');
    }

    public function reject($id)
    {
        $this->barangService->rejectDelete($id);
        return back()->with('success', 'Penghapusan aset ditolak.');
    }

    public function pengadaan()
    {
        $pengadaans = $this->barangService->getPendingPengadaan();
        return view('kepsek.approval.pengadaan', compact('pengadaans'));
    }

    public function approvePengadaan(Request $request, $id)
    {
        $request->validate([
            'jumlah_disetujui' => 'required|integer|min:1',
            'alasan_penolakan' => 'nullable|string|max:500'
        ]);

        $this->barangService->approvePengadaan($id, $request->jumlah_disetujui, $request->alasan_penolakan);
        return redirect()->route('kepsek.approval.pengadaan')->with('success', 'Pengajuan pengadaan berhasil disetujui. Barang telah dimasukkan ke dalam sistem.');
    }

    public function rejectPengadaan(Request $request, $id)
    {
        $request->validate(['alasan_penolakan' => 'required|string|max:500']);
        $this->barangService->rejectPengadaan($id, $request->alasan_penolakan);
        return redirect()->route('kepsek.approval.pengadaan')->with('success', 'Pengajuan pengadaan telah ditolak.');
    }

    // ─── Persetujuan Perubahan Data Barang ───────────────────────────────────
    public function perubahan()
    {
        $perubahans = $this->barangService->getPendingPerubahan();
        return view('kepsek.approval.perubahan', compact('perubahans'));
    }

    public function approvePerubahan($id)
    {
        $this->barangService->approvePerubahan($id);
        return redirect()->route('kepsek.approval.perubahan')->with('success', 'Perubahan data barang telah disetujui dan diterapkan.');
    }

    public function rejectPerubahan(Request $request, $id)
    {
        $request->validate(['alasan_penolakan' => 'required|string|max:500']);
        $this->barangService->rejectPerubahan($id, $request->alasan_penolakan);
        return redirect()->route('kepsek.approval.perubahan')->with('success', 'Perubahan data barang telah ditolak.');
    }

    // ─── Persetujuan Mutasi ───────────────────────────────────────────────────
    public function mutasi()
    {
        $mutasis = $this->mutasiService->getPendingMutasi();
        return view('kepsek.approval.mutasi', compact('mutasis'));
    }

    public function approveMutasi($id)
    {
        $this->mutasiService->approveMutasi($id);
        return redirect()->route('kepsek.approval.mutasi')->with('success', 'Mutasi barang telah disetujui dan diterapkan.');
    }

    public function rejectMutasi(Request $request, $id)
    {
        $request->validate(['alasan_penolakan' => 'required|string|max:500']);
        $this->mutasiService->rejectMutasi($id, $request->alasan_penolakan);
        return redirect()->route('kepsek.approval.mutasi')->with('success', 'Mutasi barang telah ditolak.');
    }

    public function getNotificationCounts()
    {
        $counts = [
            'pengadaan' => $this->barangService->getPendingPengadaan()->count(),
            'perubahan' => $this->barangService->getPendingPerubahan()->count(),
            'mutasi' => $this->mutasiService->getPendingMutasi()->count(),
            'penghapusan' => $this->barangService->getPendingApproval()->count(),
        ];
        
        $counts['total'] = array_sum($counts);
        
        return response()->json($counts);
    }
}
