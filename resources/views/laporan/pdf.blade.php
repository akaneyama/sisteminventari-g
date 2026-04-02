<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Inventaris</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 9.5px; color: #1e293b; }

        /* KOP / HEADER */
        .kop { width: 100%; border-bottom: 3px solid #1e40af; padding-bottom: 10px; margin-bottom: 12px; }
        .kop-inner { display: flex; align-items: center; gap: 14px; }
        .kop-logo { width: 60px; height: 60px; }
        .kop-title h1 { font-size: 14px; font-weight: bold; text-transform: uppercase; color: #1e3a8a; }
        .kop-title h2 { font-size: 11px; font-weight: bold; color: #1e3a8a; }
        .kop-title p  { font-size: 9px; color: #475569; margin-top: 1px; }
        .report-title { text-align: center; margin: 10px 0; }
        .report-title h3 { font-size: 12px; font-weight: bold; text-transform: uppercase; letter-spacing: 1px; color: #1e293b; }
        .report-title p  { font-size: 9px; color: #64748b; margin-top: 3px; }

        /* STATISTIK */
        .stats-row { width: 100%; border-collapse: collapse; margin-bottom: 14px; }
        .stats-row td { padding: 7px 10px; text-align: center; border-radius: 4px; font-size: 9px; }
        .stat-box { border: 1px solid #e2e8f0; border-radius: 5px; padding: 6px 10px; text-align: center; }
        .stat-box .val { font-size: 16px; font-weight: bold; }
        .stat-box .lbl { font-size: 8px; color: #64748b; }
        .stat-blue  { border-color: #bfdbfe; background: #eff6ff; }
        .stat-green { border-color: #bbf7d0; background: #f0fdf4; }
        .stat-yellow{ border-color: #fef08a; background: #fefce8; }
        .stat-red   { border-color: #fecaca; background: #fef2f2; }
        .val-blue   { color: #1d4ed8; }
        .val-green  { color: #15803d; }
        .val-yellow { color: #a16207; }
        .val-red    { color: #b91c1c; }

        /* TABEL DATA */
        table.data { width: 100%; border-collapse: collapse; }
        table.data thead tr { background-color: #1e40af; color: white; }
        table.data thead th { padding: 6px 6px; text-align: left; font-size: 8.5px; font-weight: bold; text-transform: uppercase; letter-spacing: 0.5px; }
        table.data tbody tr:nth-child(even) { background-color: #f8fafc; }
        table.data tbody tr:hover { background-color: #eff6ff; }
        table.data tbody td { padding: 5px 6px; border-bottom: 1px solid #f1f5f9; vertical-align: top; }
        table.data tfoot tr { background-color: #eff6ff; font-weight: bold; }
        table.data tfoot td { padding: 6px 6px; border-top: 2px solid #bfdbfe; }

        .badge { display: inline-block; padding: 1px 6px; border-radius: 9999px; font-size: 8px; font-weight: bold; }
        .badge-green  { background: #dcfce7; color: #15803d; }
        .badge-yellow { background: #fef9c3; color: #a16207; }
        .badge-red    { background: #fee2e2; color: #b91c1c; }
        .text-muted { color: #94a3b8; }
        .text-small { font-size: 8.5px; }

        /* FOOTER */
        .footer { margin-top: 20px; font-size: 8.5px; color: #94a3b8; border-top: 1px solid #e2e8f0; padding-top: 8px; display: flex; justify-content: space-between; }
    </style>
</head>
<body>

    {{-- KOP LAPORAN --}}
    <div class="kop">
        <div class="kop-inner">
            <div class="kop-title">
                <h1>Laporan Rekapitulasi Inventaris Aset</h1>
                <h2>Sistem Inventaris &mdash; Sekolah Anda</h2>
                <p>Alamat Sekolah, Kecamatan, Kabupaten/Kota &bull; Telp. (0XXX) XXXXXX</p>
            </div>
        </div>
    </div>

    {{-- JUDUL LAPORAN --}}
    <div class="report-title">
        <h3>Daftar Inventaris Aset Aktif</h3>
        <p>Tanggal Cetak: {{ date('d F Y') }} &bull; Total {{ $stats['total_jenis'] }} jenis barang &bull; {{ $stats['total_unit'] }} unit</p>
    </div>

    {{-- STATISTIK RINGKASAN --}}
    <table class="stats-row" style="margin-bottom: 14px;">
        <tr>
            <td style="width:25%;padding:4px;">
                <div class="stat-box stat-blue">
                    <div class="val val-blue">{{ $stats['total_unit'] }}</div>
                    <div class="lbl">Total Unit</div>
                </div>
            </td>
            <td style="width:25%;padding:4px;">
                <div class="stat-box stat-green">
                    <div class="val val-green">{{ $stats['kondisi_baik'] }}</div>
                    <div class="lbl">Kondisi Baik</div>
                </div>
            </td>
            <td style="width:25%;padding:4px;">
                <div class="stat-box stat-yellow">
                    <div class="val val-yellow">{{ $stats['rusak_ringan'] }}</div>
                    <div class="lbl">Rusak Ringan</div>
                </div>
            </td>
            <td style="width:25%;padding:4px;">
                <div class="stat-box stat-red">
                    <div class="val val-red">{{ $stats['rusak_berat'] }}</div>
                    <div class="lbl">Rusak Berat</div>
                </div>
            </td>
        </tr>
    </table>

    {{-- TABEL DATA INVENTARIS --}}
    <table class="data">
        <thead>
            <tr>
                <th style="width:3%">No</th>
                <th style="width:11%">Kode Inventaris</th>
                <th style="width:16%">Nama Barang</th>
                <th style="width:8%">Merk/Tipe</th>
                <th style="width:8%">Kategori</th>
                <th style="width:10%">Lokasi Ruangan</th>
                <th style="width:4%">Jml</th>
                <th style="width:7%">Kondisi</th>
                <th style="width:11%">Supplier</th>
                <th style="width:12%">Sumber Dana</th>
                <th style="width:5%">Thn. Perolehan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($barang as $item)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td><strong>{{ $item->kode_inventaris }}</strong></td>
                <td>{{ $item->nama_barang }}</td>
                <td class="text-small text-muted">{{ $item->merk_type }}</td>
                <td>{{ $item->kategori->nama_kategori ?? '-' }}</td>
                <td>{{ $item->lokasi->nama_ruangan ?? '-' }}</td>
                <td style="text-align:center;font-weight:bold">{{ $item->jumlah_barang }}</td>
                <td>
                    @if($item->kondisi == 'Baik')
                        <span class="badge badge-green">Baik</span>
                    @elseif($item->kondisi == 'Rusak Ringan')
                        <span class="badge badge-yellow">Rusak Ringan</span>
                    @else
                        <span class="badge badge-red">Rusak Berat</span>
                    @endif
                </td>
                <td>{{ $item->supplier->nama_supplier ?? '-' }}</td>
                <td>
                    {{ $item->sumberDana->nama_sumber_dana ?? '-' }}
                    @if($item->sumberDana?->tahun)
                        <span class="text-muted">({{ $item->sumberDana->tahun }})</span>
                    @endif
                </td>
                <td style="text-align:center">{{ $item->tahun_perolehan ?? '-' }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="6" style="text-align:right; font-size:9px; color:#1e40af">Total Unit Barang:</td>
                <td style="text-align:center; color:#1d4ed8; font-size:11px">{{ $stats['total_unit'] }}</td>
                <td colspan="4"></td>
            </tr>
        </tfoot>
    </table>

    {{-- FOOTER --}}
    <div class="footer">
        <span>Dokumen ini dibuat otomatis oleh Sistem Inventaris &mdash; {{ date('d/m/Y H:i') }}</span>
        <span>Halaman 1 | Laporan Inventaris Aset</span>
    </div>

</body>
</html>