<?php

namespace App\Services;

use App\Models\Mutasi;
use App\Models\Barang;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class MutasiService
{
    public function getAll(array $filter = [])
    {
        $query = Mutasi::with(['barang', 'user', 'lokasiAsal', 'lokasiTujuan']);

        if (!empty($filter['search'])) {
            $s = $filter['search'];
            $query->whereHas('barang', function($q) use ($s) {
                $q->where('kode_inventaris', 'like', "%{$s}%")
                  ->orWhere('nama_barang', 'like', "%{$s}%");
            });
        }
        if (!empty($filter['jenis_mutasi']))  $query->where('jenis_mutasi', $filter['jenis_mutasi']);
        if (!empty($filter['tanggal_dari']))  $query->whereDate('tanggal_mutasi', '>=', $filter['tanggal_dari']);
        if (!empty($filter['tanggal_sampai'])) $query->whereDate('tanggal_mutasi', '<=', $filter['tanggal_sampai']);

        return $query->orderBy('tanggal_mutasi', 'desc')->latest()->get();
    }

    public function create(array $data)
    {
        return DB::transaction(function () use ($data) {
            $barang = Barang::findOrFail($data['id_barang']);

            if ($barang->status_approval !== 'Tersedia') {
                throw new \Exception('Barang tidak bisa dimutasi karena sedang dalam status: ' . $barang->status_approval);
            }

            if ($data['jumlah'] > $barang->jumlah_barang) {
                throw new \Exception('Jumlah mutasi melebihi sisa barang yang tersedia.');
            }

            $data['id_user'] = Auth::user()->id_user;
            $data['lokasi_asal'] = $barang->id_lokasi;
            $data['kondisi_sebelum'] = $barang->kondisi;
            $data['status'] = 'Menunggu'; // Tidak langsung dieksekusi

            return Mutasi::create($data);
        });
    }

    public function getPendingMutasi()
    {
        return Mutasi::with(['barang', 'user', 'lokasiAsal', 'lokasiTujuan'])
            ->where('status', 'Menunggu')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function approveMutasi($id)
    {
        return DB::transaction(function () use ($id) {
            $mutasi = Mutasi::findOrFail($id);
            $barang = Barang::findOrFail($mutasi->id_barang);

            // Validasi ulang jumlah
            if ($mutasi->jumlah > $barang->jumlah_barang) {
                throw new \Exception('Jumlah mutasi melebihi sisa barang yang tersedia.');
            }

            // Mutasi parsial: clone barang
            if ($mutasi->jumlah < $barang->jumlah_barang) {
                $barang->decrement('jumlah_barang', $mutasi->jumlah);

                $targetBarang = $barang->replicate();
                $targetBarang->jumlah_barang = $mutasi->jumlah;
                $targetBarang->kode_inventaris = $barang->kode_inventaris . '-SPLIT-' . time();
                $targetBarang->save();
            } else {
                $targetBarang = $barang;
            }

            // Update ID di mutasi jika diclone
            $mutasi->id_barang = $targetBarang->id_barang;

            // Eksekusi perubahan fisik
            if ($mutasi->jenis_mutasi === 'Pindah Lokasi') {
                $targetBarang->update(['id_lokasi' => $mutasi->lokasi_tujuan]);
            } elseif ($mutasi->jenis_mutasi === 'Ubah Status') {
                $targetBarang->update(['kondisi' => $mutasi->kondisi_sesudah]);
            } elseif ($mutasi->jenis_mutasi === 'Penghapusan') {
                $targetBarang->delete();
            }

            $mutasi->status = 'Disetujui';
            $mutasi->save();

            // Auto-create perbaikan jika dicentang dan jenis mutasi adalah Ubah Status
            if ($mutasi->jenis_mutasi === 'Ubah Status' && $mutasi->ajukan_servis) {
                app(\App\Services\PerbaikanService::class)->mulaiPerbaikan($targetBarang->id_barang, [
                    'tanggal_mulai' => now()->toDateString(),
                    'keterangan' => 'Mutasi ke ' . $mutasi->kondisi_sesudah . ': ' . $mutasi->keterangan
                ]);
            }

            return $mutasi;
        });
    }

    public function rejectMutasi($id, $alasan)
    {
        $mutasi = Mutasi::findOrFail($id);
        $mutasi->status = 'Ditolak';
        $mutasi->alasan_penolakan = $alasan;
        $mutasi->save();
        return $mutasi;
    }
}