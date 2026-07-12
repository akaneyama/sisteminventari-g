<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Bukti Perbaikan Barang</title>
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

    <div class="judul-surat">SURAT BUKTI PERBAIKAN BARANG (SERVICE)</div>
    <div class="nomor-surat">Nomor: SRV/{{ date('Y/m', strtotime($perbaikan->tanggal_selesai ?? now())) }}/{{ str_pad($perbaikan->id_perbaikan, 4, '0', STR_PAD_LEFT) }}</div>

    <div class="paragraf">
        Berdasarkan catatan pemeliharaan aset sekolah, telah dilakukan tindakan perbaikan / <em>service</em> pada Barang Milik Sekolah/Negara di <strong>{{ $identitas->nama_sekolah }}</strong> dengan rincian sebagai berikut:
    </div>

    <table class="table-rincian">
        <tr>
            <td>Kode Inventaris</td>
            <td>: {{ $perbaikan->barang->kode_inventaris }}</td>
        </tr>
        <tr>
            <td>Nama Barang</td>
            <td>: {{ $perbaikan->barang->nama_barang }}</td>
        </tr>
        <tr>
            <td>Merk / Tipe</td>
            <td>: {{ $perbaikan->barang->merk_type ?? '-' }}</td>
        </tr>
        <tr>
            <td>Tanggal Mulai</td>
            <td>: {{ \Carbon\Carbon::parse($perbaikan->tanggal_mulai)->format('d F Y') }}</td>
        </tr>
        <tr>
            <td>Tanggal Selesai</td>
            <td>: {{ $perbaikan->tanggal_selesai ? \Carbon\Carbon::parse($perbaikan->tanggal_selesai)->format('d F Y') : '-' }}</td>
        </tr>
        <tr>
            <td>Kendala Awal</td>
            <td>: {{ $perbaikan->keterangan }}</td>
        </tr>
        <tr>
            <td>Hasil Perbaikan</td>
            <td>: <strong>{{ $perbaikan->status_perbaikan }}</strong></td>
        </tr>
        <tr>
            <td>Biaya Perbaikan</td>
            <td>: Rp {{ number_format($perbaikan->biaya ?? 0, 0, ',', '.') }}</td>
        </tr>
    </table>

    <div class="paragraf">
        Barang tersebut saat ini dinyatakan dalam kondisi <strong>{{ $perbaikan->barang->kondisi }}</strong> dan diserahkan kembali untuk dipergunakan dalam menunjang kegiatan operasional/akademik sekolah sesuai fungsinya.
    </div>
    
    <div class="paragraf">
        Demikian Surat Bukti Perbaikan Barang ini dibuat dengan sebenarnya untuk dapat dipergunakan sebagai dokumen pelaporan pemeliharaan inventaris.
    </div>

    <table class="tanda-tangan">
        <tr>
            <td>
                Mengetahui,<br>
                Kepala Sekolah
                <div class="ttd-nama">{{ $identitas->nama_kepala_sekolah }}</div>
                <div>NIP. {{ $identitas->nip_kepala_sekolah }}</div>
            </td>
            <td>
                Dibuat Oleh,<br>
                Admin Inventari
                <div class="ttd-nama">{{ Auth::user()->nama_lengkap ?? 'Admin Inventari' }}</div>
                <div>NIP. {{ Auth::user()->nip ?? '-' }}</div>
            </td>
        </tr>
    </table>

</body>
</html>
