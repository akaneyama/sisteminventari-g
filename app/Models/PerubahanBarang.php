<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PerubahanBarang extends Model
{
    protected $table = 'perubahan_barangs';
    protected $primaryKey = 'id_perubahan';

    protected $fillable = [
        'id_barang',
        'data_lama',
        'data_baru',
        'status',
        'alasan_penolakan',
    ];

    protected $casts = [
        'data_lama' => 'array',
        'data_baru' => 'array',
    ];

    public function barang()
    {
        return $this->belongsTo(Barang::class, 'id_barang', 'id_barang')->withTrashed();
    }
}
