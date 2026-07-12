<?php

namespace App\Services;

use App\Models\Barang;
use App\Models\PerubahanBarang;
use Illuminate\Support\Facades\Storage;

class BarangService
{
    public function getAll(array $filter = [], $excludePendingDelete = true)
    {
        $query = Barang::with(['kategori', 'lokasi', 'supplier', 'sumberDana']);

        if ($excludePendingDelete) {
            $query->whereIn('status_approval', ['Tersedia', 'Dalam Perbaikan']);
        }

        if (!empty($filter['search'])) {
            $s = $filter['search'];
            $query->where(function($q) use ($s) {
                $q->where('kode_inventaris', 'like', "%{$s}%")
                  ->orWhere('nama_barang', 'like', "%{$s}%")
                  ->orWhere('merk_type', 'like', "%{$s}%");
            });
        }
        if (!empty($filter['id_lokasi']))    $query->where('id_lokasi', $filter['id_lokasi']);
        if (!empty($filter['id_kategori']))  $query->where('id_kategori', $filter['id_kategori']);
        if (!empty($filter['kondisi']))      $query->where('kondisi', $filter['kondisi']);

        return $query->latest()->paginate(10);
    }

    public function getPendingApproval()
    {
        return Barang::with(['kategori', 'lokasi'])->where('status_approval', 'Menunggu Penghapusan')->latest()->paginate(10);
    }

    public function create(array $data)
    {
        // Handle upload foto jika ada
        if (isset($data['foto_barang'])) {
            $data['foto_barang'] = $data['foto_barang']->
            store('barang_fotos', 'public');
        }

        $data['status_approval'] = 'Menunggu Pengadaan';
        $data['jumlah_diajukan'] = $data['jumlah_barang'] ?? 0;

        return Barang::create($data);
    }

    public function getById($id)
    {
        return Barang::findOrFail($id);
    }

    public function update($id, array $data)
    {
        $barang = Barang::findOrFail($id);

        // Handle foto: langsung diupdate tanpa approval
        if (isset($data['foto_barang'])) {
            if ($barang->foto_barang && Storage::disk('public')->exists($barang->foto_barang)) {
                Storage::disk('public')->delete($barang->foto_barang);
            }
            $barang->foto_barang = $data['foto_barang']->store('barang_fotos', 'public');
            $barang->save();
            unset($data['foto_barang']);
        }

        // Jika tidak ada data lain yang berubah, selesai
        if (empty($data)) return $barang;

        // Snapshot data lama (field yang diubah saja)
        $dataLama = $barang->only(array_keys($data));

        // Simpan ke perubahan_barangs, bukan langsung ke barang
        PerubahanBarang::create([
            'id_barang'  => $barang->id_barang,
            'data_lama'  => $dataLama,
            'data_baru'  => $data,
            'status'     => 'Menunggu',
        ]);

        return $barang;
    }

    public function requestDelete($id)
    {
        $barang = Barang::findOrFail($id);
        $barang->status_approval = 'Menunggu Penghapusan';
        $barang->save();
        return $barang;
    }

    public function approveDelete($id)
    {
        $barang = Barang::findOrFail($id);
        // Kembalikan ke Tersedia agar saat di-restore nanti statusnya normal
        $barang->status_approval = 'Tersedia'; 
        $barang->save();
        // Lakukan soft delete
        return $barang->delete();
    }

    public function rejectDelete($id)
    {
        $barang = Barang::findOrFail($id);
        $barang->status_approval = 'Tersedia';
        $barang->save();
        return $barang;
    }

    public function getPengadaanStatus($tab = 'aktif')
    {
        $query = Barang::with(['kategori', 'lokasi']);
        
        if ($tab === 'aktif') {
            $query->whereIn('status_approval', ['Menunggu Pengadaan', 'Pengadaan Disetujui']);
        } elseif ($tab === 'ditolak') {
            $query->where('status_approval', 'Pengadaan Ditolak');
        } else {
            // Tab 'semua' juga menampilkan barang yang sudah selesai (Tersedia)
            $query->whereIn('status_approval', ['Menunggu Pengadaan', 'Pengadaan Disetujui', 'Pengadaan Ditolak', 'Tersedia', 'Dalam Perbaikan']);
        }
        
        return $query->latest()->paginate(10);
    }

    public function getPendingPengadaan()
    {
        return Barang::with(['kategori', 'lokasi'])
            ->where('status_approval', 'Menunggu Pengadaan')
            ->latest()
            ->paginate(10);
    }

    public function approvePengadaan($id, $jumlah_disetujui, $alasan = null)
    {
        $barang = Barang::findOrFail($id);
        $barang->status_approval = 'Pengadaan Disetujui';
        $barang->jumlah_barang = $jumlah_disetujui;
        $barang->alasan_penolakan = $alasan;
        $barang->save();
        return $barang;
    }

    public function terimaPengadaan($id, $fileBukti = null)
    {
        $barang = Barang::findOrFail($id);
        
        if ($fileBukti) {
            $barang->bukti_nota = $fileBukti->store('bukti_penerimaan', 'public');
        }

        $barang->status_approval = 'Tersedia';
        $barang->save();
        return $barang;
    }

    public function rejectPengadaan($id, $alasan)
    {
        $barang = Barang::findOrFail($id);
        $barang->status_approval = 'Pengadaan Ditolak';
        $barang->alasan_penolakan = $alasan;
        $barang->save();
        return $barang;
    }

    public function getPendingPerubahan()
    {
        return PerubahanBarang::with(['barang.kategori', 'barang.lokasi'])
            ->where('status', 'Menunggu')
            ->latest()
            ->paginate(10);
    }

    public function approvePerubahan($id)
    {
        $perubahan = PerubahanBarang::findOrFail($id);
        $barang = Barang::findOrFail($perubahan->id_barang);

        // Resolve relasi ID jika ada (misal id_kategori, id_lokasi)
        $barang->update($perubahan->data_baru);

        $perubahan->status = 'Disetujui';
        $perubahan->save();

        return $barang;
    }

    public function rejectPerubahan($id, $alasan)
    {
        $perubahan = PerubahanBarang::findOrFail($id);
        $perubahan->status = 'Ditolak';
        $perubahan->alasan_penolakan = $alasan;
        $perubahan->save();
        return $perubahan;
    }
}