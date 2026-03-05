<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lokasi extends Model
{
    protected $table = 'lokasi'; // Nama tabel di database
    protected $primaryKey = 'id_lokasi'; // Primary key custom

    protected $fillable = [
        'nama_ruangan',
        'gedung',
    ];
}