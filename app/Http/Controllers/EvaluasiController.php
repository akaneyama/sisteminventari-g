<?php

namespace App\Http\Controllers;

use App\Models\EvaluasiLaporan;
use Illuminate\Http\Request;

class EvaluasiController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'periode' => 'required|string|max:255',
            'catatan' => 'required|string',
        ]);

        EvaluasiLaporan::create($data);

        return back()->with('success', 'Evaluasi laporan berhasil dikirim ke Admin.');
    }

    public function markAsRead($id)
    {
        $evaluasi = EvaluasiLaporan::findOrFail($id);
        $evaluasi->update(['status' => 'Sudah Dibaca']);

        return back()->with('success', 'Catatan ditandai sebagai sudah dibaca.');
    }
}
