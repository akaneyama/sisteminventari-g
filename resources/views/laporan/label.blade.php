<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Cetak Label - {{ $barang->kode_inventaris }}</title>
    <style>
        body { font-family: sans-serif; display: flex; justify-content: center; align-items: center; height: 100vh; background-color: #f0f0f0; margin: 0; }
        .label-box { 
            width: 400px; 
            border: 2px solid #000; 
            background: #fff; 
            padding: 15px; 
            display: flex; 
            align-items: center; 
            justify-content: space-between;
        }
        .logo-area { width: 60px; text-align: center; }
        .text-area { flex-grow: 1; padding: 0 15px; border-left: 2px solid #000; border-right: 2px solid #000; margin: 0 15px; }
        .qr-area { width: 80px; text-align: center; }
        h4 { margin: 0 0 5px 0; font-size: 14px; }
        p { margin: 0; font-size: 16px; font-weight: bold; }
        /* Hilangkan background saat diprint */
        @media print {
            body { background: #fff; }
            .no-print { display: none; }
        }
    </style>
</head>
<body>
    
    <div style="text-align: center;">
        <button class="no-print" onclick="window.print()" style="margin-bottom: 20px; padding: 10px 20px; cursor:pointer; font-size:16px;">🖨️ Cetak Label Sekarang</button>
        
        <div class="label-box">
            <div class="logo-area">
                <strong style="font-size: 10px;">LOGO<br>SEKOLAH</strong>
            </div>
            <div class="text-area">
                <h4>NAMA BARANG =</h4>
                <p>{{ strtoupper($barang->nama_barang) }}</p>
                <h4 style="margin-top: 10px;">KODE BARANG =</h4>
                <p>{{ $barang->kode_inventaris }}</p>
            </div>
            <div class="qr-area">
                {!! QrCode::size(70)->generate($barang->kode_inventaris) !!}
            </div>
        </div>
    </div>

</body>
</html>