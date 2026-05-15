<?php

namespace App\Http\Controllers;

use App\Services\IdentitasSekolahService;
use Illuminate\Http\Request;

class IdentitasSekolahController extends Controller
{
    protected $identitasService;

    public function __construct(IdentitasSekolahService $identitasService)
    {
        $this->identitasService = $identitasService;
    }

    public function index()
    {
        $identitas = $this->identitasService->getIdentitas();
        return view('admin.identitas.index', compact('identitas'));
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'nama_sekolah' => 'required|string|max:255',
            'alamat' => 'required|string',
            'email' => 'nullable|email|max:255',
            'telepon' => 'nullable|string|max:50',
            'website' => 'nullable|string|max:255',
            'nama_kepala_sekolah' => 'nullable|string|max:255',
            'nip_kepala_sekolah' => 'nullable|string|max:100',
            'logo_file' => 'nullable|image|mimes:jpeg,png,jpg,svg|max:2048',
        ]);

        $this->identitasService->updateIdentitas($data);

        return redirect()->back()->with('success', 'Data identitas sekolah berhasil diperbarui!');
    }
}
