<?php

namespace App\Http\Controllers;

use App\Services\BarangService;
use Illuminate\Http\Request;

class PengajuanController extends Controller
{
    protected $barangService;

    public function __construct(BarangService $barangService)
    {
        $this->barangService = $barangService;
    }

    public function index()
    {
        $pengajuans = $this->barangService->getPengadaanStatus();
        return view('admin.pengajuan.index', compact('pengajuans'));
    }
}
