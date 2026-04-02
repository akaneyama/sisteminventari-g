<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Barang extends Model
{
    use SoftDeletes;

    protected $table = 'barang';
    protected $primaryKey = 'id_barang';

    protected $fillable = [
        'kode_inventaris',
        'nama_barang',
        'merk_type',
        'tahun_perolehan',
        'sumber_dana',
        'kondisi',
        'id_kategori',
        'id_lokasi',
        'id_sumber_dana_new',
        'id_tahun_pengadaan_new',
        'foto_barang',
    ];

    // Relasi ke tabel Kategori
    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'id_kategori', 'id_kategori');
    }

    // Relasi ke tabel Lokasi
    public function lokasi()
    {
        return $this->belongsTo(Lokasi::class, 'id_lokasi', 'id_lokasi');
    }

    public function sumberDana()
    {
        return $this->belongsTo(SumberDana::class, 'id_sumber_dana_new', 'id_sumber_dana');
    }

    public function tahunPengadaan()
    {
        return $this->belongsTo(TahunPengadaan::class, 'id_tahun_pengadaan_new', 'id_tahun_pengadaan');
    }
}