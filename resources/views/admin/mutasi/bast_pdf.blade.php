<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Berita Acara Mutasi Barang</title>
    <style>
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 12pt;
            line-height: 1.5;
            margin: 0;
            padding: 0;
        }
        .kop-surat {
            border-bottom: 3px solid #000;
            padding-bottom: 10px;
            margin-bottom: 20px;
            text-align: center;
        }
        .kop-surat h1 {
            font-size: 16pt;
            font-weight: bold;
            margin: 0;
            text-transform: uppercase;
        }
        .kop-surat h2 {
            font-size: 14pt;
            margin: 0;
        }
        .kop-surat p {
            font-size: 10pt;
            margin: 0;
        }
        .logo {
            position: absolute;
            top: 10px;
            left: 20px;
            width: 80px;
        }
        .judul-surat {
            text-align: center;
            font-size: 14pt;
            font-weight: bold;
            text-decoration: underline;
            margin-bottom: 5px;
        }
        .nomor-surat {
            text-align: center;
            margin-bottom: 30px;
        }
        .paragraf {
            margin-bottom: 15px;
            text-align: justify;
        }
        .table-rincian {
            width: 100%;
            margin-bottom: 20px;
            margin-left: 20px;
        }
        .table-rincian td {
            padding: 3px;
        }
        .table-rincian td:first-child {
            width: 150px;
            font-weight: bold;
        }
        .tanda-tangan {
            width: 100%;
            margin-top: 50px;
        }
        .tanda-tangan td {
            width: 50%;
            text-align: center;
        }
        .ttd-nama {
            font-weight: bold;
            text-decoration: underline;
            margin-top: 80px;
        }
    </style>
</head>
<body>

    <div class="kop-surat">
        @if($identitas->logo)
            @php
                $path = storage_path('app/public/logos/' . $identitas->logo);
                if(file_exists($path)) {
                    $type = pathinfo($path, PATHINFO_EXTENSION);
                    $data = file_get_contents($path);
                    $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
                } else {
                    $base64 = null;
                }
            @endphp
            @if($base64)
                <img src="{{ $base64 }}" class="logo">
            @endif
        @endif
        
        @if($identitas->naungan)
            <h1>{{ $identitas->naungan }}</h1>
            <h2>{{ $identitas->nama_sekolah }}</h2>
        @else
            <h1>{{ $identitas->nama_sekolah }}</h1>
        @endif
        
        <p>{{ $identitas->alamat }}</p>
        <p>Telepon: {{ $identitas->telepon }} | Email: {{ $identitas->email }} | Web: {{ $identitas->website }}</p>
    </div>

    @php
        $judul_bast = $mutasi->jenis_mutasi === 'Penghapusan' ? 'BERITA ACARA PENGHAPUSAN BARANG INVENTARIS' : 'BERITA ACARA MUTASI/PINDAH LOKASI BARANG';
        $singkatan = $mutasi->jenis_mutasi === 'Penghapusan' ? 'BAP' : 'BAST';
    @endphp

    <div class="judul-surat">{{ $judul_bast }}</div>
    <div class="nomor-surat">Nomor: {{ $singkatan }}/{{ date('Y/m', strtotime($mutasi->tanggal_mutasi)) }}/{{ str_pad($mutasi->id_mutasi, 4, '0', STR_PAD_LEFT) }}</div>

    <div class="paragraf">
        Pada hari ini, tanggal <strong>{{ \Carbon\Carbon::parse($mutasi->tanggal_mutasi)->format('d F Y') }}</strong>, bertempat di <strong>{{ $identitas->nama_sekolah }}</strong>, telah dilakukan proses {{ strtolower($mutasi->jenis_mutasi) }} terhadap Barang Milik Sekolah/Negara berdasarkan persetujuan dari Kepala Sekolah dengan rincian barang sebagai berikut:
    </div>

    <table class="table-rincian">
        <tr>
            <td>Kode Inventaris</td>
            <td>: {{ $mutasi->barang->kode_inventaris }}</td>
        </tr>
        <tr>
            <td>Nama Barang</td>
            <td>: {{ $mutasi->barang->nama_barang }}</td>
        </tr>
        <tr>
            <td>Merk / Tipe</td>
            <td>: {{ $mutasi->barang->merk_type ?? '-' }}</td>
        </tr>
        <tr>
            <td>Jumlah Dimutasi</td>
            <td>: {{ $mutasi->jumlah }} Unit</td>
        </tr>
        <tr>
            <td>Kondisi Barang</td>
            <td>: {{ $mutasi->kondisi_sebelum }}</td>
        </tr>
        
        @if($mutasi->jenis_mutasi === 'Pindah Lokasi')
        <tr>
            <td>Lokasi Asal</td>
            <td>: {{ $mutasi->lokasiAsal->nama_ruangan ?? '-' }}</td>
        </tr>
        <tr>
            <td>Lokasi Tujuan</td>
            <td>: {{ $mutasi->lokasiTujuan->nama_ruangan ?? '-' }}</td>
        </tr>
        @endif
        
        <tr>
            <td>Keterangan</td>
            <td>: {{ $mutasi->keterangan }}</td>
        </tr>
    </table>

    @if($mutasi->jenis_mutasi === 'Penghapusan')
    <div class="paragraf">
        Dengan ditandatanganinya Berita Acara ini, barang tersebut secara resmi telah dihapus dari daftar aset/inventaris aktif sekolah karena alasan yang tertera pada keterangan di atas, dan tanggung jawab terhadap fisik barang diserahkan kepada petugas terkait untuk dimusnahkan/ditindaklanjuti.
    </div>
    @elseif($mutasi->jenis_mutasi === 'Pindah Lokasi')
    <div class="paragraf">
        Dengan ditandatanganinya Berita Acara ini, barang tersebut telah resmi dipindahkan posisinya dan pencatatan inventarisnya telah disesuaikan ke lokasi tujuan baru. Pihak Penanggung Jawab di lokasi tujuan wajib menjaga dan memelihara barang tersebut dengan sebaik-baiknya.
    </div>
    @endif

    <div class="paragraf">
        Demikian Berita Acara ini dibuat dengan sebenar-benarnya untuk dapat dipergunakan sebagaimana mestinya.
    </div>

    <table class="tanda-tangan">
        <tr>
            <td>
                Menyetujui,<br>
                Kepala Sekolah
                <div class="ttd-nama">{{ $identitas->nama_kepala_sekolah }}</div>
                <div>NIP. {{ $identitas->nip_kepala_sekolah }}</div>
            </td>
            <td>
                Dibuat Oleh,<br>
                Admin Sarpras / Petugas Mutasi
                <div class="ttd-nama">{{ current(explode(' ', $mutasi->user->nama_lengkap ?? 'Admin/Petugas')) }}</div>
                <div>NIP. {{ $mutasi->user->nip ?? '-' }}</div>
            </td>
        </tr>
    </table>

</body>
</html>
