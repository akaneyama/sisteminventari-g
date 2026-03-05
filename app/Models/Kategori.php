<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    protected $table = 'kategori'; // Nama tabel di database
    protected $primaryKey = 'id_kategori'; // Primary key custom
    
    protected $fillable = [
        'nama_kategori',
        'deskripsi',
    ];
}