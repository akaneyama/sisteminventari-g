<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Label Inventaris — {{ $barang->kode_inventaris }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background: #e5e7eb;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 24px;
        }

        /* --- Action Bar (tidak ikut cetak) --- */
        .action-bar {
            display: flex;
            gap: 12px;
            margin-bottom: 24px;
            align-items: center;
        }
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 20px;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            border: none;
            text-decoration: none;
            transition: all 0.2s;
        }
        .btn-print { background: #1e40af; color: white; }
        .btn-print:hover { background: #1d4ed8; transform: translateY(-1px); box-shadow: 0 4px 12px rgba(30,64,175,0.4); }
        .btn-back  { background: white; color: #374151; border: 1px solid #d1d5db; }
        .btn-back:hover { background: #f9fafb; }

        /* --- Label Card --- */
        .label-wrapper {
            background: white;
            border-radius: 16px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.12);
            overflow: hidden;
            width: 460px;
        }

        /* Header strip biru */
        .label-header {
            background: linear-gradient(135deg, #1e40af, #3b82f6);
            color: white;
            padding: 12px 18px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .label-header .school-name {
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            opacity: 0.95;
        }
        .label-header .inv-badge {
            font-size: 9px;
            font-weight: 600;
            background: rgba(255,255,255,0.25);
            padding: 2px 8px;
            border-radius: 20px;
            letter-spacing: 0.5px;
        }

        /* Body utama */
        .label-body {
            display: flex;
            align-items: center;
            padding: 18px;
            gap: 16px;
        }

        /* Info teks */
        .label-info { flex: 1; }
        .label-info .item-name {
            font-size: 16px;
            font-weight: 800;
            color: #111827;
            line-height: 1.2;
            margin-bottom: 6px;
            text-transform: uppercase;
        }
        .label-info .item-code {
            font-size: 13px;
            font-weight: 700;
            color: #1d4ed8;
            letter-spacing: 0.5px;
            margin-bottom: 10px;
        }
        .label-info .meta-row {
            display: flex;
            align-items: center;
            gap: 6px;
            margin-bottom: 4px;
        }
        .label-info .meta-label {
            font-size: 9px;
            font-weight: 700;
            color: #9ca3af;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            width: 48px;
            flex-shrink: 0;
        }
        .label-info .meta-value {
            font-size: 11px;
            color: #374151;
            font-weight: 600;
        }

        /* QR area */
        .label-qr {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 6px;
            flex-shrink: 0;
        }
        .label-qr .qr-box {
            border: 2px solid #e5e7eb;
            border-radius: 10px;
            padding: 8px;
            background: #fafafa;
        }
        .label-qr .qr-box svg, .label-qr .qr-box img {
            display: block;
            width: 90px !important;
            height: 90px !important;
        }
        .label-qr .scan-text {
            font-size: 8.5px;
            color: #9ca3af;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* Divider */
        .label-divider { height: 1px; background: #f3f4f6; margin: 0 18px; }

        /* Footer strip */
        .label-footer {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 10px 18px;
            background: #f9fafb;
        }
        .label-footer .category-badge {
            font-size: 10px;
            font-weight: 700;
            background: #dbeafe;
            color: #1e40af;
            padding: 3px 10px;
            border-radius: 20px;
        }
        .label-footer .location-text {
            font-size: 10px;
            color: #6b7280;
            font-weight: 600;
        }

        /* PRINT ─────────────────────────────── */
        @media print {
            body {
                background: white;
                justify-content: flex-start;
                padding: 0;
            }
            .action-bar { display: none; }
            .label-wrapper {
                box-shadow: none;
                border: 1.5px solid #d1d5db;
                border-radius: 8px;
                width: 100%;
                max-width: 460px;
            }
            @page { margin: 8mm; size: auto; }
        }
    </style>
</head>
<body>

    <div class="action-bar no-print">
        <a href="javascript:history.back()" class="btn btn-back">
            ← Kembali
        </a>
        <button onclick="window.print()" class="btn btn-print">
            🖨️ &nbsp;Cetak Label Sekarang
        </button>
    </div>

    <div class="label-wrapper">
        <!-- Header -->
        <div class="label-header">
            <div class="school-name">Inventaris Aset &mdash; Sekolah</div>
            <div class="inv-badge">INVENTARIS</div>
        </div>

        <!-- Body -->
        <div class="label-body">
            <div class="label-info">
                <div class="item-name">{{ $barang->nama_barang }}</div>
                <div class="item-code">{{ $barang->kode_inventaris }}</div>

                <div class="meta-row">
                    <span class="meta-label">Merk</span>
                    <span class="meta-value">{{ $barang->merk_type ?: '-' }}</span>
                </div>
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
            </div>

            <div class="label-qr">
                <div class="qr-box">
                    {!! QrCode::size(90)->generate($barang->kode_inventaris) !!}
                </div>
                <span class="scan-text">Scan QR</span>
            </div>
        </div>

        <div class="label-divider"></div>

        <!-- Footer -->
        <div class="label-footer">
            <span class="category-badge">{{ $barang->kategori->nama_kategori ?? 'Umum' }}</span>
            <span class="location-text">📍 {{ $barang->lokasi->nama_ruangan ?? '-' }}</span>
        </div>
    </div>

</body>
</html>