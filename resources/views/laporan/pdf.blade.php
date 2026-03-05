<!DOCTYPE html>
<html>
<head>
    <title>Laporan Inventaris</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #000; padding-bottom: 10px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #333; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <div class="header">
        <h2>Laporan Rekapitulasi Inventaris Aset Sekolah</h2>
        <p>Tanggal Cetak: {{ date('d/m/Y') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Kode Inventaris</th>
                <th>Nama Barang</th>
                <th>Merk/Tipe</th>
                <th>Lokasi</th>
                <th>Kondisi</th>
                <th>Tahun</th>
            </tr>
        </thead>
        <tbody>
            @foreach($barang as $item)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $item->kode_inventaris }}</td>
                <td>{{ $item->nama_barang }}</td>
                <td>{{ $item->merk_type }}</td>
                <td>{{ $item->lokasi->nama_ruangan ?? '-' }}</td>
                <td>{{ $item->kondisi }}</td>
                <td>{{ $item->tahun_perolehan }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>