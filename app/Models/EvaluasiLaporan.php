<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EvaluasiLaporan extends Model
{
    protected $table = 'evaluasi_laporans';
    protected $primaryKey = 'id_evaluasi';

    protected $fillable = [
        'periode',
        'catatan',
        'status',
    ];
}
