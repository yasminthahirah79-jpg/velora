@extends('layouts.app')
@section('title', 'Kas')
@section('content')
@php $rp = fn ($v) => 'Rp ' . number_format($v, 0, ',', '.'); @endphp
<div class="top"><div><h1>Kas</h1><p class="sub">Catatan kas masuk dan kas keluar. Pembayaran hutang tercatat otomatis sebagai kas keluar.</p></div></div>
<div class="cards" style="grid-template-columns:repeat(3,1fr);margin-bottom:14px"><div class="card y" style="min-height:96px"><p>Kas masuk</p><h1 style="font-size:24px">{{ $rp($masuk) }}</h1></div><div class="card p" style="min-height:96px"><p>Kas keluar</p><h1 style="font-size:24px">{{ $rp($keluar) }}</h1></div><div class="card g" style="min-height:96px"><p>Saldo kas</p><h1 style="font-size:24px">{{ $rp($masuk - $keluar) }}</h1></div></div>
<details class="card w" style="margin-bottom:14px;min-height:0"><summary class="btn" style="display:inline-block">+ Catat Kas</summary><form method="post" action="{{ route('kas.store') }}" style="margin-top:14px">@csrf
<div class="f"><div><label>Tanggal</label><input type="date" name="tanggal" value="{{ now()->toDateString() }}"></div><div><label>Jenis</label><select name="jenis"><option value="masuk">Kas masuk</option><option value="keluar">Kas keluar</option></select></div>
<div><label>Kategori</label><input name="kategori" required></div><div><label>Jumlah</label><input type="number" step="any" min="1" name="jumlah" required></div><div><label>Keterangan</label><input name="keterangan"></div></div>
<div style="text-align:right;margin-top:12px"><button class="btn">Simpan</button></div></form></details>
<div class="row h" style="grid-template-columns:.9fr 1.6fr 1fr 1fr"><span>Tanggal</span><span>Keterangan</span><span>Jenis</span><span>Jumlah</span></div>
@foreach($items as $k)<div class="row" style="grid-template-columns:.9fr 1.6fr 1fr 1fr"><span>{{ $k->tanggal }}</span><span>{{ $k->keterangan ?: $k->kategori }}</span><span><span class="pill {{ $k->jenis == 'masuk' ? 'ok' : 'bd' }}">{{ ucfirst($k->jenis) }}</span></span><span>{{ $rp($k->jumlah) }}</span></div>@endforeach
<div style="margin-top:12px">{{ $items->links('pagination::simple-default') }}</div>
@endsection
