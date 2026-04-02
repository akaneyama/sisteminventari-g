<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SumberDana extends Model
{
    use HasFactory;

    protected $table = 'sumber_dana';
    protected $primaryKey = 'id_sumber_dana';
    protected $fillable = ['nama_sumber_dana', 'deskripsi'];

    public function barangs()
    {
        return $this->hasMany(Barang::class, 'id_sumber_dana_new', 'id_sumber_dana');
    }
}
