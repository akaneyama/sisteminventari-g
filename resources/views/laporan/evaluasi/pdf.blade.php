<!DOCTYPE html>
<html>
<head>
    <title>Laporan Evaluasi</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #000; padding-bottom: 10px; }
        .header h2, .header h3 { margin: 0; padding: 2px; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border: 1px solid #333; padding: 8px; text-align: left; }
        th { background-color: #f3f4f6; font-weight: bold; }
        .footer { margin-top: 30px; text-align: right; font-size: 11px; }
    </style>
</head>
<body>

    <div class="header">
        <h2>REKAPITULASI EVALUASI LAPORAN</h2>
        <h3>Sistem Inventaris Aset</h3>
    </div>

    @if(isset($filter['periode']) && $filter['periode'] != '')
        <p><strong>Filter Periode:</strong> {{ $filter['periode'] }}</p>
    @endif
    @if(isset($filter['status']) && $filter['status'] != '')
        <p><strong>Filter Status:</strong> {{ $filter['status'] }}</p>
    @endif

    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="20%">Periode</th>
                <th width="45%">Catatan/Instruksi</th>
                <th width="15%">Status</th>
                <th width="15%">Tanggal Dikirim</th>
            </tr>
        </thead>
        <tbody>
            @forelse($evaluasis as $item)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $item->periode }}</td>
                <td>{{ $item->catatan }}</td>
                <td>{{ $item->status }}</td>
                <td>{{ $item->created_at->format('d-m-Y H:i') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="5" style="text-align: center;">Tidak ada data evaluasi.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p>Dicetak pada: {{ now()->format('d F Y H:i') }}</p>
    </div>

</body>
</html>
