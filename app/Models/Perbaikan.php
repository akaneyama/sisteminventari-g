<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Perbaikan extends Model
{
    protected $table = 'perbaikans';
    protected $primaryKey = 'id_perbaikan';

    protected $fillable = [
        'id_barang',
        'tanggal_mulai',
        'tanggal_selesai',
        'keterangan',
        'biaya',
        'status_perbaikan',
        'nota_perbaikan',
    ];

    public function barang()
    {
        return $this->belongsTo(Barang::class, 'id_barang', 'id_barang')->withTrashed();
    }
}
