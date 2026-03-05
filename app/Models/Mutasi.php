<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mutasi extends Model
{
    protected $table = 'mutasi';
    protected $primaryKey = 'id_mutasi';

    protected $fillable = [
        'tanggal_mutasi',
        'jenis_mutasi',
        'id_barang',
        'id_user',
        'lokasi_asal',
        'lokasi_tujuan',
        'kondisi_sebelum',
        'kondisi_sesudah',
        'keterangan',
    ];

    // Relasi
    public function barang() {
        //withTrashed() agar barang yang sudah dihapus tetap muncul di riwayat mutasi
        return $this->belongsTo(Barang::class, 'id_barang', 'id_barang')->withTrashed(); 
    }

    public function user() {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    public function lokasiAsal() {
        return $this->belongsTo(Lokasi::class, 'lokasi_asal', 'id_lokasi');
    }

    public function lokasiTujuan() {
        return $this->belongsTo(Lokasi::class, 'lokasi_tujuan', 'id_lokasi');
    }
}