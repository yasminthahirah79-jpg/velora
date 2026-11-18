<html><head><meta charset="utf-8"><style>body{font-family:DejaVu Sans,sans-serif;font-size:11px}table{width:100%;border-collapse:collapse}th,td{border:1px solid #444;padding:5px 6px;text-align:left}th{background:#eee}</style></head><body>
<h2>Laporan {{ ucfirst($jenis) }} — Velora</h2><p>Periode {{ $dari }} s.d. {{ $sampai }}</p>
<table><tr>@foreach($head as $h)<th>{{ $h }}</th>@endforeach</tr>@foreach($rows as $r)<tr>@foreach($r as $c)<td>{{ $c }}</td>@endforeach</tr>@endforeach</table>
<p><b>{{ $jenis == 'kas' ? 'Saldo periode' : 'Total periode' }}: Rp {{ number_format($total, 0, ',', '.') }}</b></p></body></html>
