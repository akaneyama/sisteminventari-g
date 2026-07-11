<?php

namespace App\Services;

use App\Models\IdentitasSekolah;
use Illuminate\Support\Facades\Storage;

class IdentitasSekolahService
{
    public function getIdentitas()
    {
        return IdentitasSekolah::first();
    }

    public function updateIdentitas(array $data)
    {
        $identitas = IdentitasSekolah::first();

        // Handle file upload
        if (isset($data['logo_file'])) {
            $file = $data['logo_file'];
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('logos', $filename, 'public');
            
            // Delete old logo if exists
            if ($identitas && $identitas->logo && Storage::disk('public')->exists('logos/' . $identitas->logo)) {
                Storage::disk('public')->delete('logos/' . $identitas->logo);
            }
            
            $data['logo'] = $filename;
        }

        if (!$identitas) {
            return IdentitasSekolah::create($data);
        }

        $identitas->update($data);
        return $identitas;
    }
}





