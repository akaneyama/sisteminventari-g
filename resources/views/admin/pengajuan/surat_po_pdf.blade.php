<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Surat Pesanan (PO)</title>
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
            margin-bottom: 20px;
        }
        .info-tujuan {
            margin-bottom: 20px;
        }
        .table-barang {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        .table-barang th, .table-barang td {
            border: 1px solid #000;
            padding: 8px;
        }
        .table-barang th {
            background-color: #f0f0f0;
            font-weight: bold;
            text-align: center;
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
                // Gunakan base64 untuk memastikan DOMPDF bisa membaca gambar tanpa masalah symlink/URL
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

    <div class="judul-surat">SURAT PESANAN BARANG (PURCHASE ORDER)</div>
    <div class="nomor-surat">Nomor: PO/{{ date('Y/m') }}/{{ str_pad($barang->id_barang, 4, '0', STR_PAD_LEFT) }}</div>

    <div class="info-tujuan">
        <p>Kepada Yth.,<br>
        <strong>Pimpinan {{ $barang->supplier->nama_supplier ?? 'Toko/Vendor (Belum Ditentukan)' }}</strong><br>
        {{ $barang->supplier->alamat ?? '-' }}<br>
        Telepon: {{ $barang->supplier->telepon ?? '-' }}
        </p>
        <br>
        <p>Dengan hormat,</p>
        <p>Berdasarkan kebutuhan sekolah kami dan persetujuan dari Kepala Sekolah, melalui surat ini kami bermaksud untuk melakukan pemesanan pengadaan barang sebagai berikut:</p>
    </div>

    <table class="table-barang">
        <thead>
            <tr>
                <th style="width: 5%">No</th>
                <th style="width: 25%">Kode Inventaris</th>
                <th style="width: 30%">Nama Barang</th>
                <th style="width: 20%">Merk / Tipe</th>
                <th style="width: 20%">Jumlah Dipesan</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="text-align: center;">1</td>
                <td style="text-align: center;">{{ $barang->kode_inventaris }}</td>
                <td>{{ $barang->nama_barang }}</td>
                <td>{{ $barang->merk_type }}</td>
                <td style="text-align: center;">{{ $barang->jumlah_barang }} Unit</td>
            </tr>
        </tbody>
    </table>

    <p>Kami mohon agar barang tersebut dapat dikirimkan ke alamat sekolah kami selambat-lambatnya 7 (tujuh) hari kerja sejak surat pesanan ini diterima.</p>
    <p>Demikian Surat Pesanan ini kami buat untuk dapat diproses lebih lanjut. Atas perhatian dan kerjasamanya kami ucapkan terima kasih.</p>

    <table class="tanda-tangan">
        <tr>
            <td>
                Mengetahui/Menyetujui,<br>
                Kepala Sekolah
                <div class="ttd-nama">{{ $identitas->nama_kepala_sekolah }}</div>
                <div>NIP. {{ $identitas->nip_kepala_sekolah }}</div>
            </td>
            <td>
                {{ date('d F Y') }}<br>
                Pejabat Pengadaan / Admin Sarpras
                <div class="ttd-nama">{{ Auth::user()->nama_lengkap ?? '_________________________' }}</div>
                <div>NIP. {{ Auth::user()->nip ?? '-' }}</div>
            </td>
        </tr>
    </table>

</body>
</html>
