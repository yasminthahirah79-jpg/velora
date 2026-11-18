@extends('layouts.app')
@section('title', 'Laporan')
@section('content')
<div class="top"><div><h1>Laporan</h1><p class="sub">Pilih jenis laporan dan periode, lalu ekspor ke PDF atau Excel.</p></div></div>
<form method="get" class="card w" style="min-height:0;margin-bottom:14px"><div class="f" style="grid-template-columns:1fr 1fr 1fr"><div><label>Jenis laporan</label><select name="jenis">@foreach(['pembelian' => 'Pembelian', 'hutang' => 'Hutang', 'pembayaran' => 'Pembayaran', 'kas' => 'Kas'] as $v => $l)<option value="{{ $v }}" @selected($jenis == $v)>{{ $l }}</option>@endforeach</select></div>
<div><label>Dari tanggal</label><input type="date" name="dari" value="{{ $dari }}"></div><div><label>Sampai tanggal</label><input type="date" name="sampai" value="{{ $sampai }}"></div></div>
<div style="display:flex;gap:10px;margin-top:12px"><button class="btn">Tampilkan</button><button class="btn l" name="export" value="excel">Ekspor Excel</button><button class="btn l" name="export" value="pdf">Ekspor PDF</button></div></form>
@php $g = 'repeat(' . count($head) . ',1fr)'; @endphp
<div class="row h" style="grid-template-columns:{{ $g }}">@foreach($head as $h)<span>{{ $h }}</span>@endforeach</div>
@forelse($rows as $r)<div class="row" style="grid-template-columns:{{ $g }}">@foreach($r as $c)<span>{{ $c }}</span>@endforeach</div>@empty<p class="sub">Tidak ada data pada periode ini.</p>@endforelse
<div class="tot"><span>{{ $jenis == 'kas' ? 'Saldo periode' : 'Total periode' }}</span><span>Rp {{ number_format($total, 0, ',', '.') }}</span></div>
@endsection
