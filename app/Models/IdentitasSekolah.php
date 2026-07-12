<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IdentitasSekolah extends Model
{
    use HasFactory;

    protected $table = 'identitas_sekolah';

    protected $fillable = [
        'naungan',
        'nama_sekolah',
        'alamat',
        'email',
        'telepon',
        'website',
        'logo',
        'nama_kepala_sekolah',
        'nip_kepala_sekolah',
    ];
}
