@extends('layouts.app')
@section('title', 'Dashboard')
@section('content')
@php $rp = fn ($v) => 'Rp ' . number_format($v, 0, ',', '.'); $d = now()->startOfMonth(); $mx = max($grafik->max(), 1); @endphp
<div class="top"><div><h1>Selamat datang, {{ auth()->user()->name }}</h1><p class="sub">Velora mencatat {{ $jumlahPembelian }} pembelian bulan ini. {{ $jatuhTempo->count() }} tagihan hutang jatuh tempo dalam 7 hari ke depan.</p></div></div>
<div class="grid"><div><div class="cards">
<div class="card y"><h3>Total Pembelian</h3><div class="st"><div><b>{{ $jumlahPembelian }}</b>Transaksi</div><div><b>{{ $rp($totalPembelian) }}</b>Bulan ini</div></div>
<svg class="ch" viewBox="0 0 200 60">@foreach($grafik as $k => $v)<rect x="{{ $k * 20 + 4 }}" y="{{ 60 - max(4, $v / $mx * 56) }}" width="9" height="{{ max(4, $v / $mx * 56) }}" rx="4" fill="#141414"/>@endforeach</svg></div>
<div class="card p"><h3>Ringkasan Hutang</h3><div class="st"><div><b>{{ $rp($hutangBelumLunas) }}</b>Sisa hutang</div><div><b>{{ $jatuhTempo->count() }}</b>Jatuh tempo</div></div></div>
<div class="card g"><h3>Status Hutang</h3><div class="st"><div><b>{{ $status['lunas'] ?? 0 }}</b>Lunas</div><div><b>{{ $status['sebagian'] ?? 0 }}</b>Sebagian</div><div><b>{{ $status['belum lunas'] ?? 0 }}</b>Belum lunas</div></div></div>
<div class="card b"><h3>Kas Bulan Ini</h3><div class="st"><div><b>{{ $rp($kasMasuk) }}</b>Masuk</div><div><b>{{ $rp($kasKeluar) }}</b>Keluar</div></div><p>Saldo {{ $rp($kasMasuk - $kasKeluar) }}</p></div></div>
<h2>Pembelian Terbaru @if(in_array(auth()->user()->role, ['admin', 'pembelian']))<a class="pill" href="{{ route('purchases.index') }}">Lihat semua</a>@endif</h2>
@foreach($terbaru as $p)<div class="row {{ $loop->first ? 'hi' : '' }}" style="grid-template-columns:1.2fr 1.4fr 1fr 1fr"><span>{{ $p->no_pembelian }}</span><span>{{ $p->supplier->nama }}</span><span>{{ $rp($p->total) }}</span><span><span class="pill">{{ $p->status == 'dibatalkan' ? 'Dibatalkan' : ucfirst($p->payable?->status) }}</span></span></div>@endforeach</div>
<div><div class="card w"><b>{{ now()->locale('id')->translatedFormat('F Y') }}</b>
<div class="cal">@foreach(['SEN', 'SEL', 'RAB', 'KAM', 'JUM', 'SAB', 'MIN'] as $x)<span class="d">{{ $x }}</span>@endforeach
@for($i = $d->dayOfWeekIso - 1; $i > 0; $i--)<span class="m">{{ $d->copy()->subDays($i)->day }}</span>@endfor
@for($i = 1; $i <= $d->daysInMonth; $i++)<span class="{{ $i == now()->day ? 't' : '' }}">{{ $i }}</span>@endfor</div>
<h2 style="margin-top:6px">Jatuh tempo terdekat</h2>
@forelse($jatuhTempo as $h)<div class="tl"><i>!</i><div><b>{{ $h->supplier->nama }}</b><p>{{ $h->no_tagihan }} · {{ $h->jatuh_tempo->format('d M') }} · {{ $rp($h->sisa) }}</p></div></div>@empty<p class="sub">Tidak ada hutang yang akan jatuh tempo.</p>@endforelse
@if(in_array(auth()->user()->role, ['admin', 'keuangan']))<a class="btn" style="display:block;text-align:center;text-decoration:none" href="{{ route('payments.index') }}">Lihat semua hutang</a>@endif</div></div></div>
@endsection
