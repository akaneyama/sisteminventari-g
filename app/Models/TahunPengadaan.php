<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TahunPengadaan extends Model
{
    use HasFactory;

    protected $table = 'tahun_pengadaan';
    protected $primaryKey = 'id_tahun_pengadaan';
    protected $fillable = ['tahun', 'deskripsi'];

    public function barangs()
    {
        return $this->hasMany(Barang::class, 'id_tahun_pengadaan_new', 'id_tahun_pengadaan');
    }
}
