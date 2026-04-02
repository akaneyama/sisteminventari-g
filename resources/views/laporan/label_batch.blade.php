<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Label Batch — {{ $barangs->count() }} Barang</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background: #e5e7eb;
            padding: 24px;
        }

        /* Action bar */
        .action-bar {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 24px;
            background: white;
            padding: 14px 20px;
            border-radius: 14px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        }
        .action-title { flex: 1; font-weight: 700; color: #1f2937; font-size: 15px; }
        .action-title span { color: #6b7280; font-weight: 500; font-size: 13px; margin-left: 8px; }
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 9px 18px;
            border-radius: 9px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            border: none;
            text-decoration: none;
            transition: all 0.15s;
        }
        .btn-print { background: #1e40af; color: white; }
        .btn-print:hover { background: #1d4ed8; transform: translateY(-1px); box-shadow: 0 4px 12px rgba(30,64,175,0.35); }
        .btn-back  { background: white; color: #374151; border: 1px solid #d1d5db; }
        .btn-back:hover { background: #f3f4f6; }

        /* Grid layout untuk print */
        .labels-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 14px;
            max-width: 960px;
            margin: 0 auto;
        }

        /* Label card */
        .label-card {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 16px rgba(0,0,0,0.08);
        }
        .label-header {
            background: linear-gradient(135deg, #1e40af, #3b82f6);
            color: white;
            padding: 9px 14px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .label-header .school { font-size: 9px; font-weight: 700; letter-spacing: 0.5px; text-transform: uppercase; }
        .label-header .inv-badge { font-size: 8px; font-weight: 700; background: rgba(255,255,255,0.25); padding: 2px 7px; border-radius: 20px; }

        .label-body {
            display: flex;
            align-items: center;
            padding: 14px;
            gap: 12px;
        }
        .label-info { flex: 1; }
        .item-name   { font-size: 13px; font-weight: 800; color: #111827; text-transform: uppercase; line-height: 1.2; margin-bottom: 4px; }
        .item-code   { font-size: 11px; font-weight: 700; color: #1d4ed8; letter-spacing: 0.5px; margin-bottom: 8px; }
        .meta-row    { display: flex; gap: 5px; margin-bottom: 3px; align-items: baseline; }
        .meta-label  { font-size: 8px; font-weight: 700; color: #9ca3af; text-transform: uppercase; width: 44px; flex-shrink: 0; }
        .meta-value  { font-size: 10px; color: #374151; font-weight: 600; }

        .label-qr { flex-shrink: 0; display: flex; flex-direction: column; align-items: center; gap: 4px; }
        .qr-box { border: 1.5px solid #e5e7eb; border-radius: 8px; padding: 6px; background: #fafafa; }
        .qr-box svg, .qr-box img { display: block; width: 75px !important; height: 75px !important; }
        .scan-text { font-size: 7.5px; color: #9ca3af; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; }

        .label-footer {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 8px 14px;
            background: #f9fafb;
            border-top: 1px solid #f3f4f6;
        }
        .cat-badge { font-size: 9px; font-weight: 700; background: #dbeafe; color: #1e40af; padding: 2px 8px; border-radius: 20px; }
        .loc-text  { font-size: 9px; color: #6b7280; font-weight: 600; }

        /* PRINT */
        @media print {
            body { background: white; padding: 0; }
            .action-bar { display: none; }
            .labels-grid {
                display: grid;
                grid-template-columns: repeat(2, 1fr);
                gap: 8px;
                max-width: 100%;
            }
            .label-card {
                box-shadow: none;
                border: 1px solid #d1d5db;
                break-inside: avoid;
            }
            @page { margin: 8mm; size: A4; }
        }
    </style>
</head>
<body>

    <div class="action-bar no-print">
        <div class="action-title">
            Cetak Label Batch
            <span>{{ $barangs->count() }} label dipilih</span>
        </div>
        <a href="javascript:history.back()" class="btn btn-back">← Kembali</a>
        <button onclick="window.print()" class="btn btn-print">🖨️ &nbsp;Cetak Semua Label</button>
    </div>

    <div class="labels-grid">
        @foreach($barangs as $barang)
        <div class="label-card">
            <div class="label-header">
                <span class="school">Inventaris Aset &mdash; Sekolah</span>
                <span class="inv-badge">INVENTARIS</span>
            </div>

            <div class="label-body">
                <div class="label-info">
                    <div class="item-name">{{ $barang->nama_barang }}</div>
                    <div class="item-code">{{ $barang->kode_inventaris }}</div>

                    <div class="meta-row">
                        <span class="meta-label">Kategori</span>
                        <span class="meta-value">{{ $barang->kategori->nama_kategori ?? '-' }}</span>
                    </div>
                    <div class="meta-row">
                        <span class="meta-label">Lokasi</span>
                        <span class="meta-value">{{ $barang->lokasi->nama_ruangan ?? '-' }}</span>
                    </div>
                    <div class="meta-row">
                        <span class="meta-label">Kondisi</span>
                        <span class="meta-value">{{ $barang->kondisi }}</span>
                    </div>
                    <div class="meta-row">
                        <span class="meta-label">Jumlah</span>
                        <span class="meta-value">{{ $barang->jumlah_barang }} unit</span>
                    </div>
                </div>

                <div class="label-qr">
                    <div class="qr-box">
                        {!! QrCode::size(75)->generate($barang->kode_inventaris) !!}
                    </div>
                    <span class="scan-text">Scan QR</span>
                </div>
            </div>

            <div class="label-footer">
                <span class="cat-badge">{{ $barang->kategori->nama_kategori ?? 'Umum' }}</span>
                <span class="loc-text">📍 {{ $barang->lokasi->nama_ruangan ?? '-' }}</span>
            </div>
        </div>
        @endforeach
    </div>

</body>
</html>
