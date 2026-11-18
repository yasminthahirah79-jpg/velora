@extends('layouts.app')
@section('title', 'Pembelian')
@section('content')
<div class="top"><div><h1>Pembelian</h1><p class="sub">Catatan pembelian barang dari supplier. Stok dan hutang diperbarui otomatis.</p></div><a class="btn" style="text-decoration:none" href="{{ route('purchases.create') }}">+ Catat Pembelian</a></div>
<div class="row h" style="grid-template-columns:1.1fr .9fr 1.5fr 1fr 1fr .9fr"><span>No. Pembelian</span><span>Tanggal</span><span>Supplier</span><span>Total</span><span>Jatuh tempo</span><span>Status</span></div>
@foreach($purchases as $p)<a class="row {{ $loop->first ? 'hi' : '' }}" href="{{ route('purchases.show', $p) }}" style="grid-template-columns:1.1fr .9fr 1.5fr 1fr 1fr .9fr"><span>{{ $p->no_pembelian }}</span><span>{{ $p->tanggal->format('d M Y') }}</span><span>{{ $p->supplier->nama }}</span><span>Rp {{ number_format($p->total, 0, ',', '.') }}</span><span>{{ $p->jatuh_tempo->format('d M Y') }}</span>
<span><span class="pill {{ $p->status == 'dibatalkan' ? 'bd' : ['lunas' => 'ok', 'sebagian' => 'in'][$p->payable?->status] ?? 'wn' }}">{{ $p->status == 'dibatalkan' ? 'Dibatalkan' : ucfirst($p->payable?->status) }}</span></span></a>@endforeach
<div style="margin-top:12px">{{ $purchases->links('pagination::simple-default') }}</div>
@endsection
